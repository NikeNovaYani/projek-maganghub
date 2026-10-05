<?= $this->extend('layouts/admin') ?>
<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>
<?= $this->section('page_heading') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <!-- Overview Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-5 mb-8">
        <!-- Card 1 -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-slate-400">Total Berita</span>
                <div class="w-9 h-9 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                </div>
            </div>
            <div class="text-2xl font-black font-heading text-slate-800"><?= $stats['total_berita'] ?></div>
            <a href="<?= base_url('admin/berita') ?>" class="mt-2 inline-flex items-center space-x-1 text-xs text-brand-teal-600 font-semibold hover:underline">
                <span>Kelola Berita &rarr;</span>
            </a>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-slate-400">Pengurus Org</span>
                <div class="w-9 h-9 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            <div class="text-2xl font-black font-heading text-slate-800"><?= $stats['total_organisasi'] ?></div>
            <a href="<?= base_url('admin/organisasi') ?>" class="mt-2 inline-flex items-center space-x-1 text-xs text-blue-600 font-semibold hover:underline">
                <span>Struktur &rarr;</span>
            </a>
        </div>

        <!-- Card 3 -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-slate-400">Layanan IT</span>
                <div class="w-9 h-9 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                </div>
            </div>
            <div class="text-2xl font-black font-heading text-slate-800"><?= $stats['total_layanan'] ?></div>
            <a href="<?= base_url('admin/layanan') ?>" class="mt-2 inline-flex items-center space-x-1 text-xs text-emerald-600 font-semibold hover:underline">
                <span>Item Layanan &rarr;</span>
            </a>
        </div>

        <!-- Card 4 -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-slate-400">Aplikasi KIS</span>
                <div class="w-9 h-9 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                </div>
            </div>
            <div class="text-2xl font-black font-heading text-slate-800"><?= $stats['total_kis'] ?></div>
            <a href="<?= base_url('admin/aplikasi-kis') ?>" class="mt-2 inline-flex items-center space-x-1 text-xs text-indigo-600 font-semibold hover:underline">
                <span>Daftar KIS &rarr;</span>
            </a>
        </div>

        <!-- Card 5 -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-slate-400">Album Galeri</span>
                <div class="w-9 h-9 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <div class="text-2xl font-black font-heading text-slate-800"><?= $stats['total_kegiatan'] ?></div>
            <a href="<?= base_url('admin/kegiatan') ?>" class="mt-2 inline-flex items-center space-x-1 text-xs text-amber-600 font-semibold hover:underline">
                <span>Album &rarr;</span>
            </a>
        </div>

        <!-- Card 6 -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-slate-400">Kategori</span>
                <div class="w-9 h-9 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
            </div>
            <div class="text-2xl font-black font-heading text-slate-800"><?= $stats['total_kategori'] ?></div>
            <a href="<?= base_url('admin/kategori') ?>" class="mt-2 inline-flex items-center space-x-1 text-xs text-rose-600 font-semibold hover:underline">
                <span>Kelola &rarr;</span>
            </a>
        </div>
    </div>

    <!-- Quick Integrations & Status -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left: Quick Links & Manpro Info -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-gradient-to-br from-brand-teal-900 to-slate-900 rounded-3xl p-6 text-white shadow-lg relative overflow-hidden">
                <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-brand-lime-400/10 rounded-full blur-xl"></div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-brand-lime-400 text-slate-900 mb-4">
                    Integrasi Utama
                </span>
                <h3 class="text-lg font-bold font-heading mb-1">Sistem Manpro SIRS</h3>
                <p class="text-xs text-slate-300 leading-relaxed mb-4">
                    Portal eksternal pelaporan kendala dan monitoring tiket pengerjaan IT RSUP Dr. Kariadi.
                </p>
                <div class="bg-white/10 p-3 rounded-2xl border border-white/10 text-xs break-all font-mono text-brand-lime-300 mb-4">
                    <?= esc(site_setting('manpro_url', 'https://manpro.rskariadi.id')) ?>
                </div>
                <a href="<?= base_url('admin/settings') ?>" class="inline-flex items-center space-x-2 text-xs font-bold text-white hover:text-brand-lime-300 transition">
                    <span>Ubah URL Manpro di Pengaturan &rarr;</span>
                </a>
            </div>

            <!-- Quick Action Box -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-3">
                <h4 class="text-sm font-bold font-heading text-slate-800">Aksi Cepat</h4>
                <div class="grid grid-cols-2 gap-2">
                    <a href="<?= base_url('admin/berita/create') ?>" class="p-3 rounded-2xl bg-slate-50 hover:bg-brand-teal-50 hover:text-brand-teal-700 text-xs font-semibold text-slate-700 transition text-center flex flex-col items-center justify-center gap-1">
                        <span class="text-lg">+</span>
                        <span>Tulis Berita</span>
                    </a>
                    <a href="<?= base_url('admin/organisasi/create') ?>" class="p-3 rounded-2xl bg-slate-50 hover:bg-brand-teal-50 hover:text-brand-teal-700 text-xs font-semibold text-slate-700 transition text-center flex flex-col items-center justify-center gap-1">
                        <span class="text-lg">+</span>
                        <span>Tambah Anggota</span>
                    </a>
                    <a href="<?= base_url('admin/kis/create') ?>" class="p-3 rounded-2xl bg-slate-50 hover:bg-brand-teal-50 hover:text-brand-teal-700 text-xs font-semibold text-slate-700 transition text-center flex flex-col items-center justify-center gap-1">
                        <span class="text-lg">+</span>
                        <span>Tambah App KIS</span>
                    </a>
                    <a href="<?= base_url('admin/kegiatan/create') ?>" class="p-3 rounded-2xl bg-slate-50 hover:bg-brand-teal-50 hover:text-brand-teal-700 text-xs font-semibold text-slate-700 transition text-center flex flex-col items-center justify-center gap-1">
                        <span class="text-lg">+</span>
                        <span>Upload Album</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Right: Recent Articles -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-base font-bold font-heading text-slate-800">Berita & Pengumuman Terbaru</h3>
                    <p class="text-xs text-slate-400">Daftar publikasi artikel terakhir</p>
                </div>
                <a href="<?= base_url('admin/berita') ?>" class="text-xs font-bold text-brand-teal-600 hover:text-brand-teal-800">Lihat Semua</a>
            </div>

            <div class="divide-y divide-slate-100">
                <?php if (empty($latestBerita)): ?>
                    <p class="text-xs text-slate-400 py-6 text-center">Belum ada berita yang diterbitkan.</p>
                <?php else: ?>
                    <?php foreach ($latestBerita as $item): ?>
                        <div class="py-3.5 flex items-center justify-between gap-4">
                            <div class="min-w-0">
                                <div class="flex items-center space-x-2 mb-1">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold <?= $item['status'] == 'publish' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' ?>">
                                        <?= strtoupper($item['status']) ?>
                                    </span>
                                    <span class="text-xs text-slate-400"><?= esc($item['kategori_nama'] ?? 'Umum') ?></span>
                                    <span class="text-xs text-slate-300">•</span>
                                    <span class="text-xs text-slate-400"><?= date('d M Y', strtotime($item['created_at'])) ?></span>
                                </div>
                                <h4 class="text-sm font-bold text-slate-800 truncate"><?= esc($item['judul']) ?></h4>
                            </div>
                            <div class="shrink-0 flex items-center space-x-2">
                                <a href="<?= base_url('admin/berita/edit/' . $item['id']) ?>" class="p-2 text-slate-400 hover:text-brand-teal-600 rounded-xl hover:bg-slate-50 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>
