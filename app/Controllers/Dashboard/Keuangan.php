<?php
 
namespace App\Controllers\Dashboard;
 
use App\Controllers\BaseController;
use App\Models\KeuanganModel;
 
class Keuangan extends BaseController
{
    protected $keuanganModel;
    protected $logModel;
 
    public function __construct()
    {
        $this->keuanganModel = new KeuanganModel();
        $this->logModel = new \App\Models\ActivityLogModel();
    }
 
    public function index()
    {
        $bulan  = $this->request->getGet('bulan') ?? date('m');
        $tahun  = $this->request->getGet('tahun') ?? date('Y');
        $search = $this->request->getGet('search');
        $reff   = $this->request->getGet('reff');

        // Initial filters on the model
        if ($bulan !== '' && $bulan !== null) {
            $this->keuanganModel->where("MONTH(tanggal) = $bulan", null, false);
        }
        if ($tahun !== '' && $tahun !== null) {
            $this->keuanganModel->where("YEAR(tanggal) = $tahun", null, false);
        }
        if ($reff !== '' && $reff !== null) {
            $this->keuanganModel->where('reff', $reff);
        }
        if ($search !== '' && $search !== null) {
            $this->keuanganModel->groupStart()
                                ->like('keterangan', $search)
                                ->groupEnd();
        }

        // 1. Calculate stats using a CLONE of the builder before pagination resets it
        $statsBuilder = clone $this->keuanganModel->builder();
        
        $totalDebet = (clone $statsBuilder)->selectSum('debet')->get()->getRow()->debet ?? 0;
        $totalKredit = (clone $statsBuilder)->selectSum('kredit')->get()->getRow()->kredit ?? 0;
        $saldo = $totalDebet - $totalKredit;

        // 2. Paginate (Uses the model's current builder state)
        $keuangan = $this->keuanganModel->orderBy('tanggal', 'DESC')->paginate(20, 'keuangan');

        $data = [
            'title'        => 'Manajemen Keuangan',
            'keuangan'     => $keuangan,
            'pager'        => $this->keuanganModel->pager,
            'bulan'        => $bulan,
            'tahun'        => $tahun,
            'search'       => $search,
            'reff'         => $reff,
            'total_debet'  => $totalDebet,
            'total_kredit' => $totalKredit,
            'saldo'        => $saldo
        ];
        
        return view('dashboard/keuangan/index', $data);
    }
    
    // -- KEUANGAN METHODS --
    public function create_laporan()
    {
        $data = ['title' => 'Tambah Laporan Keuangan'];
        return view('dashboard/keuangan/create_laporan', $data);
    }
 
    public function store_laporan()
    {
        if (!$this->validate([
            'tanggal'     => 'required',
            'keterangan'  => 'required',
            'debet'       => 'permit_empty|numeric',
            'kredit'      => 'permit_empty|numeric',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal.');
        }
 
        $this->keuanganModel->save([
            'tanggal'    => $this->request->getPost('tanggal'),
            'keterangan' => $this->request->getPost('keterangan'),
            'reff'       => $this->request->getPost('reff'),
            'debet'      => $this->request->getPost('debet') ?: 0,
            'kredit'     => $this->request->getPost('kredit') ?: 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // Log
        $newId = $this->keuanganModel->insertID();
        $this->logModel->add('CREATE', 'keuangan', $newId, null, $this->request->getPost());
        return redirect()->to('/dashboard/keuangan')->with('success', 'Laporan berhasil disimpan.');
    }
 
    public function edit_laporan($id)
    {
         $keuangan = $this->keuanganModel->find($id);
         $data = ['title' => 'Edit Laporan Keuangan', 'keuangan' => $keuangan];
         return view('dashboard/keuangan/edit_laporan', $data);
    }
 
    public function update_laporan($id)
    {
        if (!$this->validate([
            'tanggal'     => 'required',
            'keterangan'  => 'required',
            'debet'       => 'permit_empty|numeric',
            'kredit'      => 'permit_empty|numeric',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal.');
        }
 
        $updateData = [
            'tanggal'    => $this->request->getPost('tanggal'),
            'keterangan' => $this->request->getPost('keterangan'),
            'reff'       => $this->request->getPost('reff'),
            'debet'      => $this->request->getPost('debet') ?: 0,
            'kredit'     => $this->request->getPost('kredit') ?: 0,
        ];

        // Get Old Data
        $oldData = $this->keuanganModel->asArray()->find($id);

        $this->keuanganModel->update($id, $updateData);

        // Log Activiy
        $this->logModel->add('UPDATE', 'keuangan', $id, $oldData, $updateData);
        return redirect()->to('/dashboard/keuangan')->with('success', 'Laporan berhasil diperbarui.');
    }
 
    public function delete_laporan($id)
    {
        $oldData = $this->keuanganModel->asArray()->find($id);
        $this->keuanganModel->delete($id);
        
        $this->logModel->add('DELETE', 'keuangan', $id, $oldData, null);
        return redirect()->to('/dashboard/keuangan')->with('success', 'Data dihapus.');
    }
}
