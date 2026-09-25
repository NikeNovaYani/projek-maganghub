<?php

namespace App\Models;

use CodeIgniter\Model;

class OrganisasiModel extends Model
{
    protected $table = 'organisasi';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'parent_id',
        'level',
        'layout_type',
        'nama',
        'jabatan',
        'foto',
        'urutan',
        'created_by',
        'updated_by',
    ];

    public function getMainTree(): array
    {
        $rows = $this->db->table($this->table . ' o')
            ->select('o.id, o.parent_id, o.level, o.layout_type, o.nama, o.jabatan, o.foto, o.urutan, CASE WHEN o.level = 1 THEN (SELECT COUNT(*) FROM ' . $this->table . ' total_staff WHERE total_staff.level = 5) WHEN o.level = 4 THEN COUNT(staff.id) ELSE 0 END AS total_staf', false)
            ->join($this->table . ' staff', 'staff.parent_id = o.id AND staff.level = 5', 'left', false)
            ->whereIn('o.level', [1, 2, 3, 4])
            ->groupBy('o.id, o.parent_id, o.level, o.layout_type, o.nama, o.jabatan, o.foto, o.urutan')
            ->orderBy('o.urutan', 'ASC')
            ->orderBy('o.id', 'ASC')
            ->get()
            ->getResultArray();

        $childrenByParent = [];
        foreach ($rows as $row) {
            $row['total_staf'] = (int) $row['total_staf'];
            $key = $row['parent_id'] === null ? 'root' : (string) $row['parent_id'];
            $childrenByParent[$key][] = $row;
        }

        return $this->buildMainTree($childrenByParent, 'root');
    }

    public function getProfile(int $id): ?array
    {
        return $this->select('id, parent_id, level, layout_type, nama, jabatan, foto, urutan')
            ->where('id', $id)
            ->first();
    }

    public function getLevelFiveStaff(int $parentId): array
    {
        return $this->select('id, parent_id, level, nama, jabatan, foto, urutan')
            ->where('parent_id', $parentId)
            ->where('level', 5)
            ->orderBy('urutan', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    public function getTree(?int $parentId = null): array
    {
        $rows = $this->orderBy('urutan', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();

        $childrenByParent = [];
        foreach ($rows as $row) {
            $key = $row['parent_id'] === null ? 'root' : (string) $row['parent_id'];
            $childrenByParent[$key][] = $row;
        }

        $rootKey = $parentId === null ? 'root' : (string) $parentId;

        return $this->buildTree($childrenByParent, $rootKey);
    }

    public function getDirectStaff(int $parentId): array
    {
        return $this->select('id, parent_id, level, nama, jabatan, foto, urutan')
            ->where('parent_id', $parentId)
            ->orderBy('urutan', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    public function findWithChildCount(int $id): ?array
    {
        $node = $this->find($id);
        if ($node === null) {
            return null;
        }

        $node['child_count'] = $this->where('parent_id', $id)->countAllResults();

        return $node;
    }

    private function buildTree(array $childrenByParent, string $parentKey): array
    {
        $tree = [];

        foreach ($childrenByParent[$parentKey] ?? [] as $node) {
            $node['child_count'] = count($childrenByParent[(string) $node['id']] ?? []);
            $node['children'] = $this->buildTree($childrenByParent, (string) $node['id']);
            $tree[] = $node;
        }

        return $tree;
    }

    private function buildMainTree(array $childrenByParent, string $parentKey): array
    {
        $tree = [];

        foreach ($childrenByParent[$parentKey] ?? [] as $node) {
            $node['children'] = $this->buildMainTree($childrenByParent, (string) $node['id']);
            $tree[] = $node;
        }

        return $tree;
    }
}
