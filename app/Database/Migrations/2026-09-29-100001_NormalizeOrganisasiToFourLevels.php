<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use RuntimeException;
use Throwable;

class NormalizeOrganisasiToFourLevels extends Migration
{
    public function up()
    {
        /** @var \CodeIgniter\Database\BaseConnection $db */
        $db = $this->db;
        $db->transBegin();

        try {
            $positions = $db->table('organisasi')
                ->select('id, parent_id, level, urutan')
                ->orderBy('urutan', 'ASC')
                ->orderBy('id', 'ASC')
                ->get()
                ->getResultArray();
            $positionsById = [];
            foreach ($positions as $position) {
                $positionsById[(int) $position['id']] = $position;
            }

            $groupsById = [];
            foreach ($positions as $position) {
                if ((int) $position['level'] !== 4) {
                    continue;
                }

                $parent = $positionsById[(int) $position['parent_id']] ?? null;
                if ($parent === null || (int) $parent['level'] !== 3) {
                    throw new RuntimeException('Migrasi struktur dihentikan: setiap tim Level 4 harus berada di bawah Level 3.');
                }

                $groupsById[(int) $position['id']] = $position;
            }

            $staffByGroup = [];
            foreach ($positions as $position) {
                if ((int) $position['level'] !== 5) {
                    continue;
                }

                $groupId = (int) $position['parent_id'];
                if (! isset($groupsById[$groupId])) {
                    throw new RuntimeException('Migrasi struktur dihentikan: setiap staf Level 5 harus memiliki tim Level 4.');
                }

                $staffByGroup[$groupId][] = $position;
            }

            foreach ($positions as $position) {
                if ((int) $position['level'] > 5 || (int) $position['level'] < 1) {
                    throw new RuntimeException('Migrasi struktur dihentikan: ditemukan level jabatan di luar rentang 1 sampai 5.');
                }
                if ((int) $position['level'] === 4) {
                    foreach ($positions as $child) {
                        if ((int) ($child['parent_id'] ?? 0) === (int) $position['id'] && (int) $child['level'] !== 5) {
                            throw new RuntimeException('Migrasi struktur dihentikan: tim Level 4 memiliki bawahan selain staf Level 5.');
                        }
                    }
                }
            }

            $ordersByParent = [];
            foreach ($groupsById as $groupId => $group) {
                $newParentId = (int) $group['parent_id'];
                foreach ($staffByGroup[$groupId] ?? [] as $staff) {
                    $ordersByParent[$newParentId] = ($ordersByParent[$newParentId] ?? 0) + 1;
                    $updated = $db->table('organisasi')
                        ->where('id', (int) $staff['id'])
                        ->update([
                            'parent_id' => $newParentId,
                            'level' => 4,
                            'urutan' => $ordersByParent[$newParentId],
                        ]);

                    if (! $updated) {
                        throw new RuntimeException('Migrasi struktur dihentikan: data staf gagal dipindahkan.');
                    }
                }
            }

            if ($groupsById !== []) {
                $deleted = $db->table('organisasi')
                    ->whereIn('id', array_keys($groupsById))
                    ->delete();

                if (! $deleted) {
                    throw new RuntimeException('Migrasi struktur dihentikan: tim Level 4 lama gagal dihapus.');
                }
            }

            if (! $db->transStatus()) {
                throw new RuntimeException('Migrasi struktur gagal dan seluruh perubahan dibatalkan.');
            }

            $db->transCommit();
        } catch (Throwable $exception) {
            $db->transRollback();
            throw $exception;
        }
    }

    public function down()
    {
        throw new RuntimeException('Migrasi data organisasi tidak dapat dibalik otomatis. Pulihkan backup database sebelum migrasi jika perlu rollback.');
    }
}