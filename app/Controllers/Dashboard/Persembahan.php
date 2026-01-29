<?php
 
namespace App\Controllers\Dashboard;
 
use App\Controllers\BaseController;
use App\Models\InformasiPersembahanModel;
use App\Models\KeuanganModel;
 
class Persembahan extends BaseController
{
    protected $persembahanModel;
    protected $keuanganModel;
 
    public function __construct()
    {
        $this->persembahanModel = new InformasiPersembahanModel();
        $this->keuanganModel = new KeuanganModel();
    }
 
    public function index()
    {
        $bulan  = $this->request->getGet('bulan') ?? date('m');
        $tahun  = $this->request->getGet('tahun') ?? date('Y');
        $search = $this->request->getGet('search');

        // Initial filters on the model
        if ($bulan !== '' && $bulan !== null) {
            $this->persembahanModel->where("MONTH(tanggal) = $bulan", null, false);
        }
        if ($tahun !== '' && $tahun !== null) {
            $this->persembahanModel->where("YEAR(tanggal) = $tahun", null, false);
        }
        if ($search !== '' && $search !== null) {
            $this->persembahanModel->groupStart()
                                   ->like('judul', $search)
                                   ->orLike('deskripsi', $search)
                                   ->groupEnd();
        }

        // 1. Calculate stats using a CLONE of the builder before pagination resets it
        $statsBuilder = clone $this->persembahanModel->builder();
        $totalTerkumpul = (clone $statsBuilder)->selectSum('jumlah')->get()->getRow()->jumlah ?? 0;

        // 2. Paginate (Uses the model's current builder state)
        $persembahan = $this->persembahanModel->orderBy('tanggal', 'DESC')->paginate(10, 'persembahan');

        $data = [
            'title'            => 'Persembahan Ibadah',
            'persembahan'      => $persembahan,
            'pager'            => $this->persembahanModel->pager,
            'bulan'            => $bulan,
            'tahun'            => $tahun,
            'search'           => $search,
            'total_terkumpul'  => $totalTerkumpul
        ];
        
        return view('dashboard/persembahan/index', $data);
    }
    
    public function create()
    {
        $data = ['title' => 'Input Persembahan Baru'];
        return view('dashboard/persembahan/create', $data);
    }
 
    public function store()
    {
         if (!$this->validate([
            'tanggal'   => 'required|valid_date',
            'judul'     => 'required',
            'jumlah'    => 'required|numeric',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal.');
        }
 
        $this->persembahanModel->save([
            'tanggal'   => $this->request->getPost('tanggal'),
            'judul'     => $this->request->getPost('judul'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'jumlah'    => $this->request->getPost('jumlah'),
            'status'    => $this->request->getPost('status') ?? 'aktif',
        ]);
 
        return redirect()->to('/dashboard/persembahan')->with('success', 'Data Persembahan berhasil disimpan.');
    }
 
    public function edit($id)
    {
        $persembahan = $this->persembahanModel->find($id);
        
        // Block if posted
        if ($persembahan['is_posted']) {
            return redirect()->to('/dashboard/persembahan')->with('error', 'Data yang sudah diposting tidak dapat diedit.');
        }

        $data = ['title' => 'Edit Data Persembahan', 'persembahan' => $persembahan];
        return view('dashboard/persembahan/edit', $data);
    }
 
    public function update($id)
    {
        $persembahan = $this->persembahanModel->find($id);
        if ($persembahan['is_posted']) {
            return redirect()->to('/dashboard/persembahan')->with('error', 'Data yang sudah diposting tidak dapat diubah.');
        }

         if (!$this->validate([
            'tanggal'   => 'required|valid_date',
            'judul'     => 'required',
            'jumlah'    => 'required|numeric',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal.');
        }
        
        $this->persembahanModel->update($id, [
             'tanggal'   => $this->request->getPost('tanggal'),
             'judul'     => $this->request->getPost('judul'),
             'deskripsi' => $this->request->getPost('deskripsi'),
             'jumlah'    => $this->request->getPost('jumlah'),
             'status'    => $this->request->getPost('status'),
        ]);
        return redirect()->to('/dashboard/persembahan')->with('success', 'Data berhasil diperbarui.');
    }
 
    public function delete($id)
    {
        $persembahan = $this->persembahanModel->find($id);
        if ($persembahan['is_posted']) {
            return redirect()->to('/dashboard/persembahan')->with('error', 'Data yang sudah diposting tidak dapat dihapus.');
        }

        $this->persembahanModel->delete($id);
        return redirect()->to('/dashboard/persembahan')->with('success', 'Data dihapus.');
    }

    public function post($id)
    {
        $persembahan = $this->persembahanModel->find($id);
        $reff = $this->request->getGet('reff') ?? 'KAS';

        if (!$persembahan) {
            return redirect()->to('/dashboard/persembahan')->with('error', 'Data tidak ditemukan.');
        }

        if ($persembahan['is_posted']) {
            return redirect()->to('/dashboard/persembahan')->with('warning', 'Data sudah pernah diposting.');
        }

        // 1. Insert into Keuangan
        $this->keuanganModel->save([
            'tanggal'     => $persembahan['tanggal'],
            'keterangan'  => 'Pemasukan Persembahan: ' . $persembahan['judul'],
            'reff'        => $reff,
            'debet'       => $persembahan['jumlah'],
            'kredit'      => 0,
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        // 2. Update status at Persembahan
        $this->persembahanModel->update($id, [
            'is_posted' => 1,
            'posted_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/dashboard/persembahan')->with('success', 'Data persembahan berhasil diposting ke Buku Kas (Keuangan).');
    }
}
