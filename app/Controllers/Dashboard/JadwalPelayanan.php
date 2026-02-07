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
        if (!$this->validate([
            'tanggal'     => 'required',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal. Tanggal harus diisi.');
        }

        $tanggal = $this->request->getPost('tanggal');
        $tema    = $this->request->getPost('tema');
        $status  = $this->request->getPost('status') ?? 'aktif';

        // Prepare Sessions
        $sessions = [
            'pagi' =>  ['default_jam' => '06:00', 'nama' => 'Ibadah Pagi', 'input_key' => 'petugas_pagi'],
            'siang' => ['default_jam' => '09:00', 'nama' => 'Ibadah Siang', 'input_key' => 'petugas_siang'],
            'sore' =>  ['default_jam' => '17:00', 'nama' => 'Ibadah Sore', 'input_key' => 'petugas_sore'],
        ];

        $jenisTugas = $this->request->getPost('jenis_tugas'); // Array of Roles

        $db = \Config\Database::connect();
        $db->transStart();

        foreach ($sessions as $key => $session) {
            // Check if this session is active/checked
            $isActive = $this->request->getPost('sess_' . $key . '_active') ? true : false;
            
            if (!$isActive) {
                continue; // Skip if not active
            }

            // Get Time from Input or Default
            $jam = $this->request->getPost('sess_' . $key . '_time');
            if (empty($jam)) $jam = $session['default_jam'];

            // 1. Create Header for this Session
            $headerData = [
                'id_gereja'   => 1, // Default
                'tanggal'     => $tanggal,
                'jam'         => $jam,
                'nama_ibadah' => $session['nama'],
                'tema'        => $tema,
                'status'      => $status,
            ];
            
            $this->utamaModel->insert($headerData);
            $idJadwal = $this->utamaModel->getInsertID();

            // 2. Insert Details for this Session
            $petugasNames = $this->request->getPost($session['input_key']); // Array of names for this session

            if (!empty($jenisTugas) && !empty($petugasNames)) {
                $details = [];
                foreach ($jenisTugas as $index => $role) {
                    $name = $petugasNames[$index] ?? '';
                    if (!empty($role) && !empty($name) && trim($name) !== '-') {
                        $details[] = [
                            'id_jadwal_utama' => $idJadwal,
                            'jenis_tugas'     => $role,
                            'nama_petugas'    => $name
                        ];
                    }
                }
                if(!empty($details)) {
                    $this->detailModel->insertBatch($details);
                }
            }
            
            $this->logModel->add('CREATE', 'jadwal_ibadah_utama', $idJadwal, null, ['session' => $session['nama'], 'data' => $headerData]);
        }

        $db->transComplete();

        if ($db->transStatus() === FALSE) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data jadwal.');
        }

        return redirect()->to('/dashboard/jadwal_pelayanan')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit($id)
    {
        // 1. Find the initial schedule to get the Date
        $initialJadwal = $this->utamaModel->find($id);
        if (!$initialJadwal) {
            return redirect()->to('/dashboard/jadwal_pelayanan')->with('error', 'Data tidak ditemukan.');
        }

        $tanggal = $initialJadwal['tanggal'];

        // 2. Fetch ALL schedules for this Date
        $allSchedules = $this->utamaModel->where('tanggal', $tanggal)->findAll();

        // 3. Organize Data by Session Name (Robust way)
        // Fallback to time if name not standard? 
        // Let's assume 'nama_ibadah' is our key 'Ibadah Pagi', 'Ibadah Siang', 'Ibadah Sore'
        // Or if legacy data, time-based.
        
        $sessionsData = [
            'pagi' => null,
            'siang' => null,
            'sore' => null
        ];

        foreach ($allSchedules as $sch) {
            $name = strtolower($sch['nama_ibadah']);
            $time = date('H:i', strtotime($sch['jam'])); 

            // Flexible matching
            if (strpos($name, 'pagi') !== false || $time == '06:00') $sessionsData['pagi'] = $sch;
            elseif (strpos($name, 'siang') !== false || $time == '09:00') $sessionsData['siang'] = $sch;
            elseif (strpos($name, 'sore') !== false || $time == '17:00') $sessionsData['sore'] = $sch;
            elseif ($time < '10:00') $sessionsData['pagi'] = $sch; // Fallback logic
            elseif ($time < '15:00') $sessionsData['siang'] = $sch;
            else $sessionsData['sore'] = $sch;
        }

        // 4. Fetch Details for each session
        foreach ($sessionsData as $key => $sch) {
            if ($sch) {
                $sessionsData[$key]['details'] = $this->detailModel->where('id_jadwal_utama', $sch['id_jadwal_utama'])->findAll();
            }
        }

        // 5. Structure for View
        $allRoles = [];
        foreach ($sessionsData as $key => $sch) {
            if ($sch && !empty($sch['details'])) {
                foreach ($sch['details'] as $det) {
                    $role = $det['jenis_tugas'];
                    if (!in_array($role, $allRoles)) {
                        $allRoles[] = $role;
                    }
                }
            }
        }
        
        $data = [
            'title'        => 'Edit Jadwal Pelayanan (Satu Hari)',
            'tanggal'      => $tanggal,
            'tema'         => $initialJadwal['tema'],
            'status'       => $initialJadwal['status'],
            'sessionsData' => $sessionsData,
            'existingRoles'=> $allRoles,
            'id'           => $id
        ];
        return view('dashboard/jadwal_pelayanan/edit', $data);
    }

    public function update($id)
    {
        if (!$this->validate([
            'tanggal'     => 'required',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal.');
        }

        $tanggal = $this->request->getPost('tanggal');
        $tema    = $this->request->getPost('tema');
        $status  = $this->request->getPost('status');

        $sessionsConfig = [
            'pagi' =>  ['default_jam' => '06:00', 'nama' => 'Ibadah Pagi', 'input_key' => 'petugas_pagi'],
            'siang' => ['default_jam' => '09:00', 'nama' => 'Ibadah Siang', 'input_key' => 'petugas_siang'],
            'sore' =>  ['default_jam' => '17:00', 'nama' => 'Ibadah Sore', 'input_key' => 'petugas_sore'],
        ];

        $jenisTugas = $this->request->getPost('jenis_tugas');

        $db = \Config\Database::connect();
        $db->transStart();

        foreach ($sessionsConfig as $key => $config) {
            $isActive = $this->request->getPost('sess_' . $key . '_active') ? true : false;
            
            // Try to find matching existing record by Name + Date
            // This allows us to update the correct slot even if time changed.
            $existing = $this->utamaModel
                ->where('tanggal', $tanggal)
                ->like('nama_ibadah', $config['nama']) // Loose match or Exact?
                ->first();
            
            // Fallback: If not found by name, try by approximate time? 
            // Risky if time changed. Let's rely on name for consistency in this flow.
            // If name differs (legacy), maybe we can't update it easily. 
            // We'll assume the system manages these names: 'Ibadah Pagi', etc.
            
            if (!$isActive) {
                // If Inactive and Exists -> DELETE
                if ($existing) {
                    $this->utamaModel->delete($existing['id_jadwal_utama']); // Logic to delete detail cascade?
                    // Assuming Database trigger or manual delete. Model delete might not cascade without setup.
                    $this->detailModel->where('id_jadwal_utama', $existing['id_jadwal_utama'])->delete();
                }
                continue;
            }

            // If Active -> Upsert
            $jam = $this->request->getPost('sess_' . $key . '_time');
            if (empty($jam)) $jam = $config['default_jam'];

            $headerData = [
                'id_gereja'   => 1,
                'tanggal'     => $tanggal,
                'jam'         => $jam,
                'nama_ibadah' => $config['nama'],
                'tema'        => $tema,
                'status'      => $status,
            ];

            $idJadwal = null;

            if ($existing) {
                // Update
                $idJadwal = $existing['id_jadwal_utama'];
                $this->utamaModel->update($idJadwal, $headerData);
                // Refresh details
                $this->detailModel->where('id_jadwal_utama', $idJadwal)->delete();
            } else {
                // Create
                $this->utamaModel->insert($headerData);
                $idJadwal = $this->utamaModel->getInsertID();
            }

            // Insert New Details
            $petugasNames = $this->request->getPost($config['input_key']); 
            if (!empty($jenisTugas) && !empty($petugasNames)) {
                $details = [];
                foreach ($jenisTugas as $index => $role) {
                    $name = $petugasNames[$index] ?? '';
                    if (!empty($role) && !empty($name) && trim($name) !== '-') {
                        $details[] = [
                            'id_jadwal_utama' => $idJadwal,
                            'jenis_tugas'     => $role,
                            'nama_petugas'    => $name
                        ];
                    }
                }
                if(!empty($details)) {
                    $this->detailModel->insertBatch($details);
                }
            }
        }

        $db->transComplete();
        
        return redirect()->to('/dashboard/jadwal_pelayanan')->with('success', 'Jadwal Hari Tersebut Berhasil Diperbarui.');
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
