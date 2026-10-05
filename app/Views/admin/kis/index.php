<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Aplikasi KIS<?= $this->endSection() ?>
<?= $this->section('page_heading') ?>Aplikasi KIS<?= $this->endSection() ?>
<?= $this->section('page_description') ?>Kelola aplikasi Kariadi Information System yang tampil di website publik<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
/** @var list<array<string, mixed>> $kategori */
/** @var list<array<string, mixed>> $tanpaKategori */
/** @var array<string, string> $klasifikasi */

$klasColors = ['pmk82' => '#16a34a', 'diluar_pmk' => '#2563eb', 'eksternal' => '#f59e0b'];

// Ringkasan
$allApps = $tanpaKategori;
foreach ($kategori as $k) {
    $allApps = array_merge($allApps, $k['apps']);
}
$totalApps = count($allApps);
$totalKategori = count($kategori);
$totalTanpaLink = count(array_filter($allApps, static fn(array $a): bool => trim((string) ($a['url'] ?? '')) === ''));
$totalInternal = count(array_filter($allApps, static fn(array $a): bool => (int) ($a['akses_internal'] ?? 0) === 1));

// Tab aktif: dari ?tab=..., kalau tidak ada pakai tab pertama
$tabKeys = array_column($kategori, 'slug');
if ($tanpaKategori !== []) {
    $tabKeys[] = 'belum';
}
$requestedTab = (string) service('request')->getGet('tab');
$activeTab = in_array($requestedTab, $tabKeys, true) ? $requestedTab : ($tabKeys[0] ?? '');

// Data untuk pop up setelah validasi gagal
$errors = session()->getFlashdata('errors') ?? [];
if (is_string($errors)) {
    $errors = [$errors];
}
$reopen = session()->getFlashdata('reopen');
$reopenType = is_array($reopen) ? ($reopen['type'] ?? '') : '';
$oldForm = [
    'nama'              => (string) old('nama', ''),
    'deskripsi'         => (string) old('deskripsi', ''),
    'kategori_id'       => (string) old('kategori_id', ''),
    'nama_aplikasi'     => (string) old('nama_aplikasi', ''),
    'deskripsi_singkat' => (string) old('deskripsi_singkat', ''),
    'url'               => (string) old('url', ''),
    'klasifikasi'       => (string) old('klasifikasi', ''),
    'akses_internal'    => old('akses_internal') ? '1' : '',
];
$slugMap = array_column($kategori, 'id', 'slug');
$jsonFlags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;
$lastKategori = count($kategori) - 1;

