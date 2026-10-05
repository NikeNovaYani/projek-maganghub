<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Layanan IT<?= $this->endSection() ?>
<?= $this->section('page_heading') ?>Layanan IT<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
/** @var list<array<string, mixed>> $kategori */
$totalItem = array_sum(array_map(static fn(array $k): int => count($k['items']), $kategori));
$normalize = static fn(string $text): string => mb_strtolower(trim($text));
$lastIndex = count($kategori) - 1;

$errors = session()->getFlashdata('errors') ?? [];
if (is_string($errors)) {
    $errors = [$errors];
}
$reopen = session()->getFlashdata('reopen');
$reopenType = is_array($reopen) ? ($reopen['type'] ?? '') : '';
$itemMediaMap = [];
foreach ($kategori as $category) {
    foreach ($category['items'] as $categoryItem) {
        $itemMediaMap[$categoryItem['id']] = $categoryItem['gambar_layanan'] ?? '';
    }
}
$oldForm = [
    'nama'      => (string) old('nama', ''),
    'judul'     => (string) old('judul', ''),
    'deskripsi' => (string) old('deskripsi', ''),
];
$kategoriMap = array_column($kategori, 'nama', 'id');
$jsonFlags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;

$iconButton = 'h-7 w-7 rounded-md border border-slate-200 bg-white text-xs text-slate-600 hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-40';
$textButton = 'h-7 rounded-md border border-slate-200 bg-white px-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-100';
$dangerButton = 'h-7 rounded-md border border-rose-200 bg-white px-2.5 text-xs font-semibold text-rose-700 hover:bg-rose-50';
?>

<style>
    .layanan-modal-backdrop {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        background: rgba(15, 23, 42, .45);
        -webkit-backdrop-filter: blur(6px);
        backdrop-filter: blur(6px);
    }
</style>

