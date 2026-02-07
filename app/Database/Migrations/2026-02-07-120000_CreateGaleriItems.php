<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGaleriItems extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_item' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_galeri' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true, // Matches id_galeri in galeri table if applicable
            ],
            'judul' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'file_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'file_path' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'sort_order' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
        ]);
        $this->forge->addKey('id_item', true);
        $this->forge->addKey('id_galeri');
        $this->forge->createTable('galeri_items');
    }

    public function down()
    {
        $this->forge->dropTable('galeri_items');
    }
}
