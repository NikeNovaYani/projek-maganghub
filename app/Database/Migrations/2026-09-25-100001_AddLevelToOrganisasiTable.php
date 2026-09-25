<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLevelToOrganisasiTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('organisasi', [
            'level' => [
                'type'       => 'TINYINT',
                'constraint' => 3,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'parent_id',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('organisasi', 'level');
    }
}
