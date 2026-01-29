<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Models\DiskusiModel;
use App\Models\DiskusiJawabanModel;

class Diskusi extends BaseController
{
    protected $diskusiModel;
    protected $jawabanModel;

    public function __construct()
    {
        $this->diskusiModel = new DiskusiModel();
        $this->jawabanModel = new DiskusiJawabanModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen Diskusi',
            'topics' => $this->diskusiModel->orderBy('created_at', 'DESC')->findAll(),
        ];
        return view('dashboard/diskusi/index', $data);
    }

    public function replies($id)
    {
        $topic = $this->diskusiModel->find($id);
        if (!$topic) {
            return redirect()->to('/dashboard/diskusi')->with('error', 'Topik tidak ditemukan.');
        }

        $data = [
            'title'   => 'Jawaban Diskusi: ' . $topic['judul'],
            'topic'   => $topic,
            'replies' => $this->jawabanModel->where('id_diskusi', $id)->orderBy('created_at', 'DESC')->findAll(),
        ];
        return view('dashboard/diskusi/replies', $data);
    }

    public function delete_topic($id)
    {
        $this->diskusiModel->delete($id);
        return redirect()->to('/dashboard/diskusi')->with('success', 'Topik diskusi berhasil dihapus.');
    }

    public function delete_reply($id)
    {
        $this->jawabanModel->delete($id);
        return redirect()->back()->with('success', 'Jawaban berhasil dihapus.');
    }

    public function update_status($id)
    {
        $topic = $this->diskusiModel->find($id);
        $newStatus = $topic['status'] == 'aktif' ? 'nonaktif' : 'aktif';
        
        $this->diskusiModel->update($id, ['status' => $newStatus]);
        return redirect()->to('/dashboard/diskusi')->with('success', 'Status topik berhasil diperbarui.');
    }
}
