<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKegiatanTables extends Migration
{
    public function up()
    {
        // Tabel kegiatan (album)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kategori_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],
            'judul' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
            ],
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => 220,
            ],
            'tanggal' => [
                'type' => 'DATE',
            ],
            'cover_foto' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->addUniqueKey('slug');
        $this->forge->addKey('kategori_id');
        $this->forge->addForeignKey('kategori_id', 'kategori', 'id', 'SET NULL', 'SET NULL');
        $this->forge->createTable('kegiatan', true);

        // Tabel kegiatan_foto (foto-foto dalam album)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kegiatan_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
            'foto' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'caption' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'urutan' => [
                'type'       => 'INT',
                'constraint' => 5,
                'default'    => 0,
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
        $this->forge->addKey('kegiatan_id');
        $this->forge->addForeignKey('kegiatan_id', 'kegiatan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('kegiatan_foto', true);
    }

    public function down()
    {
        $this->forge->dropTable('kegiatan_foto', true);
        $this->forge->dropTable('kegiatan', true);
    }
}
