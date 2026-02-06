<?php

namespace App\Controllers;

use App\Models\DiskusiModel;
use App\Models\DiskusiJawabanModel;
use App\Models\GerejaModel;

class Diskusi extends BaseController
{
    protected $diskusiModel;
    protected $jawabanModel;
    protected $gerejaModel;

    public function __construct()
    {
        $this->diskusiModel = new DiskusiModel();
        $this->jawabanModel = new DiskusiJawabanModel();
        $this->gerejaModel = new GerejaModel();
    }

    public function index()
    {
        $gereja = $this->gerejaModel->first();
        $keyword = $this->request->getGet('keyword');
        $topics = $this->diskusiModel->getTopicsWithCount($keyword);

        $data = [
            'title'     => 'Ruang Diskusi Jemaat',
            'gereja'    => $gereja,
            'topics'    => $topics,
        ];

        return view('frontend/diskusi/index', $data);
    }

    public function detail($id)
    {
        $gereja = $this->gerejaModel->first();
        $topic = $this->diskusiModel->where('id_diskusi', $id)->where('status', 'aktif')->first();

        if (!$topic) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $replies = $this->jawabanModel->where('id_diskusi', $id)->orderBy('created_at', 'ASC')->findAll();

        $data = [
            'title'     => 'Diskusi: ' . $topic['judul'],
            'gereja'    => $gereja,
            'topic'     => $topic,
            'replies'   => $replies,
        ];

        return view('frontend/diskusi/detail', $data);
    }

    public function submit_topic()
    {
        $this->diskusiModel->save([
            'id_gereja' => 1, // Default church ID
            'judul'     => $this->request->getPost('judul'),
            'isi'       => $this->request->getPost('isi'),
            'penulis'   => $this->request->getPost('penulis'),
            'status'    => 'aktif'
        ]);

        return redirect()->back()->with('success', 'Topik diskusi berhasil dikirim!');
    }

    public function submit_reply($id_diskusi)
    {
        $this->jawabanModel->save([
            'id_diskusi' => $id_diskusi,
            'isi'        => $this->request->getPost('isi'),
            'penulis'    => $this->request->getPost('penulis')
        ]);

        return redirect()->back()->with('success', 'Jawaban berhasil dikirim!');
    }
}
