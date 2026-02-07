<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Models\JadwalIbadahUtamaModel;
use App\Models\JadwalPetugasDetailModel;

class JadwalPelayanan extends BaseController
{
    protected $utamaModel;
    protected $detailModel;
    protected $logModel;

    public function __construct()
    {
        $this->utamaModel = new JadwalIbadahUtamaModel();
        $this->detailModel = new JadwalPetugasDetailModel();
        $this->logModel = new \App\Models\ActivityLogModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen Jadwal Pelayanan',
            'jadwal' => $this->utamaModel->orderBy('tanggal', 'DESC')->findAll(),
        ];
        return view('dashboard/jadwal_pelayanan/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Tambah Jadwal Pelayanan'];
        return view('dashboard/jadwal_pelayanan/create', $data);
    }

    public function store()
    {
        // Validation with dynamic rules is tricky, simplified here
        if (!$this->validate([
            'tanggal' => 'required|valid_date',
            'status' => 'required',
            'sessions' => 'required', // Expecting array
            'jenis_tugas' => 'required', // Expecting array
        ])) {
            return redirect()->back()->withInput()->with('error', 'Data tidak valid. Pastikan tanggal dan sesi diisi.');
        }

        $tanggal = $this->request->getPost('tanggal');
        $tema = $this->request->getPost('tema') ?? '';
        $status = $this->request->getPost('status');
        
        $sessions = $this->request->getPost('sessions'); // [id => [name, time]]
        $jenisTugas = $this->request->getPost('jenis_tugas'); // [0 => 'Pengkotbah', ...]
        $petugasData = $this->request->getPost('petugas'); // [id => [0 => 'Name', ...]]

        if (!is_array($sessions) || empty($sessions)) {
            return redirect()->back()->withInput()->with('error', 'Minimal satu sesi harus dibuat.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        foreach ($sessions as $sessId => $sessConfig) {
            // 1. Insert Header for this Session
            $headerData = [
                'tanggal' => $tanggal,
                'nama_ibadah' => $sessConfig['name'], // Save full name as input
                'jam' => $sessConfig['time'],
                'tema' => $tema,
                'status' => $status
            ];
            
            $this->utamaModel->insert($headerData);
            $idJadwal = $this->utamaModel->getInsertID();

            // 2. Insert Details for this Session
            // Get the petugas names for this session from the matrix
            $sessPetugasList = $petugasData[$sessId] ?? [];
            
            foreach ($jenisTugas as $index => $roleName) {
                if (empty($roleName)) continue; // Skip empty role names

                $petugasName = $sessPetugasList[$index] ?? '';
                
                // Only insert if there's a name (or meaningful data)
                // Actually, empty string is fine if user wants blank slot displayed? 
                // Let's insert even empty to maintain structure, or skip?
                // Standard: insert if role is defined. display handles empty name.
                
                $this->detailModel->insert([
                    'id_jadwal_utama' => $idJadwal,
                    'jenis_tugas' => $roleName,
                    'nama_petugas' => $petugasName
                ]);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan jadwal.');
        }

        return redirect()->to(base_url('dashboard/jadwal_pelayanan'))->with('success', 'Jadwal berhasil dibuat.');
    }

    public function edit($id)
    {
        // 1. Find the schedule to identify the Date
        $initialJadwal = $this->utamaModel->find($id);
        if (!$initialJadwal) {
            return redirect()->to(base_url('dashboard/jadwal_pelayanan'))->with('error', 'Jadwal tidak ditemukan.');
        }
        
        $tanggal = $initialJadwal['tanggal'];
        
        // 2. Fetch ALL schedules for this Date
        $allSchedules = $this->utamaModel->where('tanggal', $tanggal)->orderBy('jam', 'ASC')->findAll();
        
        // 3. Prepare Sessions Data for View
        $sessionsData = [];
        $existingRoles = [];

        foreach ($allSchedules as $schedule) {
            // Get details for this schedule
            $details = $this->detailModel->where('id_jadwal_utama', $schedule['id_jadwal_utama'])->findAll();
            
            // Extract session name from "Ibadah Pagi" -> "Pagi"
            $sessName = $schedule['nama_ibadah'];
            
            $sessionsData[$schedule['id_jadwal_utama']] = [ // Key by actual DB ID
                'id' => $schedule['id_jadwal_utama'], // also keep inside
                'name' => $sessName,
                'time' => date('H:i', strtotime($schedule['jam'])),
                'color' => 'blue', // Default, view can assign cyclical colors
                'details' => $details
            ];

            // Collect unique roles
            foreach ($details as $d) {
                if (!in_array($d['jenis_tugas'], $existingRoles)) {
                    $existingRoles[] = $d['jenis_tugas'];
                }
            }
        }
        
        // Use first schedule for global fields (Tema, Status)
        $data = [
            'id' => $id, // Use the requested ID
            'tanggal' => $tanggal,
            'tema' => $initialJadwal['tema'],
            'status' => $initialJadwal['status'],
            'sessionsData' => $sessionsData,
            'existingRoles' => $existingRoles
        ];

        return view('dashboard/jadwal_pelayanan/edit', $data);
    }

    public function update($id)
    {
        // $id is just one of the schedules on this date. We use it to find the date.
        
        $initialJadwal = $this->utamaModel->find($id);
        if (!$initialJadwal) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
        
        $tanggal = $this->request->getPost('tanggal'); // New date?
        $tema = $this->request->getPost('tema') ?? '';
        $status = $this->request->getPost('status');
        
        $sessions = $this->request->getPost('sessions'); // [id => [name, time]]
        $jenisTugas = $this->request->getPost('jenis_tugas');
        $petugasData = $this->request->getPost('petugas');

        if (!is_array($sessions) || empty($sessions)) {
            return redirect()->back()->withInput()->with('error', 'Minimal satu sesi harus ada.');
        }

        $db = \Config\Database::connect();
        $db->transStart();
        
        // Track which DB IDs are processed/kept
        $keptIds = [];
        
        foreach ($sessions as $sessInputId => $sessConfig) {
            // Determine if $sessInputId is a real DB ID (numeric) or new (string 'sess_...')
            $isNew = !is_numeric($sessInputId);
            $currentId = null;

            $headerData = [
                'tanggal' => $tanggal,
                'nama_ibadah' => $sessConfig['name'],
                'jam' => $sessConfig['time'],
                'tema' => $tema,
                'status' => $status
            ];

            if ($isNew) {
                // Insert New Session
                $this->utamaModel->insert($headerData);
                $currentId = $this->utamaModel->getInsertID();
                $keptIds[] = $currentId; // It's a valid ID now
            } else {
                // Update Existing Session - Verify it exists?
                // Assuming ID provided is valid if numeric.
                $this->utamaModel->update($sessInputId, $headerData);
                $currentId = $sessInputId;
                $keptIds[] = $currentId;
            }

            // Sync Details: Delete old, Insert new
            $this->detailModel->where('id_jadwal_utama', $currentId)->delete();
            
            $sessPetugasList = $petugasData[$sessInputId] ?? [];
            
            foreach ($jenisTugas as $index => $roleName) {
                if (empty($roleName)) continue;
                
                $petugasName = $sessPetugasList[$index] ?? '';
                
                $this->detailModel->insert([
                    'id_jadwal_utama' => $currentId,
                    'jenis_tugas' => $roleName,
                    'nama_petugas' => $petugasName
                ]);
            }
        }
        
        // Delete Removed Sessions
        $originalDate = $initialJadwal['tanggal'];
        // All schedules on original date
        $existingSchedules = $this->utamaModel->where('tanggal', $originalDate)->findAll();
        
        foreach ($existingSchedules as $ex) {
            if (!in_array($ex['id_jadwal_utama'], $keptIds)) {
                $this->utamaModel->delete($ex['id_jadwal_utama']); 
                $this->detailModel->where('id_jadwal_utama', $ex['id_jadwal_utama'])->delete(); 
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui jadwal.');
        }

        return redirect()->to(base_url('dashboard/jadwal_pelayanan'))->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function delete($id)
    {
        // Cascade delete handles detail rows automatically via DB constraint, 
        // but explicit deletion is safer if DB foreign keys aren't set up perfectly by user.
        // Migration has ON DELETE CASCADE, so just delete header is enough.
        // Migration has ON DELETE CASCADE, so just delete header is enough.
        $oldData = $this->utamaModel->asArray()->find($id);
        $this->utamaModel->delete($id);
        
        $this->logModel->add('DELETE', 'jadwal_ibadah_utama', $id, $oldData, null);
        return redirect()->to('/dashboard/jadwal_pelayanan')->with('success', 'Jadwal berhasil dihapus.');
    }
}
