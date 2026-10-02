<?= $this->extend('layouts/admin') ?>

<?php
/** @var string $mode */
/** @var array<string, mixed> $position */
/** @var list<array<string, mixed>> $positions */
/** @var string $action */
$isEdit = $mode === 'edit';
$currentNodeType = (string) old('node_type', $position['node_type'] ?? 'pejabat');
$currentParent = (string) old('parent_id', $position['parent_id'] ?? '');
$photoName = $position['foto'] ?? '';
$nodeTypes = $nodeTypes ?? ['pejabat' => 'Pejabat', 'admin' => 'Administrasi', 'tim' => 'Tim'];
$errors = session()->getFlashdata('errors') ?? [];
if (is_string($errors)) {
    $errors = [$errors];
}

/**
 * Helper internal untuk memetakan level/node_type menjadi label kategori yang presisi
 */
if (! function_exists('getLabelNode')) {
    function getLabelNode(string $nodeType, int $level): string {
        if ($nodeType === 'admin') {
            return 'Administrasi';
        }
        if ($nodeType === 'tim') {
            return 'Tim';
        }

        switch ($level) {
            case 1:
                return 'Kepala';
            case 2:
                return 'Penjab';
            case 3:
                return 'Ka Tim';
            default:
                return 'Pejabat';
        }
    }
}
?>

<?= $this->section('title') ?><?= $isEdit ? 'Edit Posisi' : 'Tambah Posisi' ?><?= $this->endSection() ?>
<?= $this->section('page_heading') ?><?= $isEdit ? 'Edit Posisi' : 'Tambah Posisi / Staf' ?><?= $this->endSection() ?>
<?= $this->section('page_description') ?>Perbarui informasi dan hubungan dalam struktur organisasi<?= $this->endSection() ?>

