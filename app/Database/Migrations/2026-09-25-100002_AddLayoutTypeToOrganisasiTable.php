<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLayoutTypeToOrganisasiTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('organisasi', [
            'layout_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'main',
                'after'      => 'level',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('organisasi', 'layout_type');
    }
}
