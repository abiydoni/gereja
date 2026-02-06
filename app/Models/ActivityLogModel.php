<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityLogModel extends Model
{
    protected $table            = 'activity_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'user_id', 
        'username', 
        'table_name', 
        'record_id', 
        'action', 
        'old_values', 
        'new_values', 
        'ip_address', 
        'user_agent'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // No update field
    protected $deletedField  = '';

    /**
     * Helper function to save log easily
     * 
     * @param string $action 'CREATE', 'UPDATE', 'DELETE', 'LOGIN', etc
     * @param string $table Target table name
     * @param string|int $recordId Target record ID
     * @param array|null $oldData Data before change
     * @param array|null $newData Data after change
     */
    public function add($action, $table, $recordId, $oldData = null, $newData = null)
    {
        $request = \Config\Services::request();
        $agent   = $request->getUserAgent();

        $data = [
            'user_id'    => session()->get('id_user') ?? null,
            'username'   => session()->get('username') ?? 'System/Guest',
            'table_name' => $table,
            'record_id'  => (string) $recordId,
            'action'     => strtoupper($action),
            'old_values' => $oldData ? json_encode($oldData) : null,
            'new_values' => $newData ? json_encode($newData) : null,
            'ip_address' => $request->getIPAddress(),
            'user_agent' => (string) $agent,
        ];

        return $this->insert($data);
    }
}
