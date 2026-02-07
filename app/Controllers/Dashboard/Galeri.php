<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Models\GaleriModel;

class Galeri extends BaseController
{
    protected $galeriModel;
    protected $logModel;

    public function __construct()
    {
        $this->galeriModel = new GaleriModel();
        $this->logModel = new \App\Models\ActivityLogModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen Galeri Multimedia',
            'items' => $this->galeriModel->orderBy('created_at', 'DESC')->findAll(),
        ];
        return view('dashboard/galeri/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Tambah Item Galeri'];
        return view('dashboard/galeri/create', $data);
    }

    public function store()
    {
        $kategori = $this->request->getPost('kategori');

        // Validation Rules
        $rules = [
            'judul'    => 'required',
            'kategori' => 'required',
        ];

        if ($kategori !== 'upload_audio') {
            $rules['link_media'] = 'required';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal. Pastikan semua field terisi.');
        }

        // 1. Create Main Gallery Record
        $this->galeriModel->save([
            'id_gereja'  => 1, // Default church ID
            'judul'      => $this->request->getPost('judul'),
            'kategori'   => $kategori,
            'link_media' => $kategori === 'upload_audio' ? 'local_playlist' : $this->request->getPost('link_media'), // Marker for local
            'keterangan' => $this->request->getPost('keterangan'),
            'status'     => $this->request->getPost('status') ?? 'aktif',
        ]);
        
        $newId = $this->galeriModel->insertID();
        $this->logModel->add('CREATE', 'galeri', $newId, null, $this->request->getPost());

        // 2. Handle File Uploads (If Audio)
        if ($kategori === 'upload_audio') {
            $files = $this->request->getFileMultiple('audio_files');
            $pathsJson = $this->request->getPost('folder_paths');
            $paths = json_decode($pathsJson, true); // Array of relative paths matching files index

            $galeriItemsModel = new \App\Models\GaleriItemsModel(); // Init Model locally or via property

            if ($files) {
                foreach ($files as $index => $file) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        // Determine Sub-Folder (Subtitle) from Path
                        // Path fmt: "FolderName/FileName.mp3" or just "FileName.mp3"
                        $relPath = isset($paths[$index]) ? $paths[$index] : $file->getClientName();
                        $parts = explode('/', $relPath);
                        
                        // If path has folder, use it. Else use "Single Track" or empty
                        $subTitle = (count($parts) > 1) ? $parts[0] : 'General'; 
                        
                        // Server Storage Path: public/uploads/audio/{id_galeri}/{subTitle}/
                        $savePath = 'uploads/audio/' . $newId . '/' . $this->clean_path($subTitle); 
                        $fileName = $file->getClientName();
                        
                        // Move File
                        // Note: move() creates dirs automatically
                        $file->move(FCPATH . $savePath, $fileName);
                        
                        // Save to DB Items
                        $galeriItemsModel->insert([
                            'id_galeri' => $newId,
                            'judul'     => $subTitle, // Used as Group Header
                            'file_name' => $fileName,
                            'file_path' => $savePath . '/' . $fileName,
                            'sort_order'=> $index
                        ]);
                    }
                }
            }
        }

        return redirect()->to('/dashboard/galeri')->with('success', 'Item galeri berhasil ditambahkan.');
    }
    
    // Helper to sanitize folder names
    protected function clean_path($string) {
       return preg_replace('/[^A-Za-z0-9_\-]/', '_', $string);
    }

    public function edit($id)
    {
        $item = $this->galeriModel->find($id);
        if (!$item) {
            return redirect()->to('/dashboard/galeri')->with('error', 'Item tidak ditemukan.');
        }
        
        // Fetch Audio Items if category is upload_audio
        $audioItems = [];
        if($item['kategori'] == 'upload_audio') {
            $galeriItemsModel = new \App\Models\GaleriItemsModel();
            $audioItems = $galeriItemsModel->where('id_galeri', $id)->orderBy('sort_order', 'ASC')->findAll();
        }

        $data = [
            'title' => 'Edit Galeri',
            'item'  => $item,
            'audioItems' => $audioItems
        ];
        return view('dashboard/galeri/edit', $data);
    }

    public function update($id)
    {
        // Validation - skip link_media if upload_audio
        $kategori = $this->request->getPost('kategori');
        $rules = [
             'judul'    => 'required',
             'kategori' => 'required',
        ];
        if ($kategori !== 'upload_audio') {
            $rules['link_media'] = 'required';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal.');
        }

        $updateData = [
            'judul'      => $this->request->getPost('judul'),
            'kategori'   => $this->request->getPost('kategori'),
            'link_media' => $kategori === 'upload_audio' ? 'local_playlist' : $this->request->getPost('link_media'),
            'keterangan' => $this->request->getPost('keterangan'),
            'status'     => $this->request->getPost('status'),
        ];
        
        $oldData = $this->galeriModel->asArray()->find($id);
        $this->galeriModel->update($id, $updateData);
        
        $this->logModel->add('UPDATE', 'galeri', $id, $oldData, $updateData);

        // Handle Additional File Uploads
        if ($kategori === 'upload_audio') {
            $files = $this->request->getFileMultiple('audio_files');
            $pathsJson = $this->request->getPost('folder_paths');
            $paths = json_decode($pathsJson, true); 

            $galeriItemsModel = new \App\Models\GaleriItemsModel();

            // Get current max sort order
            $lastItem = $galeriItemsModel->where('id_galeri', $id)->orderBy('sort_order', 'DESC')->first();
            $startIndex = $lastItem ? $lastItem['sort_order'] + 1 : 0;

            if ($files) {
                foreach ($files as $index => $file) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $relPath = isset($paths[$index]) ? $paths[$index] : $file->getClientName();
                        $parts = explode('/', $relPath);
                        $subTitle = (count($parts) > 1) ? $parts[0] : 'General'; 
                        
                        $savePath = 'uploads/audio/' . $id . '/' . $this->clean_path($subTitle); 
                        $fileName = $file->getClientName();
                        
                        $file->move(FCPATH . $savePath, $fileName);
                        
                        $galeriItemsModel->insert([
                            'id_galeri' => $id,
                            'judul'     => $subTitle,
                            'file_name' => $fileName,
                            'file_path' => $savePath . '/' . $fileName,
                            'sort_order'=> $startIndex + $index
                        ]);
                    }
                }
            }
        }

        return redirect()->to('/dashboard/galeri')->with('success', 'Item galeri berhasil diperbarui.');
    }

    public function delete($id)
    {
        $oldData = $this->galeriModel->asArray()->find($id);
        $this->galeriModel->delete($id);
        $this->logModel->add('DELETE', 'galeri', $id, $oldData, null);
        return redirect()->to('/dashboard/galeri')->with('success', 'Item galeri berhasil dihapus.');
    }
}