<div class="mx-auto max-w-100rem space-y-6"
    x-data="layananPage()"
    data-kategori-store-url="<?= base_url('admin/layanan/kategori/store') ?>"
    data-kategori-update-url="<?= base_url('admin/layanan/kategori/update') ?>"
    data-item-store-url="<?= base_url('admin/layanan/item/store') ?>"
    data-item-update-url="<?= base_url('admin/layanan/item/update') ?>"
    data-media-base-url="<?= base_url('uploads/layanan') ?>/">

    <div class="flex flex-wrap items-center justify-between gap-3">
        <p class="text-sm text-slate-500"><?= count($kategori) ?> kategori &middot; <?= $totalItem ?> layanan</p>
        <button type="button" @click="openKategoriCreate()" class="inline-flex min-h-10 items-center rounded-xl bg-teal-700 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-800">
            + Tambah Kategori
        </button>
    </div>

    <?php if ($kategori === []): ?>
        <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center text-sm text-slate-500">
            Belum ada kategori layanan.
        </div>
    <?php endif; ?>

    <?php foreach ($kategori as $index => $kat): ?>
        <?php
        $titleCounts = array_count_values(array_map(
            static fn(array $item): string => $normalize((string) $item['judul']),
            $kat['items']
        ));
        $itemLastIndex = count($kat['items']) - 1;
        ?>
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <header class="flex flex-wrap items-start justify-between gap-4 border-b border-slate-100 bg-slate-50/70 px-5 py-4 sm:px-6">
                <div class="flex min-w-0 flex-1 items-start gap-3">
                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-teal-700 text-sm font-bold text-white"><?= $index + 1 ?></span>
                    <div class="min-w-0">
                        <h2 class="font-heading text-base font-bold text-slate-900"><?= esc($kat['nama']) ?></h2>
                        <?php if (! empty($kat['deskripsi'])): ?>
                            <p class="mt-2 text-sm leading-relaxed text-slate-600"><?= esc($kat['deskripsi']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="flex shrink-0 flex-wrap items-center gap-1.5">
                    <span class="mr-1 rounded-full bg-teal-50 px-3 py-1 text-xs font-bold text-teal-800"><?= count($kat['items']) ?> layanan</span>

                    <form action="<?= base_url('admin/layanan/kategori/move/' . $kat['id'] . '/up') ?>" method="post" class="inline">
                        <?= csrf_field() ?>
                        <button type="submit" <?= $index === 0 ? 'disabled' : '' ?> title="Naik" aria-label="Naik"
                            class="h-8 w-8 rounded-lg border border-slate-200 bg-white text-sm text-slate-600 hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-40">&uarr;</button>
                    </form>
                    <form action="<?= base_url('admin/layanan/kategori/move/' . $kat['id'] . '/down') ?>" method="post" class="inline">
                        <?= csrf_field() ?>
                        <button type="submit" <?= $index === $lastIndex ? 'disabled' : '' ?> title="Turun" aria-label="Turun"
                            class="h-8 w-8 rounded-lg border border-slate-200 bg-white text-sm text-slate-600 hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-40">&darr;</button>
                    </form>

                    <button type="button"
                        data-kategori-edit-id="<?= (int) $kat['id'] ?>"
                        data-id="<?= (int) $kat['id'] ?>"
                        data-nama="<?= esc($kat['nama'], 'attr') ?>"
                        data-deskripsi="<?= esc((string) ($kat['deskripsi'] ?? ''), 'attr') ?>"
                        data-gambar-kategori="<?= esc((string) ($kat['gambar_kategori'] ?? ''), 'attr') ?>"
                        @click="openKategoriEdit($el)"
                        class="h-8 rounded-lg border border-slate-200 bg-white px-3 text-xs font-semibold text-slate-700 hover:bg-slate-100">Edit</button>

                    <form action="<?= base_url('admin/layanan/kategori/delete/' . $kat['id']) ?>" method="post" class="inline" data-delete-form data-position-name="<?= esc($kat['nama'], 'attr') ?>">
                        <?= csrf_field() ?>
                        <button type="submit" class="h-8 rounded-lg border border-rose-200 bg-white px-3 text-xs font-semibold text-rose-700 hover:bg-rose-50">Hapus</button>
                    </form>
                </div>
            </header>

            <div class="px-5 py-4 sm:px-6">
                <div class="flex items-center justify-between gap-3 pb-2">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Daftar Layanan</p>
                    <button type="button"
                        data-kategori-id="<?= (int) $kat['id'] ?>"
                        @click="openItemCreate($el)"
                        class="inline-flex h-8 items-center rounded-lg bg-teal-50 px-3 text-xs font-bold text-teal-800 hover:bg-teal-100">+ Tambah Layanan</button>
                </div>

                <?php if ($kat['items'] === []): ?>
                    <p class="py-3 text-sm text-slate-500">Belum ada layanan di kategori ini.</p>
                <?php else: ?>
                    <ul class="divide-y divide-slate-100">
                        <?php foreach ($kat['items'] as $itemIndex => $item): ?>
                            <?php $isDuplicate = ($titleCounts[$normalize((string) $item['judul'])] ?? 0) > 1; ?>
                            <li class="flex flex-wrap items-start gap-3 py-3">
                                <span class="w-6 shrink-0 pt-0.5 text-xs font-semibold text-slate-400"><?= $itemIndex + 1 ?></span>

                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-semibold text-slate-800"><?= esc($item['judul']) ?></p>
                                    <?php if (! empty($item['deskripsi'])): ?>
                                        <p class="mt-0.5 text-xs leading-relaxed text-slate-500"><?= esc($item['deskripsi']) ?></p>
                                    <?php endif; ?>
                                </div>

                                <?php if ($isDuplicate): ?>
                                    <span class="shrink-0 rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-bold text-amber-800">Duplikat</span>
                                <?php endif; ?>

                                <div class="flex shrink-0 items-center gap-1">
                                    <form action="<?= base_url('admin/layanan/item/move/' . $item['id'] . '/up') ?>" method="post" class="inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" <?= $itemIndex === 0 ? 'disabled' : '' ?> title="Naik" aria-label="Naik" class="<?= $iconButton ?>">&uarr;</button>
                                    </form>
                                    <form action="<?= base_url('admin/layanan/item/move/' . $item['id'] . '/down') ?>" method="post" class="inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" <?= $itemIndex === $itemLastIndex ? 'disabled' : '' ?> title="Turun" aria-label="Turun" class="<?= $iconButton ?>">&darr;</button>
                                    </form>
                                    <button type="button"
                                        data-id="<?= (int) $item['id'] ?>"
                                        data-kategori-id="<?= (int) $kat['id'] ?>"
                                        data-judul="<?= esc($item['judul'], 'attr') ?>"
                                        data-deskripsi="<?= esc((string) ($item['deskripsi'] ?? ''), 'attr') ?>"
                                        data-gambar-layanan="<?= esc((string) ($item['gambar_layanan'] ?? ''), 'attr') ?>"
                                        @click="openItemEdit($el)"
                                        class="<?= $textButton ?>">Edit</button>
                                    <form action="<?= base_url('admin/layanan/item/delete/' . $item['id']) ?>" method="post" class="inline" data-delete-form data-position-name="<?= esc($item['judul'], 'attr') ?>">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="<?= $dangerButton ?>">Hapus</button>
                                    </form>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </section>
    <?php endforeach; ?>

    <!-- ============ POP UP KATEGORI ============ -->
    <div x-show="modal === 'kategori'" style="display: none;"
        class="layanan-modal-backdrop"
        @keydown.escape.window="closeModal()"
        @click.self="closeModal()"
        role="dialog" aria-modal="true" aria-labelledby="kategori-modal-title">
        <div class="w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                <h2 id="kategori-modal-title" class="font-heading text-base font-bold text-slate-900" x-text="kategori.id ? 'Edit Kategori' : 'Tambah Kategori'"></h2>
                <button type="button" @click="closeModal()" class="grid h-9 w-9 place-items-center rounded-lg text-xl text-slate-500 hover:bg-slate-100" aria-label="Tutup">&times;</button>
            </div>

            <form :action="kategoriAction()" method="post" enctype="multipart/form-data" class="space-y-5 p-5 sm:p-6">
                <?= csrf_field() ?>

                <?php if ($errors !== [] && $reopenType === 'kategori'): ?>
                    <div role="alert" class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
                        <p class="font-semibold">Data belum dapat disimpan.</p>
                        <ul class="mt-2 list-inside list-disc space-y-1">
                            <?php foreach ($errors as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="space-y-2">
                    <label for="kategori_nama" class="block text-sm font-semibold text-slate-700">Nama Kategori</label>
                    <input id="kategori_nama" name="nama" type="text" required maxlength="100" x-model="kategori.nama" x-ref="namaInput"
                        class="h-11 w-full rounded-xl border-slate-300 text-sm focus:border-teal-600 focus:ring-teal-600">
                    <p class="text-xs text-slate-500" x-show="!kategori.id">Slug (alamat singkat) dibuat otomatis dari nama.</p>
                </div>

                <div class="space-y-2">
                    <label for="kategori_deskripsi" class="block text-sm font-semibold text-slate-700">Deskripsi</label>
                    <textarea id="kategori_deskripsi" name="deskripsi" rows="4" maxlength="1000" x-model="kategori.deskripsi"
                        class="w-full rounded-xl border-slate-300 text-sm leading-relaxed focus:border-teal-600 focus:ring-teal-600"></textarea>
                    <p class="text-xs text-slate-500">Tampil di kartu layanan pada website publik. Maksimal 1000 karakter.</p>
                </div>

                <div class="space-y-2">
                    <label for="kategori_gambar" class="block text-sm font-semibold text-slate-700">Gambar Kategori</label>
                    <input id="kategori_gambar" name="gambar_kategori" type="file" accept=".jpg,.jpeg,.png,.webm,image/jpeg,image/png,video/webm" x-ref="kategoriImageInput"
                        class="block w-full rounded-xl border border-slate-300 text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-teal-50 file:px-4 file:py-2 file:font-semibold file:text-teal-800">
                    <template x-if="kategori.gambar_kategori">
                        <img :src="mediaUrl(kategori.gambar_kategori)" alt="Gambar kategori saat ini" class="max-h-36 rounded-xl object-cover">
                    </template>
                    <p class="text-xs text-slate-500">JPG, PNG, atau WebM; maksimal 4 MB. Kosongkan jika tidak ingin mengganti gambar.</p>
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                    <button type="button" @click="closeModal()" class="inline-flex min-h-11 items-center justify-center rounded-xl border border-slate-300 px-5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</button>
                    <button type="submit" class="inline-flex min-h-11 items-center justify-center rounded-xl bg-teal-700 px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-800">
                        <span x-text="kategori.id ? 'Simpan Perubahan' : 'Simpan Kategori'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============ POP UP LAYANAN (ITEM) ============ -->
    <div x-show="modal === 'item'" style="display: none;"
        class="layanan-modal-backdrop"
        @keydown.escape.window="closeModal()"
        @click.self="closeModal()"
        role="dialog" aria-modal="true" aria-labelledby="item-modal-title">
        <div class="w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                <div class="min-w-0">
                    <h2 id="item-modal-title" class="font-heading text-base font-bold text-slate-900" x-text="item.id ? 'Edit Layanan' : 'Tambah Layanan'"></h2>
                    <p class="mt-0.5 truncate text-xs text-slate-500">Kategori: <span class="font-semibold" x-text="item.kategori_nama"></span></p>
                </div>
                <button type="button" @click="closeModal()" class="grid h-9 w-9 shrink-0 place-items-center rounded-lg text-xl text-slate-500 hover:bg-slate-100" aria-label="Tutup">&times;</button>
            </div>

            <form :action="itemAction()" method="post" enctype="multipart/form-data" class="space-y-5 p-5 sm:p-6">
                <?= csrf_field() ?>
                <input type="hidden" name="kategori_id" :value="item.kategori_id">

                <?php if ($errors !== [] && $reopenType === 'item'): ?>
                    <div role="alert" class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
                        <p class="font-semibold">Data belum dapat disimpan.</p>
                        <ul class="mt-2 list-inside list-disc space-y-1">
                            <?php foreach ($errors as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="space-y-2">
                    <label for="item_judul" class="block text-sm font-semibold text-slate-700">Judul Layanan</label>
                    <input id="item_judul" name="judul" type="text" required maxlength="150" x-model="item.judul" x-ref="judulInput"
                        class="h-11 w-full rounded-xl border-slate-300 text-sm focus:border-teal-600 focus:ring-teal-600">
                </div>

                <div class="space-y-2">
                    <label for="item_deskripsi" class="block text-sm font-semibold text-slate-700">Deskripsi (opsional)</label>
                    <textarea id="item_deskripsi" name="deskripsi" rows="4" maxlength="2000" x-model="item.deskripsi"
                        class="w-full rounded-xl border-slate-300 text-sm leading-relaxed focus:border-teal-600 focus:ring-teal-600"></textarea>
                    <p class="text-xs text-slate-500">Maksimal 2000 karakter.</p>
                </div>

                <div class="space-y-2">
                    <label for="item_gambar" class="block text-sm font-semibold text-slate-700">Gambar / Diagram Layanan</label>
                    <input id="item_gambar" name="gambar_layanan" type="file" accept=".jpg,.jpeg,.png,.webm,image/jpeg,image/png,video/webm" x-ref="itemImageInput"
                        class="block w-full rounded-xl border border-slate-300 text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-teal-50 file:px-4 file:py-2 file:font-semibold file:text-teal-800">
                    <template x-if="item.gambar_layanan">
                        <img :src="mediaUrl(item.gambar_layanan)" alt="Gambar layanan saat ini" class="max-h-36 rounded-xl object-cover">
                    </template>
                    <p class="text-xs text-slate-500">JPG, PNG, atau WebM; maksimal 4 MB. Kosongkan jika tidak ingin mengganti gambar.</p>
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                    <button type="button" @click="closeModal()" class="inline-flex min-h-11 items-center justify-center rounded-xl border border-slate-300 px-5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</button>
                    <button type="submit" class="inline-flex min-h-11 items-center justify-center rounded-xl bg-teal-700 px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-800">
                        <span x-text="item.id ? 'Simpan Perubahan' : 'Simpan Layanan'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.layananReopen = <?= json_encode($reopen ?? null, $jsonFlags) ?>;
    window.layananOld = <?= json_encode($oldForm, $jsonFlags) ?>;
    window.layananKategoriMap = <?= json_encode($kategoriMap, $jsonFlags) ?>;
    window.layananKategoriMediaMap = <?= json_encode(array_column($kategori, 'gambar_kategori', 'id'), $jsonFlags) ?>;
    window.layananItemMediaMap = <?= json_encode($itemMediaMap, $jsonFlags) ?>;

    function layananPage() {
        return {
            modal: null,
            kategori: { id: null, nama: '', deskripsi: '', gambar_kategori: '' },
            item: { id: null, kategori_id: null, kategori_nama: '', judul: '', deskripsi: '', gambar_layanan: '' },

            init() {
                // Setelah validasi gagal: buka lagi pop up yang sama dengan isian sebelumnya
                const reopen = window.layananReopen;
                const old = window.layananOld || {};
                if (!reopen) return;

                if (reopen.type === 'kategori') {
                    this.kategori = {
                        id: reopen.id,
                        nama: old.nama || '',
                        deskripsi: old.deskripsi || '',
                        gambar_kategori: window.layananKategoriMediaMap[reopen.id] || '',
                    };
                    this.modal = 'kategori';
                } else if (reopen.type === 'item') {
                    this.item = {
                        id: reopen.id,
                        kategori_id: reopen.kategori_id,
                        kategori_nama: window.layananKategoriMap[reopen.kategori_id] || '',
                        judul: old.judul || '',
                        deskripsi: old.deskripsi || '',
                        gambar_layanan: window.layananItemMediaMap[reopen.id] || '',
                    };
                    this.modal = 'item';
                }
            },

            kategoriAction() {
                const root = this.$root.dataset;
                return this.kategori.id
                    ? root.kategoriUpdateUrl + '/' + this.kategori.id
                    : root.kategoriStoreUrl;
            },

            itemAction() {
                const root = this.$root.dataset;
                return this.item.id
                    ? root.itemUpdateUrl + '/' + this.item.id
                    : root.itemStoreUrl;
            },

            mediaUrl(name) {
                return this.$root.dataset.mediaBaseUrl + encodeURIComponent(name);
            },

            openKategoriCreate() {
                this.kategori = { id: null, nama: '', deskripsi: '', gambar_kategori: '' };
                this.$refs.kategoriImageInput.value = '';
                this.modal = 'kategori';
                this.$nextTick(() => this.$refs.namaInput && this.$refs.namaInput.focus());
            },

            openKategoriEdit(el) {
                this.kategori = {
                    id: el.dataset.id,
                    nama: el.dataset.nama || '',
                    deskripsi: el.dataset.deskripsi || '',
                    gambar_kategori: el.dataset.gambarKategori || '',
                };
                this.$refs.kategoriImageInput.value = '';
                this.modal = 'kategori';
                this.$nextTick(() => this.$refs.namaInput && this.$refs.namaInput.focus());
            },

            openItemCreate(el) {
                const kategoriId = el.dataset.kategoriId;
                this.item = {
                    id: null,
                    kategori_id: kategoriId,
                    kategori_nama: window.layananKategoriMap[kategoriId] || '',
                    judul: '',
                    deskripsi: '',
                    gambar_layanan: '',
                };
                this.$refs.itemImageInput.value = '';
                this.modal = 'item';
                this.$nextTick(() => this.$refs.judulInput && this.$refs.judulInput.focus());
            },

            openItemEdit(el) {
                const kategoriId = el.dataset.kategoriId;
                this.item = {
                    id: el.dataset.id,
                    kategori_id: kategoriId,
                    kategori_nama: window.layananKategoriMap[kategoriId] || '',
                    judul: el.dataset.judul || '',
                    deskripsi: el.dataset.deskripsi || '',
                    gambar_layanan: el.dataset.gambarLayanan || '',
                };
                this.$refs.itemImageInput.value = '';
                this.modal = 'item';
                this.$nextTick(() => this.$refs.judulInput && this.$refs.judulInput.focus());
            },

            closeModal() {
                this.modal = null;
            },
        };
    }

    document.querySelectorAll('[data-delete-form]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            const name = form.dataset.positionName || 'data ini';
            Swal.fire({
                title: 'Hapus data?',
                text: `Hapus ${name}? Tindakan ini tidak dapat dibatalkan.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#be123c',
                cancelButtonColor: '#64748b',
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });
</script>
<?= $this->endSection() ?>
