<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrganisasiTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'parent_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'jabatan' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'foto' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'urutan' => [
                'type'       => 'INT',
                'constraint' => 5,
                'default'    => 0,
            ],
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],
            'updated_by' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('parent_id');
        $this->forge->addForeignKey('parent_id', 'organisasi', 'id', 'SET NULL', 'SET NULL');
        $this->forge->createTable('organisasi', true);
    }

    public function down()
    {
        $this->forge->dropTable('organisasi', true);
    }
}
