<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use RuntimeException;
use Throwable;

class CreateOrganisasiGroupsAndMembers extends Migration
{
    public function up()
    {
        /** @var \CodeIgniter\Database\BaseConnection $db */
        $db = $this->db;
        $rows = $db->table('organisasi')
            ->select('id, parent_id, level, layout_type, nama, jabatan, foto, urutan, created_by, updated_by, created_at, updated_at')
            ->orderBy('urutan', 'ASC')
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        $rowsById = [];
        foreach ($rows as $row) {
            $rowsById[(int) $row['id']] = $row;
        }

        $groups = [];
        $memberIds = [];
        foreach ($rows as $row) {
            if ((int) $row['level'] !== 4) {
                continue;
            }

            $parentId = (int) $row['parent_id'];
            if (! isset($rowsById[$parentId]) || (int) $rowsById[$parentId]['level'] !== 3) {
                throw new RuntimeException('Migrasi dihentikan: staf Level 4 harus berada di bawah node Level 3.');
            }

            $jobTitle = (string) $row['jabatan'];
            $groupKey = $parentId . "\0" . $jobTitle;
            $groups[$groupKey] ??= [
                'parent_id' => $parentId,
                'jabatan' => $jobTitle,
                'order_hint' => (int) $row['urutan'],
                'members' => [],
            ];
            $groups[$groupKey]['order_hint'] = min($groups[$groupKey]['order_hint'], (int) $row['urutan']);
            $groups[$groupKey]['members'][] = $row;
            $memberIds[] = (int) $row['id'];
        }

        usort($groups, static fn (array $left, array $right): int => [$left['parent_id'], $left['order_hint']] <=> [$right['parent_id'], $right['order_hint']]);

        if (! $db->fieldExists('node_type', 'organisasi')) {
            $this->forge->addColumn('organisasi', [
                'node_type' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 20,
                    'default'    => 'pejabat',
                    'after'      => 'level',
                ],
            ]);
        }

        $fields = [
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'organisasi_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
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
        ];
        if (! $db->tableExists('organisasi_anggota')) {
            $this->forge->addField($fields);
            $this->forge->addKey('id', true);
            $this->forge->addKey('organisasi_id');
            $this->forge->addForeignKey('organisasi_id', 'organisasi', 'id', 'RESTRICT', 'RESTRICT');
            $this->forge->createTable('organisasi_anggota');
        }

        $db->transBegin();
        try {
            $db->table('organisasi')
                ->where('layout_type', 'side')
                ->update(['node_type' => 'admin']);

            $groupOrders = [];
            foreach ($groups as $group) {
                $parentId = $group['parent_id'];
                $groupOrders[$parentId] = ($groupOrders[$parentId] ?? 0) + 1;
                $timestamp = $group['members'][0]['created_at'] ?? date('Y-m-d H:i:s');
                $inserted = $db->table('organisasi')->insert([
                    'parent_id' => $parentId,
                    'level' => 4,
                    'node_type' => 'tim',
                    'layout_type' => 'main',
                    'nama' => $group['jabatan'],
                    'jabatan' => $group['jabatan'],
                    'foto' => null,
                    'urutan' => $groupOrders[$parentId],
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]);

                if (! $inserted) {
                    throw new RuntimeException('Migrasi dihentikan: node tim gagal dibuat.');
                }

                $groupId = (int) $db->insertID();
                foreach ($group['members'] as $member) {
                    $member['organisasi_id'] = $groupId;
                    unset($member['parent_id'], $member['level'], $member['layout_type']);
                    if (! $db->table('organisasi_anggota')->insert($member)) {
                        throw new RuntimeException('Migrasi dihentikan: anggota gagal dipindahkan ke tabel anggota.');
                    }
                }
            }

            if ($memberIds !== [] && ! $db->table('organisasi')->whereIn('id', $memberIds)->delete()) {
                throw new RuntimeException('Migrasi dihentikan: baris staf lama gagal dibersihkan setelah dipindahkan.');
            }

            if (! $db->transStatus()) {
                throw new RuntimeException('Migrasi gagal dan perubahan data dibatalkan.');
            }

            $db->transCommit();
        } catch (Throwable $exception) {
            $db->transRollback();
            throw $exception;
        }
    }

    public function down()
    {
        throw new RuntimeException('Migrasi tim dan anggota organisasi tidak dapat dibalik otomatis.');
    }
}