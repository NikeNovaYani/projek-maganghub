<?= $this->extend('layouts/admin') ?>

<?php
/** @var string $mode */
/** @var array<string, mixed> $group */
/** @var array<string, mixed> $member */
/** @var string $action */
$isEdit = $mode === 'edit';
/** @var array<int, string>|string $errors */
$errors = session()->getFlashdata('errors') ?? [];
if (is_string($errors)) {
    $errors = [$errors];
}
?>

<?= $this->section('title') ?><?= $isEdit ? 'Edit Anggota' : 'Tambah Anggota' ?><?= $this->endSection() ?>
<?= $this->section('page_heading') ?><?= $isEdit ? 'Edit Anggota tim' : 'Tambah Anggota tim' ?><?= $this->endSection() ?>
<?= $this->section('page_description') ?>tim: <?= esc($group['jabatan']) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="mx-auto max-w-2xl space-y-5">
        <?php if ($errors !== []): ?>
            <div role="alert" class="rounded-lg border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
                <?php foreach ($errors as $error): ?><p><?= esc($error) ?></p><?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form action="<?= esc($action) ?>" method="post" enctype="multipart/form-data" class="space-y-5 rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
            <?= csrf_field() ?>
            <div class="space-y-2">
                <label for="nama" class="block text-sm font-semibold text-slate-700">Nama anggota</label>
                <input id="nama" name="nama" required maxlength="150" value="<?= esc(old('nama', $member['nama'] ?? '')) ?>" class="h-11 w-full rounded-lg border-slate-300 text-sm focus:border-teal-600 focus:ring-teal-600">
            </div>
            <div class="space-y-2">
                <label for="jabatan" class="block text-sm font-semibold text-slate-700">Jabatan</label>
                <input id="jabatan" name="jabatan" required maxlength="150" value="<?= esc(old('jabatan', $member['jabatan'] ?? $group['jabatan'])) ?>" class="h-11 w-full rounded-lg border-slate-300 text-sm focus:border-teal-600 focus:ring-teal-600">
            </div>
            <div class="space-y-2">
                <label for="foto" class="block text-sm font-semibold text-slate-700">Foto (opsional)</label>
                <input id="foto" name="foto" type="file" accept="image/png,image/jpeg,.png,.jpg,.jpeg" class="block w-full text-sm text-slate-600">
                <p class="text-xs text-slate-500">PNG/JPG/JPEG, maksimal 4 MB.</p>
            </div>
            <div class="flex justify-end gap-3 border-t border-slate-100 pt-5">
                <a href="<?= base_url('admin/organisasi') ?>" class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700">Batal</a>
                <button type="submit" class="rounded-lg bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-teal-800"><?= $isEdit ? 'Simpan Anggota' : 'Tambah Anggota' ?></button>
            </div>
        </form>
    </div>
<?= $this->endSection() ?>
