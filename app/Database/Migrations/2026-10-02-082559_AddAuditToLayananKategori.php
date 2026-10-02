<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAuditToLayananKategori extends Migration
{
    public function up(): void
    {
        $fields = [];

        if (! $this->db->fieldExists('created_by', 'layanan_kategori')) {
            $fields['created_by'] = ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => true, 'after' => 'urutan'];
        }
        if (! $this->db->fieldExists('updated_by', 'layanan_kategori')) {
            $fields['updated_by'] = ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => true, 'after' => 'created_by'];
        }

        if ($fields !== []) {
            $this->forge->addColumn('layanan_kategori', $fields);
        }
    }

    public function down(): void
    {
        $this->forge->dropColumn('layanan_kategori', ['created_by', 'updated_by']);
    }
}