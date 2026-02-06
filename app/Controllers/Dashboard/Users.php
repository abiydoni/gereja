<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Models\UsersModel;

// Create UsersModel if not exists
class Users extends BaseController
{
    protected $usersModel;

    public function __construct()
    {
        // Using query builder or model. Let's assume Users table is simple.
        // We'll create a simple model inline or check if we can reuse Auth logic.
        // For management, standard CRUD is best.
        $this->usersModel = new \App\Models\UsersModel();
    }

    public function index()
    {
        // Filter: Hide superadmin if current user is not superadmin
        if (session()->get('role') !== 'superadmin') {
            $this->usersModel->where('role !=', 'superadmin');
        }

        $data = [
            'title' => 'Manajemen User',
            'users' => $this->usersModel->findAll(),
        ];
        return view('dashboard/users/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Tambah User'];
        return view('dashboard/users/create', $data);
    }

    public function store()
    {

        if (!$this->validate([
            'username' => 'required|min_length[5]|is_unique[users.username]',
            'password' => 'required|min_length[6]',
            'role'     => 'required',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal. Username mungkin sudah dipakai atau password terlalu pendek.');
        }

        // Security: Prevent privilege escalation
        if ($this->request->getPost('role') == 'superadmin' && session()->get('role') !== 'superadmin') {
             return redirect()->back()->withInput()->with('error', 'Anda tidak memiliki hak akses untuk membuat Super Admin.');
        }

        $this->usersModel->save([

            'username'  => $this->request->getPost('username'),
            'password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'      => $this->request->getPost('role'),
            'status'    => $this->request->getPost('status') ?? 'aktif',
        ]);

        return redirect()->to('/dashboard/users')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = $this->usersModel->find($id);
        if (!$user) {
            return redirect()->to('/dashboard/users')->with('error', 'Data tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit User',
            'user' => $user,
        ];
        return view('dashboard/users/edit', $data);
    }

    public function update($id)
    {
        // Rules change slightly for update (ignore current user for unique check)
        $rules = [
            'username' => "required|min_length[5]|is_unique[users.username,id_user,$id]",
            'role'     => 'required',
        ];

        // If password is filled, validate it
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal.');
        }

        // Security: Prevent privilege escalation
        if ($this->request->getPost('role') == 'superadmin' && session()->get('role') !== 'superadmin') {
             return redirect()->back()->withInput()->with('error', 'Anda tidak memiliki hak akses untuk menetapkan role Super Admin.');
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'role'     => $this->request->getPost('role'),
        ];

        if ($this->request->getPost('password')) {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        $data['status'] = $this->request->getPost('status');

        $this->usersModel->update($id, $data);

        return redirect()->to('/dashboard/users')->with('success', 'User berhasil diperbarui.');
    }

    public function delete($id)
    {
        // Prevent deleting self ideally, but for now simple delete
        if ($id == session()->get('id_user')) {
             return redirect()->to('/dashboard/users')->with('error', 'Tidak dapat menghapus akun sendiri sedang login.');
        }

        $this->usersModel->delete($id);
        return redirect()->to('/dashboard/users')->with('success', 'User berhasil dihapus.');
    }
}