$renderApp = static function (array $app, int $no, int $total, string $tabSlug) use ($klasifikasi): void {
    $klas = (string) ($app['klasifikasi'] ?? '');
    $url = trim((string) ($app['url'] ?? ''));
    $internal = (int) ($app['akses_internal'] ?? 0) === 1;
    $searchText = mb_strtolower((string) $app['nama_aplikasi'] . ' ' . (string) ($app['deskripsi_singkat'] ?? ''));
?>
    <article class="kis-card" data-klas="<?= esc($klas, 'attr') ?>" data-search="<?= esc($searchText, 'attr') ?>" x-show="matches($el)">
        <span class="kis-card__no"><?= $no ?></span>
        <div class="kis-card__body">
            <h3 class="kis-card__name"><?= esc($app['nama_aplikasi']) ?></h3>
            <?php if (! empty($app['deskripsi_singkat'])): ?>
                <p class="kis-card__desc"><?= esc($app['deskripsi_singkat']) ?></p>
            <?php endif; ?>

            <div class="kis-card__tags">
                <?php if ($klas !== '' && isset($klasifikasi[$klas])): ?>
                    <span class="kis-tag kis-tag--klas"><?= esc($klasifikasi[$klas]) ?></span>
                <?php endif; ?>
                <?php if ($internal): ?>
                    <span class="kis-tag kis-tag--internal">
                        <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        Akses internal
                    </span>
                <?php endif; ?>
            </div>

            <div class="kis-card__link">
                <?php if ($url !== ''): ?>
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                    <span class="kis-card__url" title="<?= esc($url, 'attr') ?>"><?= esc($url) ?></span>
                <?php else: ?>
                    <span class="kis-tag kis-tag--warn">Belum ada link</span>
                <?php endif; ?>
            </div>

            <div class="kis-card__actions">
                <form action="<?= base_url('admin/kis/app/move/' . $app['id'] . '/up') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="tab" value="<?= esc($tabSlug, 'attr') ?>">
                    <button type="submit" class="kis-btn kis-btn--icon" <?= $no === 1 ? 'disabled' : '' ?> title="Naik" aria-label="Naik">&uarr;</button>
                </form>
                <form action="<?= base_url('admin/kis/app/move/' . $app['id'] . '/down') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="tab" value="<?= esc($tabSlug, 'attr') ?>">
                    <button type="submit" class="kis-btn kis-btn--icon" <?= $no === $total ? 'disabled' : '' ?> title="Turun" aria-label="Turun">&darr;</button>
                </form>
                <button type="button" class="kis-btn"
                    data-id="<?= (int) $app['id'] ?>"
                    data-kategori-id="<?= (int) ($app['kategori_id'] ?? 0) ?>"
                    data-nama="<?= esc($app['nama_aplikasi'], 'attr') ?>"
                    data-deskripsi="<?= esc((string) ($app['deskripsi_singkat'] ?? ''), 'attr') ?>"
                    data-url="<?= esc($url, 'attr') ?>"
                    data-klasifikasi="<?= esc($klas, 'attr') ?>"
                    data-internal="<?= $internal ? '1' : '0' ?>"
                    @click="openAppEdit($el)">Edit</button>
                <form action="<?= base_url('admin/kis/app/delete/' . $app['id']) ?>" method="post" data-delete-form data-position-name="<?= esc($app['nama_aplikasi'], 'attr') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="tab" value="<?= esc($tabSlug, 'attr') ?>">
                    <button type="submit" class="kis-btn kis-btn--danger">Hapus</button>
                </form>
            </div>
        </div>
    </article>
<?php
};
?>

