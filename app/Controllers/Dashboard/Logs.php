<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;

class Logs extends BaseController
{
    public function index()
    {
        // Security Check: Only Superadmin
        if (session()->get('role') !== 'superadmin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak.');
        }

        $logModel = new \App\Models\ActivityLogModel();
        
        $search = $this->request->getGet('search');
        if ($search) {
            $logModel->groupStart()
                     ->like('username', $search)
                     ->orLike('action', $search)
                     ->orLike('table_name', $search)
                     ->orLike('record_id', $search)
                     ->orLike('new_values', $search)
                     ->groupEnd();
        }
        
        $data = [
            'title' => 'Log Aktivitas Sistem',
            'logs'  => $logModel->orderBy('created_at', 'DESC')->paginate(20, 'logs'),
            'pager' => $logModel->pager,
            'search'=> $search
        ];

        return view('dashboard/logs/index', $data);
    }
}
