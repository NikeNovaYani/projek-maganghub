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
        'node_type',
        'layout_type',
        'nama',
        'jabatan',
        'foto',
        'urutan',
        'created_by',
        'updated_by',
    ];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $validationRules = [
        'parent_id' => 'permit_empty|is_natural_no_zero',
        'level' => 'required|is_natural_no_zero|less_than_equal_to[4]',
        'node_type' => 'required|in_list[kepala,penjab,katim,admin,tim]',
        'layout_type' => 'required|in_list[main,side]',
        'nama' => 'required|max_length[150]',
        'jabatan' => 'required|max_length[150]',
        'foto' => 'permit_empty|max_length[255]',
        'urutan' => 'required|is_natural',
    ];
    protected $validationMessages = [
        'parent_id' => [
            'is_natural_no_zero' => 'Atasan yang dipilih tidak valid.',
        ],
        'level' => [
            'required' => 'Kategori jabatan wajib dipilih.',
            'less_than_equal_to' => 'Kategori jabatan tidak valid.',
        ],
        'layout_type' => [
            'in_list' => 'Tipe tata letak tidak valid.',
        ],
        'nama' => [
            'required' => 'Nama wajib diisi.',
            'max_length' => 'Nama maksimal 150 karakter.',
        ],
        'jabatan' => [
            'required' => 'Jabatan wajib diisi.',
            'max_length' => 'Jabatan maksimal 150 karakter.',
        ],
    ];

    public function getParentOptions(int $childLevel, ?int $excludeId = null): array
    {
        $builder = $this->select('id, level, nama, jabatan')
            ->where('level', $childLevel - 1)
            ->where('node_type', 'pejabat')
            ->orderBy('urutan', 'ASC')
            ->orderBy('nama', 'ASC');

        if ($excludeId !== null) {
            $builder->where('id !=', $excludeId);
        }

        return $builder->findAll();
    }

    public function hasChildren(int $id): bool
    {
        return $this->where('parent_id', $id)->countAllResults() > 0;
    }

    public function getMainTree(): array
    {
        $rows = $this->db->table($this->table . ' o')
            ->select('o.id, o.parent_id, o.level, o.node_type, o.layout_type, o.nama, o.jabatan, o.foto, o.urutan, (SELECT COUNT(*) FROM organisasi_anggota a WHERE a.organisasi_id = o.id) AS total_anggota', false)
            ->whereIn('o.level', [1, 2, 3, 4])
            ->orderBy('o.urutan', 'ASC')
            ->orderBy('o.id', 'ASC')
            ->get()
            ->getResultArray();

        $childrenByParent = [];
        foreach ($rows as $row) {
            $row['total_anggota'] = (int) $row['total_anggota'];
            $key = $row['parent_id'] === null ? 'root' : (string) $row['parent_id'];
            $childrenByParent[$key][] = $row;
        }

        return $this->buildMainTree($childrenByParent, 'root');
    }

    public function getProfile(int $id): ?array
    {
        return $this->select('id, parent_id, level, node_type, layout_type, nama, jabatan, foto, urutan')
            ->where('id', $id)
            ->first();
    }

    public function getGroupMembers(int $groupId): array
    {
        return (new OrganisasiAnggotaModel())
            ->select('id, organisasi_id, nama, jabatan, foto, urutan')
            ->where('organisasi_id', $groupId)
            ->orderBy('urutan', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    public function getTree(?int $parentId = null): array
    {
        $rows = $this->db->table($this->table . ' o')
            ->select('o.id, o.parent_id, o.level, o.node_type, o.layout_type, o.nama, o.jabatan, o.foto, o.urutan, (SELECT COUNT(*) FROM organisasi_anggota a WHERE a.organisasi_id = o.id) AS total_anggota', false)
            ->orderBy('o.urutan', 'ASC')
            ->orderBy('o.id', 'ASC')
            ->get()
            ->getResultArray();

        $childrenByParent = [];
        foreach ($rows as $row) {
            $row['total_anggota'] = (int) $row['total_anggota'];
            $key = $row['parent_id'] === null ? 'root' : (string) $row['parent_id'];
            $childrenByParent[$key][] = $row;
        }

        $rootKey = $parentId === null ? 'root' : (string) $parentId;

        return $this->buildTree($childrenByParent, $rootKey);
    }

    public function isInSubtree(int $candidateId, int $rootId): bool
    {
        $childrenByParent = [];
        foreach ($this->select('id, parent_id')->findAll() as $node) {
            $childrenByParent[(int) ($node['parent_id'] ?? 0)][] = (int) $node['id'];
        }

        $pending = [$rootId];
        $visited = [];
        while ($pending !== []) {
            $currentId = array_pop($pending);
            if (isset($visited[$currentId])) {
                continue;
            }
            $visited[$currentId] = true;

            foreach ($childrenByParent[$currentId] ?? [] as $childId) {
                if ($childId === $candidateId) {
                    return true;
                }
                $pending[] = $childId;
            }
        }

        return false;
    }

    public function getGroupMemberCount(int $groupId): int
    {
        return (new OrganisasiAnggotaModel())
            ->where('organisasi_id', $groupId)
            ->countAllResults();
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