<style>
    .kis-stat { padding: 1rem 1.15rem; background: #fff; border: 1px solid #e2e8f0; border-radius: 1rem; box-shadow: 0 6px 18px rgba(15, 23, 42, .04); }
    .kis-stat__value { font-size: 1.75rem; font-weight: 800; line-height: 1; color: var(--tone); }
    .kis-stat__label { margin-top: .4rem; font-size: .72rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; color: #64748b; }
    .kis-stat--teal { --tone: #0f766e; border-top: 3px solid #14b8a6; }
    .kis-stat--green { --tone: #16a34a; border-top: 3px solid #22c55e; }
    .kis-stat--amber { --tone: #d97706; border-top: 3px solid #f59e0b; }
    .kis-stat--indigo { --tone: #4f46e5; border-top: 3px solid #6366f1; }

    .kis-legend { display: flex; flex-wrap: wrap; align-items: center; gap: .5rem 1.1rem; padding: .65rem 1rem; background: #fff; border: 1px solid #e2e8f0; border-radius: .9rem; font-size: .75rem; color: #475569; }
    .kis-legend__title { font-weight: 800; text-transform: uppercase; letter-spacing: .05em; color: #94a3b8; }
    .kis-legend__item { display: inline-flex; align-items: center; gap: .4rem; font-weight: 600; }
    .kis-legend__item i { display: inline-block; width: .7rem; height: .7rem; border-radius: .25rem; }

    .kis-tabs { display: flex; gap: .5rem; padding-bottom: .25rem; overflow-x: auto; }
    .kis-tab { display: inline-flex; flex: 0 0 auto; align-items: center; gap: .55rem; padding: .55rem 1rem; font-size: .85rem; font-weight: 700; color: #475569; white-space: nowrap; background: #fff; border: 1px solid #e2e8f0; border-radius: 9999px; transition: all .15s ease; }
    .kis-tab:hover { color: #0f766e; border-color: #5eead4; }
    .kis-tab.is-active { color: #fff; background: #0f766e; border-color: #0f766e; box-shadow: 0 8px 18px rgba(15, 118, 110, .25); }
    .kis-tab__count { min-width: 1.5rem; padding: .05rem .45rem; font-size: .7rem; font-weight: 800; text-align: center; color: #0f766e; background: #ccfbf1; border-radius: 9999px; }
    .kis-tab.is-active .kis-tab__count { color: #0f766e; background: #fff; }
    .kis-tab--warn { color: #92400e; background: #fffbeb; border-color: #fcd34d; }
    .kis-tab--warn .kis-tab__count { color: #92400e; background: #fde68a; }

    .kis-panel__head { display: flex; flex-wrap: wrap; align-items: flex-start; justify-content: space-between; gap: 1rem; padding: 1.1rem 1.25rem; background: linear-gradient(135deg, #f0fdfa, #fff); border: 1px solid #ccfbf1; border-radius: 1rem; }
    .kis-panel__head--warn { background: linear-gradient(135deg, #fffbeb, #fff); border-color: #fde68a; }

    .kis-card { --accent: #94a3b8; --accent-soft: #f1f5f9; --accent-ink: #475569; position: relative; display: flex; gap: .9rem; padding: 1rem 1.1rem; background: #fff; border: 1px solid #e2e8f0; border-left: 4px solid var(--accent); border-radius: 1rem; box-shadow: 0 6px 18px rgba(15, 23, 42, .05); transition: transform .15s ease, box-shadow .15s ease; }
    .kis-card:hover { transform: translateY(-2px); box-shadow: 0 12px 26px rgba(15, 23, 42, .09); }
    .kis-card[data-klas="pmk82"] { --accent: #16a34a; --accent-soft: #dcfce7; --accent-ink: #166534; }
    .kis-card[data-klas="diluar_pmk"] { --accent: #2563eb; --accent-soft: #dbeafe; --accent-ink: #1e40af; }
    .kis-card[data-klas="eksternal"] { --accent: #f59e0b; --accent-soft: #fef3c7; --accent-ink: #92400e; }
    .kis-card__no { display: grid; flex: 0 0 auto; place-items: center; width: 2rem; height: 2rem; font-size: .8rem; font-weight: 800; color: var(--accent-ink); background: var(--accent-soft); border-radius: .65rem; }
    .kis-card__body { min-width: 0; flex: 1; }
    .kis-card__name { font-size: .95rem; font-weight: 700; line-height: 1.3; color: #0f172a; }
    .kis-card__desc { margin-top: .3rem; font-size: .78rem; line-height: 1.5; color: #64748b; }
    .kis-card__tags { display: flex; flex-wrap: wrap; gap: .35rem; margin-top: .6rem; }
    .kis-card__link { display: flex; align-items: center; gap: .4rem; margin-top: .65rem; padding-top: .6rem; font-size: .72rem; color: #0f766e; border-top: 1px dashed #e2e8f0; }
    .kis-card__url { min-width: 0; overflow: hidden; font-family: ui-monospace, SFMono-Regular, Menlo, monospace; text-overflow: ellipsis; white-space: nowrap; }
    .kis-card__actions { display: flex; flex-wrap: wrap; align-items: center; gap: .35rem; margin-top: .75rem; }
    .kis-card__actions form { display: inline; }

    .kis-tag { display: inline-flex; align-items: center; gap: .3rem; padding: .15rem .55rem; font-size: .68rem; font-weight: 700; border-radius: 9999px; }
    .kis-tag--klas { color: var(--accent-ink); background: var(--accent-soft); }
    .kis-tag--internal { color: #4338ca; background: #e0e7ff; }
    .kis-tag--warn { color: #92400e; background: #fef3c7; }

    .kis-btn { display: inline-flex; align-items: center; justify-content: center; height: 1.9rem; padding: 0 .7rem; font-size: .72rem; font-weight: 700; color: #334155; cursor: pointer; background: #fff; border: 1px solid #e2e8f0; border-radius: .55rem; transition: background .15s ease; }
    .kis-btn:hover { background: #f1f5f9; }
    .kis-btn:disabled { cursor: not-allowed; opacity: .4; }
    .kis-btn--icon { width: 1.9rem; padding: 0; font-size: .85rem; }
    .kis-btn--danger { color: #be123c; border-color: #fecdd3; }
    .kis-btn--danger:hover { background: #fff1f2; }

    .kis-modal-backdrop { position: fixed; inset: 0; z-index: 9999; display: flex; align-items: center; justify-content: center; padding: 1rem; background: rgba(15, 23, 42, .45); -webkit-backdrop-filter: blur(6px); backdrop-filter: blur(6px); }
    .kis-modal-box { display: flex; flex-direction: column; width: 100%; max-height: 92vh; overflow: hidden; background: #fff; border-radius: 1rem; box-shadow: 0 25px 50px rgba(15, 23, 42, .3); }
    .kis-modal-scroll { overflow-y: auto; }
</style>

<div class="mx-auto max-w-6xl space-y-6"
    x-data="kisPage('<?= esc($activeTab, 'js') ?>')"
    data-kategori-store-url="<?= base_url('admin/kis/kategori/store') ?>"
    data-kategori-update-url="<?= base_url('admin/kis/kategori/update') ?>"
    data-app-store-url="<?= base_url('admin/kis/app/store') ?>"
    data-app-update-url="<?= base_url('admin/kis/app/update') ?>">

    <!-- Ringkasan -->
    <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
        <div class="kis-stat kis-stat--teal"><p class="kis-stat__value"><?= $totalApps ?></p><p class="kis-stat__label">Total Aplikasi</p></div>
        <div class="kis-stat kis-stat--green"><p class="kis-stat__value"><?= $totalKategori ?></p><p class="kis-stat__label">Kategori</p></div>
        <div class="kis-stat kis-stat--amber"><p class="kis-stat__value"><?= $totalTanpaLink ?></p><p class="kis-stat__label">Belum Ada Link</p></div>
        <div class="kis-stat kis-stat--indigo"><p class="kis-stat__value"><?= $totalInternal ?></p><p class="kis-stat__label">Akses Internal</p></div>
    </div>

    <!-- Legenda -->
    <div class="kis-legend">
        <span class="kis-legend__title">Legenda</span>
        <?php foreach ($klasifikasi as $key => $label): ?>
            <span class="kis-legend__item"><i style="background: <?= esc($klasColors[$key] ?? '#94a3b8', 'attr') ?>"></i><?= esc($label) ?></span>
        <?php endforeach; ?>
    </div>

    <!-- Toolbar -->
    <div class="flex flex-wrap items-center justify-between gap-3">
        <input type="search" x-model="search" placeholder="Cari aplikasi di tab ini..."
            class="h-10 w-full rounded-xl border-slate-300 text-sm focus:border-teal-600 focus:ring-teal-600 sm:w-72">
        <div class="flex flex-wrap gap-2">
            <button type="button" @click="openKategoriCreate()" class="inline-flex min-h-10 items-center rounded-xl border border-teal-200 bg-white px-4 text-sm font-semibold text-teal-800 hover:bg-teal-50">+ Tambah Kategori</button>
            <button type="button" @click="openAppCreate()" class="inline-flex min-h-10 items-center rounded-xl bg-teal-700 px-4 text-sm font-semibold text-white shadow-sm hover:bg-teal-800">+ Tambah Aplikasi</button>
        </div>
    </div>

    <?php if ($kategori === [] && $tanpaKategori === []): ?>
        <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center text-sm text-slate-500">
            Belum ada kategori maupun aplikasi KIS. Mulai dengan menambah kategori.
        </div>
    <?php else: ?>

        <!-- Tab -->
        <div class="kis-tabs" role="tablist">
            <?php foreach ($kategori as $k): ?>
                <button type="button" role="tab" class="kis-tab"
                    :class="{ 'is-active': tab === '<?= esc($k['slug'], 'js') ?>' }"
                    @click="select('<?= esc($k['slug'], 'js') ?>')">
                    <span><?= esc($k['nama']) ?></span>
                    <span class="kis-tab__count"><?= count($k['apps']) ?></span>
                </button>
            <?php endforeach; ?>
            <?php if ($tanpaKategori !== []): ?>
                <button type="button" role="tab" class="kis-tab kis-tab--warn"
                    :class="{ 'is-active': tab === 'belum' }"
                    @click="select('belum')">
                    <span>Belum dikelompokkan</span>
                    <span class="kis-tab__count"><?= count($tanpaKategori) ?></span>
                </button>
            <?php endif; ?>
        </div>

        <!-- Panel per kategori -->
        <?php foreach ($kategori as $ki => $k): ?>
            <?php $appTotal = count($k['apps']); ?>
            <section x-show="tab === '<?= esc($k['slug'], 'js') ?>'" <?= $k['slug'] === $activeTab ? '' : 'style="display: none;"' ?> class="space-y-4">
                <div class="kis-panel__head">
                    <div class="min-w-0 flex-1">
                        <h2 class="font-heading text-lg font-bold text-slate-900"><?= esc($k['nama']) ?></h2>
                        <?php if (! empty($k['deskripsi'])): ?>
                            <p class="mt-1 text-sm text-slate-600"><?= esc($k['deskripsi']) ?></p>
                        <?php endif; ?>
                        <p class="mt-2 text-xs font-semibold text-teal-700"><?= $appTotal ?> aplikasi</p>
                    </div>
                    <div class="kis-card__actions" style="margin-top: 0;">
                        <form action="<?= base_url('admin/kis/kategori/move/' . $k['id'] . '/up') ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="tab" value="<?= esc($k['slug'], 'attr') ?>">
                        </form>
                        <form action="<?= base_url('admin/kis/kategori/move/' . $k['id'] . '/down') ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="tab" value="<?= esc($k['slug'], 'attr') ?>">
                            <button type="submit" class="kis-btn kis-btn--icon" <?= $ki === $lastKategori ? 'disabled' : '' ?> title="Geser ke kanan" aria-label="Geser ke kanan">&rarr;</button>
                        </form>
                        <button type="button" class="kis-btn"
                            data-id="<?= (int) $k['id'] ?>"
                            data-nama="<?= esc($k['nama'], 'attr') ?>"
                            data-deskripsi="<?= esc((string) ($k['deskripsi'] ?? ''), 'attr') ?>"
                            @click="openKategoriEdit($el)">Edit Kategori</button>
                        <form action="<?= base_url('admin/kis/kategori/delete/' . $k['id']) ?>" method="post" data-delete-form data-position-name="<?= esc($k['nama'], 'attr') ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="tab" value="<?= esc($k['slug'], 'attr') ?>">
                            <button type="submit" class="kis-btn kis-btn--danger">Hapus Kategori</button>
                        </form>
                    </div>
                </div>

                <?php if ($k['apps'] === []): ?>
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center text-sm text-slate-500">
                        <p>Belum ada aplikasi di kategori ini.</p>
                        <button type="button" @click="openAppCreate()" class="mt-3 inline-flex h-9 items-center rounded-lg bg-teal-50 px-4 text-xs font-bold text-teal-800 hover:bg-teal-100">+ Tambah Aplikasi</button>
                    </div>
                <?php else: ?>
                    <div class="grid gap-3 md:grid-cols-2">
                        <?php foreach ($k['apps'] as $i => $app): ?>
                            <?php $renderApp($app, $i + 1, $appTotal, $k['slug']); ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
        <?php endforeach; ?>

        <!-- Panel aplikasi tanpa kategori -->
        <?php if ($tanpaKategori !== []): ?>
            <?php $belumTotal = count($tanpaKategori); ?>
            <section x-show="tab === 'belum'" <?= $activeTab === 'belum' ? '' : 'style="display: none;"' ?> class="space-y-4">
                <div class="kis-panel__head kis-panel__head--warn">
                    <div class="min-w-0 flex-1">
                        <h2 class="font-heading text-lg font-bold text-slate-900">Belum dikelompokkan</h2>
                        <p class="mt-1 text-sm text-slate-600">Aplikasi di sini belum punya kategori, jadi <strong>belum tampil di website publik</strong>. Klik <strong>Edit</strong> lalu pilih kategorinya.</p>
                        <p class="mt-2 text-xs font-semibold text-amber-700"><?= $belumTotal ?> aplikasi</p>
                    </div>
                </div>
                <div class="grid gap-3 md:grid-cols-2">
                    <?php foreach ($tanpaKategori as $i => $app): ?>
                        <?php $renderApp($app, $i + 1, $belumTotal, 'belum'); ?>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

    <?php endif; ?>

    <!-- ============ POP UP KATEGORI ============ -->
    <div x-show="modal === 'kategori'" style="display: none;" class="kis-modal-backdrop"
        @keydown.escape.window="closeModal()" @click.self="closeModal()"
        role="dialog" aria-modal="true" aria-labelledby="kis-kategori-title">
        <div class="kis-modal-box" style="max-width: 32rem;">
            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                <h2 id="kis-kategori-title" class="font-heading text-base font-bold text-slate-900" x-text="kategori.id ? 'Edit Kategori' : 'Tambah Kategori'"></h2>
                <button type="button" @click="closeModal()" class="grid h-9 w-9 place-items-center rounded-lg text-xl text-slate-500 hover:bg-slate-100" aria-label="Tutup">&times;</button>
            </div>
            <div class="kis-modal-scroll">
                <form :action="kategoriAction()" method="post" class="space-y-5 p-5 sm:p-6">
                    <?= csrf_field() ?>
                    <input type="hidden" name="tab" :value="tab">

                    <?php if ($errors !== [] && $reopenType === 'kategori'): ?>
                        <div role="alert" class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
                            <p class="font-semibold">Data belum dapat disimpan.</p>
                            <ul class="mt-2 list-inside list-disc space-y-1">
                                <?php foreach ($errors as $error): ?><li><?= esc($error) ?></li><?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="space-y-2">
                        <label for="kis_kategori_nama" class="block text-sm font-semibold text-slate-700">Nama Kategori</label>
                        <input id="kis_kategori_nama" name="nama" type="text" required maxlength="100" x-model="kategori.nama" x-ref="kategoriNama"
                            class="h-11 w-full rounded-xl border-slate-300 text-sm focus:border-teal-600 focus:ring-teal-600">
                    </div>

                    <div class="space-y-2">
                        <label for="kis_kategori_deskripsi" class="block text-sm font-semibold text-slate-700">Deskripsi (opsional)</label>
                        <textarea id="kis_kategori_deskripsi" name="deskripsi" rows="3" maxlength="255" x-model="kategori.deskripsi"
                            class="w-full rounded-xl border-slate-300 text-sm leading-relaxed focus:border-teal-600 focus:ring-teal-600"></textarea>
                        <p class="text-xs text-slate-500">Maksimal 255 karakter.</p>
                    </div>

                    <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                        <button type="button" @click="closeModal()" class="inline-flex min-h-11 items-center justify-center rounded-xl border border-slate-300 px-5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</button>
                        <button type="submit" class="inline-flex min-h-11 items-center justify-center rounded-xl bg-teal-700 px-5 text-sm font-semibold text-white shadow-sm hover:bg-teal-800">
                            <span x-text="kategori.id ? 'Simpan Perubahan' : 'Simpan Kategori'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ============ POP UP APLIKASI ============ -->
    <div x-show="modal === 'app'" style="display: none;" class="kis-modal-backdrop"
        @keydown.escape.window="closeModal()" @click.self="closeModal()"
        role="dialog" aria-modal="true" aria-labelledby="kis-app-title">
        <div class="kis-modal-box" style="max-width: 40rem;">
            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                <h2 id="kis-app-title" class="font-heading text-base font-bold text-slate-900" x-text="app.id ? 'Edit Aplikasi' : 'Tambah Aplikasi'"></h2>
                <button type="button" @click="closeModal()" class="grid h-9 w-9 place-items-center rounded-lg text-xl text-slate-500 hover:bg-slate-100" aria-label="Tutup">&times;</button>
            </div>
            <div class="kis-modal-scroll">
                <form :action="appAction()" method="post" class="space-y-5 p-5 sm:p-6">
                    <?= csrf_field() ?>
                    <input type="hidden" name="tab" :value="tab">

                    <?php if ($errors !== [] && $reopenType === 'app'): ?>
                        <div role="alert" class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
                            <p class="font-semibold">Data belum dapat disimpan.</p>
                            <ul class="mt-2 list-inside list-disc space-y-1">
                                <?php foreach ($errors as $error): ?><li><?= esc($error) ?></li><?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div class="space-y-2">
                            <label for="kis_app_kategori" class="block text-sm font-semibold text-slate-700">Kategori</label>
                            <select id="kis_app_kategori" name="kategori_id" required x-model="app.kategori_id"
                                class="h-11 w-full rounded-xl border-slate-300 text-sm focus:border-teal-600 focus:ring-teal-600">
                                <option value="">Pilih kategori...</option>
                                <?php foreach ($kategori as $k): ?>
                                    <option value="<?= (int) $k['id'] ?>"><?= esc($k['nama']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label for="kis_app_klasifikasi" class="block text-sm font-semibold text-slate-700">Klasifikasi</label>
                            <select id="kis_app_klasifikasi" name="klasifikasi" x-model="app.klasifikasi"
                                class="h-11 w-full rounded-xl border-slate-300 text-sm focus:border-teal-600 focus:ring-teal-600">
                                <option value="">Tidak ditentukan</option>
                                <?php foreach ($klasifikasi as $key => $label): ?>
                                    <option value="<?= esc($key, 'attr') ?>"><?= esc($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="kis_app_nama" class="block text-sm font-semibold text-slate-700">Nama Aplikasi</label>
                        <input id="kis_app_nama" name="nama_aplikasi" type="text" required maxlength="150" x-model="app.nama" x-ref="appNama"
                            class="h-11 w-full rounded-xl border-slate-300 text-sm focus:border-teal-600 focus:ring-teal-600">
                    </div>

                    <div class="space-y-2">
                        <label for="kis_app_deskripsi" class="block text-sm font-semibold text-slate-700">Deskripsi (opsional)</label>
                        <textarea id="kis_app_deskripsi" name="deskripsi_singkat" rows="4" maxlength="500" x-model="app.deskripsi"
                            class="w-full rounded-xl border-slate-300 text-sm leading-relaxed focus:border-teal-600 focus:ring-teal-600"></textarea>
                        <p class="text-right text-xs text-slate-500"><span x-text="app.deskripsi.length"></span>/500</p>
                    </div>

                    <div class="space-y-2">
                        <label for="kis_app_url" class="block text-sm font-semibold text-slate-700">Link Aplikasi (opsional)</label>
                        <input id="kis_app_url" name="url" type="url" maxlength="255" placeholder="https://..." x-model="app.url"
                            class="h-11 w-full rounded-xl border-slate-300 text-sm focus:border-teal-600 focus:ring-teal-600">
                        <p class="text-xs text-slate-500">Harus diawali http:// atau https://. Boleh dikosongkan; aplikasi tanpa link tidak bisa diklik di website publik.</p>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <label class="flex cursor-pointer items-start gap-3 text-sm text-slate-700">
                            <input type="checkbox" name="akses_internal" value="1" x-model="app.akses_internal" class="mt-0.5 h-4 w-4 rounded border-slate-300 text-teal-700 focus:ring-teal-600">
                            <span><strong>Akses internal</strong><br><span class="text-xs text-slate-500">Aplikasi untuk jaringan rumah sakit atau perlu login khusus. Tetap tampil dan bisa diklik di website publik dengan label "Akses internal".</span></span>
                        </label>
                    </div>

                    <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                        <button type="button" @click="closeModal()" class="inline-flex min-h-11 items-center justify-center rounded-xl border border-slate-300 px-5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</button>
                        <button type="submit" class="inline-flex min-h-11 items-center justify-center rounded-xl bg-teal-700 px-5 text-sm font-semibold text-white shadow-sm hover:bg-teal-800">
                            <span x-text="app.id ? 'Simpan Perubahan' : 'Simpan Aplikasi'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.kisReopen = <?= json_encode($reopen ?? null, $jsonFlags) ?>;
    window.kisOld = <?= json_encode($oldForm, $jsonFlags) ?>;
    window.kisSlugMap = <?= json_encode((object) $slugMap, $jsonFlags) ?>;

    function kisPage(initialTab) {
        const blankApp = () => ({
            id: null, kategori_id: '', nama: '', deskripsi: '', url: '',
            klasifikasi: '', akses_internal: false,
        });

        return {
            tab: initialTab,
            search: '',
            modal: null,
            kategori: { id: null, nama: '', deskripsi: '' },
            app: blankApp(),

            init() {
                // Setelah validasi gagal: buka lagi pop up yang sama dengan isian sebelumnya
                const reopen = window.kisReopen;
                const old = window.kisOld || {};
                if (!reopen) return;

                if (reopen.type === 'kategori') {
                    this.kategori = { id: reopen.id, nama: old.nama || '', deskripsi: old.deskripsi || '' };
                    this.modal = 'kategori';
                } else if (reopen.type === 'app') {
                    this.app = {
                        id: reopen.id,
                        kategori_id: String(old.kategori_id || ''),
                        nama: old.nama_aplikasi || '',
                        deskripsi: old.deskripsi_singkat || '',
                        url: old.url || '',
                        klasifikasi: old.klasifikasi || '',
                        akses_internal: old.akses_internal === '1',
                    };
                    this.modal = 'app';
                }
            },

            select(key) {
                this.tab = key;
                this.search = '';
                // Simpan tab aktif di alamat supaya tidak hilang saat halaman dimuat ulang
                const url = new URL(window.location);
                url.searchParams.set('tab', key);
                history.replaceState(null, '', url);
            },

            matches(el) {
                const q = this.search.trim().toLowerCase();
                return q === '' || (el.dataset.search || '').includes(q);
            },

            kategoriAction() {
                const root = this.$root.dataset;
                return this.kategori.id ? root.kategoriUpdateUrl + '/' + this.kategori.id : root.kategoriStoreUrl;
            },

            appAction() {
                const root = this.$root.dataset;
                return this.app.id ? root.appUpdateUrl + '/' + this.app.id : root.appStoreUrl;
            },

            openKategoriCreate() {
                this.kategori = { id: null, nama: '', deskripsi: '' };
                this.modal = 'kategori';
                this.$nextTick(() => this.$refs.kategoriNama && this.$refs.kategoriNama.focus());
            },

            openKategoriEdit(el) {
                this.kategori = {
                    id: el.dataset.id,
                    nama: el.dataset.nama || '',
                    deskripsi: el.dataset.deskripsi || '',
                };
                this.modal = 'kategori';
                this.$nextTick(() => this.$refs.kategoriNama && this.$refs.kategoriNama.focus());
            },

            openAppCreate() {
                this.app = blankApp();
                // Kategori mengikuti tab yang sedang dibuka (tab "belum" harus memilih sendiri)
                this.app.kategori_id = String(window.kisSlugMap[this.tab] || '');
                this.modal = 'app';
                this.$nextTick(() => this.$refs.appNama && this.$refs.appNama.focus());
            },

            openAppEdit(el) {
                const d = el.dataset;
                this.app = {
                    id: d.id,
                    kategori_id: d.kategoriId && d.kategoriId !== '0' ? String(d.kategoriId) : '',
                    nama: d.nama || '',
                    deskripsi: d.deskripsi || '',
                    url: d.url || '',
                    klasifikasi: d.klasifikasi || '',
                    akses_internal: d.internal === '1',
                };
                this.modal = 'app';
                this.$nextTick(() => this.$refs.appNama && this.$refs.appNama.focus());
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