<?= $this->section('styles') ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="mx-auto max-w-4xl space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="font-heading text-xl font-bold text-slate-900"><?= $isEdit ? 'Perbarui data posisi' : 'Data posisi baru' ?></h1>
                <p class="mt-1 text-sm text-slate-500">Tentukan kategori jabatan dan atasan agar susunan organisasi tetap valid.</p>
            </div>
            <a href="<?= base_url('admin/organisasi') ?>" class="shrink-0 rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-white">Kembali</a>
        </div>

        <?php if (! empty($errors)): ?>
            <div role="alert" class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
                <p class="font-semibold">Data belum dapat disimpan.</p>
                <ul class="mt-2 list-inside list-disc space-y-1">
                    <?php foreach ($errors as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= esc($action) ?>" method="post" enctype="multipart/form-data" class="space-y-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-8">
            <?= csrf_field() ?>
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= (int) $position['id'] ?>">
            <?php endif; ?>

            <div class="grid gap-5 sm:grid-cols-2">
                <div class="space-y-2">
                    <label for="node_type" class="block text-sm font-semibold text-slate-700">Tipe Node</label>
                    <select id="node_type" name="node_type" required class="h-11 w-full rounded-xl border-slate-300 text-sm focus:border-teal-600 focus:ring-teal-600">
                        <?php foreach ($nodeTypes as $value => $label): ?>
                            <option value="<?= esc($value, 'attr') ?>" <?= $currentNodeType === $value ? 'selected' : '' ?>><?= esc($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div id="name-field" class="space-y-2">
                    <label for="nama" class="block text-sm font-semibold text-slate-700">Nama Pejabat</label>
                    <input id="nama" name="nama" type="text" maxlength="150" value="<?= esc(old('nama', $position['nama'] ?? '')) ?>" class="h-11 w-full rounded-xl border-slate-300 text-sm focus:border-teal-600 focus:ring-teal-600" autocomplete="name">
                </div>
                <div class="space-y-2">
                    <label for="jabatan" class="block text-sm font-semibold text-slate-700">Jabatan / Nama tim</label>
                    <input id="jabatan" name="jabatan" type="text" maxlength="150" required value="<?= esc(old('jabatan', $position['jabatan'] ?? '')) ?>" class="h-11 w-full rounded-xl border-slate-300 text-sm focus:border-teal-600 focus:ring-teal-600">
                </div>
                <div class="space-y-2">
                    <label for="parent_id" class="block text-sm font-semibold text-slate-700">Atasan</label>
                    <select id="parent_id" name="parent_id" data-current-parent="<?= esc($currentParent, 'attr') ?>" data-exclude-id="<?= $isEdit ? (int) $position['id'] : '' ?>" class="h-11 w-full rounded-xl border-slate-300 text-sm focus:border-teal-600 focus:ring-teal-600">
                        <?php foreach ($positions as $option): ?>
                            <?php 
                                $nodeLabel = getLabelNode((string) ($option['node_type'] ?? 'pejabat'), (int) ($option['level'] ?? 1)); 
                            ?>
                            <option value="<?= (int) $option['id'] ?>" data-level="<?= (int) $option['level'] ?>" <?= (string) $option['id'] === $currentParent ? 'selected' : '' ?>>
                                [<?= esc($nodeLabel) ?>] <?= esc($option['nama']) ?><?= ! empty($option['nama']) && ! empty($option['jabatan']) ? ' - ' : '' ?><?= esc($option['jabatan']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="text-xs text-slate-500">Hanya node Pejabat yang dapat dipilih sebagai atasan.</p>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                    <div class="grid h-24 w-24 shrink-0 place-items-center overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">
                        <?php if ($photoName !== ''): ?>
                            <img id="photo-preview" src="<?= base_url('uploads/organisasi/' . rawurlencode($photoName)) ?>" alt="Pratinjau foto saat ini" class="h-full w-full object-cover">
                        <?php else: ?>
                            <div id="photo-placeholder" class="grid h-full w-full place-items-center text-2xl font-bold text-slate-400" aria-label="Belum ada foto">+</div>
                            <img id="photo-preview" alt="Pratinjau foto baru" class="hidden h-full w-full object-cover">
                        <?php endif; ?>
                    </div>
                    <div class="min-w-0 flex-1 space-y-2">
                        <label for="foto" class="block text-sm font-semibold text-slate-700">Foto Profil</label>
                        <input id="foto" name="foto" type="file" accept="image/png,image/jpeg,.png,.jpg,.jpeg" class="block w-full text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-teal-50 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-teal-800 hover:file:bg-teal-100">
                        <p class="text-xs text-slate-500">PNG atau JPG/JPEG, maksimal 4 MB. Foto akan dipotong persegi sebelum disimpan.</p>
                        <?php if ($isEdit && $photoName !== ''): ?>
                            <p class="text-xs text-slate-500">Foto lama tetap digunakan jika tidak memilih pengganti.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                <a href="<?= base_url('admin/organisasi') ?>" class="inline-flex min-h-11 items-center justify-center rounded-xl border border-slate-300 px-5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</a>
                <button type="submit" class="inline-flex min-h-11 items-center justify-center rounded-xl bg-teal-700 px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-600 focus:ring-offset-2">
                    <?= $isEdit ? 'Simpan Perubahan' : 'Simpan Posisi' ?>
                </button>
            </div>
        </form>
    </div>

    <div id="crop-modal" class="fixed inset-0 z-[70] hidden items-center justify-center bg-slate-950/70 p-4" role="dialog" aria-modal="true" aria-labelledby="crop-title">
        <div class="w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                <h2 id="crop-title" class="font-heading text-base font-bold text-slate-900">Potong Foto Profil</h2>
                <button id="crop-cancel-top" type="button" class="grid h-9 w-9 place-items-center rounded-lg text-xl text-slate-500 hover:bg-slate-100" aria-label="Tutup">&times;</button>
            </div>
            <div class="max-h-[65vh] overflow-hidden bg-slate-950 p-3">
                <img id="crop-image" alt="Foto yang akan dipotong" class="block max-h-[60vh] max-w-full">
            </div>
            <div class="flex justify-end gap-3 px-5 py-4">
                <button id="crop-cancel" type="button" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</button>
                <button id="crop-apply" type="button" class="rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-teal-800">Gunakan Foto</button>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
    <script>
        (() => {
            const nodeTypeSelect = document.getElementById('node_type');
            const nameField = document.getElementById('name-field');
            const nameInput = document.getElementById('nama');
            const parentSelect = document.getElementById('parent_id');
            const photoInput = document.getElementById('foto');
            const preview = document.getElementById('photo-preview');
            const placeholder = document.getElementById('photo-placeholder');
            const modal = document.getElementById('crop-modal');
            const cropImage = document.getElementById('crop-image');
            let cropper = null;
            let sourceUrl = null;
            let previewUrl = null;

            const updateNodeFields = () => {
                const isGroup = nodeTypeSelect.value === 'tim';
                nameField.hidden = isGroup;
                nameInput.required = !isGroup;
                parentSelect.required = nodeTypeSelect.value !== 'pejabat' || parentSelect.value !== '';
            };

            const closeCropModal = () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
                if (sourceUrl) {
                    URL.revokeObjectURL(sourceUrl);
                    sourceUrl = null;
                }
            };

            nodeTypeSelect.addEventListener('change', updateNodeFields);
            parentSelect.addEventListener('change', updateNodeFields);
            updateNodeFields();

            photoInput.addEventListener('change', () => {
                const file = photoInput.files[0];
                if (!file) return;
                photoInput.value = '';
                sourceUrl = URL.createObjectURL(file);
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                cropImage.onload = () => {
                    if (cropper) cropper.destroy();
                    cropper = new Cropper(cropImage, {
                        aspectRatio: 1,
                        viewMode: 1,
                        autoCropArea: 1,
                        responsive: true,
                        background: false,
                    });
                };
                cropImage.src = sourceUrl;
            });

            document.getElementById('crop-apply').addEventListener('click', () => {
                if (!cropper) return;
                cropper.getCroppedCanvas({
                    width: 512,
                    height: 512,
                    imageSmoothingQuality: 'high',
                }).toBlob((blob) => {
                    if (!blob) return;
                    const croppedFile = new File([blob], 'profil.jpg', { type: 'image/jpeg' });
                    const transfer = new DataTransfer();
                    transfer.items.add(croppedFile);
                    photoInput.files = transfer.files;
                    if (previewUrl) URL.revokeObjectURL(previewUrl);
                    previewUrl = URL.createObjectURL(blob);
                    preview.src = previewUrl;
                    preview.classList.remove('hidden');
                    if (placeholder) placeholder.classList.add('hidden');
                    closeCropModal();
                }, 'image/jpeg', 0.86);
            });

            document.getElementById('crop-cancel').addEventListener('click', closeCropModal);
            document.getElementById('crop-cancel-top').addEventListener('click', closeCropModal);
            modal.addEventListener('click', (event) => {
                if (event.target === modal) closeCropModal();
            });
        })();
    </script>
<?= $this->endSection() ?>