<!-- sidebar admin -->

<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="<?= base_url('images/logo.png') ?>">
    <title><?= $this->renderSection('title') ?><?= $this->renderSection('title') ? ' - ' : '' ?>Admin Panel SIRS RSUP Dr. Kariadi</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>?v=<?= time() ?>">

    <!-- Alpine.js -->
    <script defer src="<?= base_url('js/alpine.min.js') ?>"></script>

    <?= $this->renderSection('styles') ?>
</head>

<body class="h-full antialiased font-sans text-slate-800" x-data="{ sidebarOpen: false }">

    <div class="min-h-full flex">
        <!-- Backdrop Mobile -->
        <div x-show="sidebarOpen"
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="sidebarOpen = false"
            class="fixed inset-0 bg-slate-900/60 z-40 lg:hidden"
            style="display: none;"></div>

        <!-- Sidebar -->
        <?php
        $segment = service('uri')->getSegment(2) ?: 'dashboard';

        $menu = [
            'Menu Utama' => [
                ['key' => 'dashboard',  'label' => 'Dashboard',           'icon' => ['M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6']],
                ['key' => 'organisasi', 'label' => 'Struktur Organisasi', 'icon' => ['M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z']],
            ],
            'Layanan' => [
                ['key' => 'layanan', 'label' => 'Layanan IT',   'icon' => ['M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z']],
                ['key' => 'kis',     'label' => 'Aplikasi KIS', 'icon' => ['M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4']],
            ],
            'Publikasi' => [
                ['key' => 'berita',   'label' => 'Berita & Pengumuman', 'icon' => ['M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z']],
                ['key' => 'kegiatan', 'label' => 'Galeri Kegiatan',     'icon' => ['M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z']],
                ['key' => 'kategori', 'label' => 'Kelola Kategori',     'icon' => ['M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z']],
            ],
            'Sistem' => [
                ['key' => 'settings', 'label' => 'Pengaturan Web', 'icon' => [
                    'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
                    'M15 12a3 3 0 11-6 0 3 3 0 016 0z',
                ]],
            ],
        ];
        ?>
        <!-- Sidebar -->
        <?php
        $segment = service('uri')->getSegment(2) ?: 'dashboard';

        $menu = [
            'Menu Utama' => [
                ['key' => 'dashboard',  'label' => 'Dashboard',           'icon' => ['M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6']],
                ['key' => 'organisasi', 'label' => 'Struktur Organisasi', 'icon' => ['M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z']],
            ],
            'Layanan' => [
                ['key' => 'layanan', 'label' => 'Layanan IT',   'icon' => ['M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z']],
                ['key' => 'kis',     'label' => 'Aplikasi KIS', 'icon' => ['M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4']],
            ],
            'Publikasi' => [
                ['key' => 'berita',   'label' => 'Berita & Pengumuman', 'icon' => ['M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z']],
                ['key' => 'kegiatan', 'label' => 'Galeri Kegiatan',     'icon' => ['M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z']],
                ['key' => 'kategori', 'label' => 'Kelola Kategori',     'icon' => ['M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z']],
            ],
            'Sistem' => [
                ['key' => 'settings', 'label' => 'Pengaturan Web', 'icon' => [
                    'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
                    'M15 12a3 3 0 11-6 0 3 3 0 016 0z',
                ]],
            ],
        ];
        ?>
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed inset-y-0 left-0 z-50 w-72 overflow-hidden bg-white border-r border-slate-200 flex flex-col transition-transform duration-300 ease-in-out">

            <!-- Brand -->
            <div class="h-20 flex items-center justify-between px-6 border-b border-slate-100">
                <a href="<?= base_url('admin/dashboard') ?>" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#4AD9D4] to-teal-600 flex items-center justify-center text-white text-sm font-bold font-heading shadow-sm">
                        SIRS
                    </div>
                    <div class="leading-tight">
                        <p class="text-sm font-bold text-slate-800 font-heading">Company Profile</p>
                        <p class="text-xs text-teal-600 font-medium">RSUP Dr. Kariadi</p>
                    </div>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden p-1 text-slate-400 hover:text-slate-700" aria-label="Tutup menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <!-- Keep the fixed sidebar from scrolling vertically. -->
            <nav class="flex-1 overflow-hidden px-4 py-6 text-sm">
                <?php foreach ($menu as $section => $items): ?>
                    <p class="px-3 mb-2 text-[11px] font-semibold uppercase tracking-wider text-slate-400 <?= $section !== array_key_first($menu) ? 'mt-7' : '' ?>">
                        <?= esc($section) ?>
                    </p>
                    <div class="space-y-1">
                        <?php foreach ($items as $item): ?>
                            <?php $active = ($segment === $item['key']); ?>
                            <a href="<?= base_url('admin/' . $item['key']) ?>"
                                <?= $active ? 'aria-current="page"' : '' ?>
                                class="relative flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-colors
                                <?= $active
                                    ? 'bg-teal-50 text-teal-700'
                                    : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>">
                                <?php if ($active): ?>
                                    <span class="absolute left-0 top-1/2 -translate-y-1/2 h-5 w-1 rounded-r-full bg-teal-500"></span>
                                <?php endif; ?>
                                <svg class="w-5 h-5 shrink-0 <?= $active ? 'text-teal-600' : 'text-slate-400' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <?php foreach ($item['icon'] as $path): ?>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $path ?>" />
                                    <?php endforeach; ?>
                                </svg>
                                <span><?= esc($item['label']) ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </nav>

            <!-- Footer -->
            <div class="p-4 border-t border-slate-100">
                <a href="<?= base_url() ?>" target="_blank" rel="noopener"
                    class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 hover:text-teal-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    <span>Lihat Website Publik</span>
                </a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 lg:pl-72 flex flex-col min-h-screen">
            <!-- Top Navbar -->
            <header class="h-20 bg-white border-b border-slate-200 sticky top-0 z-30 flex items-center justify-between px-4 sm:px-8 shadow-sm">
                <div class="flex items-center space-x-4">
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl text-slate-500 hover:bg-slate-100 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div>
                        <h2 class="text-lg font-bold font-heading text-slate-800 leading-tight">
                            <?= $this->renderSection('page_heading', 'Dashboard Admin') ?>
                        </h2>
                        <p class="text-xs text-slate-500 hidden sm:block">
                            <?= $this->renderSection('page_description', 'Kelola seluruh informasi portal SIRS RSUP Dr. Kariadi') ?>
                        </p>
                    </div>
                </div>

                <!-- User Dropdown & Status -->
                <div class="flex items-center space-x-4" x-data="{ userMenu: false }">
                    <div class="relative">
                        <button @click="userMenu = !userMenu" @click.outside="userMenu = false" class="flex items-center space-x-3 p-1.5 rounded-xl hover:bg-slate-100 transition focus:outline-none">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-brand-teal-700 to-brand-teal-500 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                <?= strtoupper(substr(auth()->user()->username ?? 'A', 0, 1)) ?>
                            </div>
                            <div class="text-left hidden sm:block">
                                <div class="text-xs font-bold text-slate-800 leading-tight"><?= esc(auth()->user()->username ?? 'Administrator') ?></div>
                                <div class="text-[11px] text-slate-400">Super Admin SIRS</div>
                            </div>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="userMenu"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-48 bg-cyan-500  rounded-2xl shadow-xl border border-slate-100 py-2 z-50"
                            style="display: none;">
                            <div class="px-4 py-2 border-b border-slate-100 text-xs">
                                <p class="text-slate-400">Login sebagai</p>
                                <p class="font-bold text-slate-800 truncate"><?= esc(auth()->user()->email ?? 'admin@karadi.co.id') ?></p>
                            </div>
                            <a href="<?= base_url('admin/settings') ?>" class="flex items-center space-x-2 px-4 py-2 text-xs text-slate-700 hover:bg-slate-50 font-medium">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                </svg>
                                <span>Pengaturan Web</span>
                            </a>
                            <div class="border-t border-slate-100 my-1"></div>
                            <a href="<?= base_url('logout') ?>" class="flex items-center space-x-2 px-4 py-2 text-xs text-rose-600 hover:bg-rose-50 font-medium">
                                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Keluar (Logout)</span>
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Body Content -->
            <main class="flex-1 p-4 sm:p-8">
                <!-- Flash Notification Banners -->
                <?php if (session()->getFlashdata('message')): ?>
                    <div x-data="{ show: true }" x-show="show" class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3.5 rounded-2xl flex items-center justify-between shadow-sm">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold">✓</div>
                            <span class="text-sm font-medium"><?= session()->getFlashdata('message') ?></span>
                        </div>
                        <button @click="show = false" class="text-emerald-500 hover:text-emerald-800 p-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div x-data="{ show: true }" x-show="show" class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3.5 rounded-2xl flex items-center justify-between shadow-sm">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center font-bold">!</div>
                            <span class="text-sm font-medium"><?= session()->getFlashdata('error') ?></span>
                        </div>
                        <button @click="show = false" class="text-rose-500 hover:text-rose-800 p-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                <?php endif; ?>

                <?= $this->renderSection('content') ?>
            </main>

            <!-- Admin Footer -->
            <footer class="bg-white border-t border-slate-200 py-4 px-8 text-center text-xs text-slate-400">
                &copy; <?= date('Y') ?> Instalasi SIRS RSUP Dr. Kariadi Semarang.
            </footer>
        </div>
    </div>

    <?= $this->renderSection('scripts') ?>
</body>

</html>