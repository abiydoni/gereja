<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSectionOrganisasiConfig extends Migration
{
    public function up()
    {
        $this->db->table('konfigurasi_frontend')->insert([
            'group'      => 'section',
            'slug'       => 'section_organisasi',
            'label'      => 'Struktur Organisasi (Majelis)',
            'status'     => 'aktif',
            'urutan'     => 10,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function down()
    {
        $this->db->table('konfigurasi_frontend')->where('slug', 'section_organisasi')->delete();
    }
}
