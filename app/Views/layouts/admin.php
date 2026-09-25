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
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
               class="fixed inset-y-0 left-0 z-50 w-72 bg-gradient-to-b from-brand-teal-950 via-brand-teal-900 to-slate-900 text-white flex flex-col transition-transform duration-300 ease-in-out">
            
            <!-- Sidebar Header -->
            <div class="h-20 flex items-center justify-between px-6 border-b border-brand-teal-800/60 bg-brand-teal-950/50 backdrop-blur-sm">
                <a href="<?= base_url('admin/dashboard') ?>" class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-lime-400 to-brand-teal-400 flex items-center justify-center shadow-lg shadow-brand-teal-500/20 text-slate-900 font-bold text-lg font-heading">
                        S
                    </div>
                    <div>
                        <h1 class="text-base font-bold tracking-tight text-white font-heading leading-tight">SIRS KARIADI</h1>
                        <p class="text-xs text-brand-teal-300 font-medium tracking-wide">Administrator Panel</p>
                    </div>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Sidebar Navigation -->
            <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1.5 custom-scrollbar text-sm">
                <?php 
                    $uri = service('uri');
                    $segment1 = $uri->getSegment(2); // admin/[segment2]
                ?>

                <div class="px-3 pb-2 text-[11px] font-semibold text-brand-teal-400 uppercase tracking-wider">
                    Menu Utama
                </div>

                <a href="<?= base_url('admin/dashboard') ?>" 
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-medium transition-all <?= ($segment1 == 'dashboard' || empty($segment1)) ? 'bg-brand-teal-600 text-white shadow-md shadow-brand-teal-900/50' : 'text-slate-300 hover:bg-white/10 hover:text-white' ?>">
                    <svg class="w-5 h-5 text-brand-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Dashboard</span>
                </a>

                <a href="<?= base_url('admin/organisasi') ?>" 
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-medium transition-all <?= ($segment1 == 'organisasi') ? 'bg-brand-teal-600 text-white shadow-md' : 'text-slate-300 hover:bg-white/10 hover:text-white' ?>">
                    <svg class="w-5 h-5 text-brand-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>Struktur Organisasi</span>
                </a>

                <div class="pt-4 px-3 pb-2 text-[11px] font-semibold text-brand-teal-400 uppercase tracking-wider">
                    Layanan & Ekosistem
                </div>

                <a href="<?= base_url('admin/layanan') ?>" 
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-medium transition-all <?= ($segment1 == 'layanan') ? 'bg-brand-teal-600 text-white shadow-md' : 'text-slate-300 hover:bg-white/10 hover:text-white' ?>">
                    <svg class="w-5 h-5 text-brand-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    <span>Layanan IT (4 Kategori)</span>
                </a>

                <a href="<?= base_url('admin/kis') ?>" 
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-medium transition-all <?= ($segment1 == 'kis') ? 'bg-brand-teal-600 text-white shadow-md' : 'text-slate-300 hover:bg-white/10 hover:text-white' ?>">
                    <svg class="w-5 h-5 text-brand-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                    <span>Aplikasi KIS (SIMRS)</span>
                </a>

                <div class="pt-4 px-3 pb-2 text-[11px] font-semibold text-brand-teal-400 uppercase tracking-wider">
                    Publikasi & Dokumentasi
                </div>

                <a href="<?= base_url('admin/berita') ?>" 
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-medium transition-all <?= ($segment1 == 'berita') ? 'bg-brand-teal-600 text-white shadow-md' : 'text-slate-300 hover:bg-white/10 hover:text-white' ?>">
                    <svg class="w-5 h-5 text-brand-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    <span>Berita & Pengumuman</span>
                </a>

                <a href="<?= base_url('admin/kegiatan') ?>" 
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-medium transition-all <?= ($segment1 == 'kegiatan') ? 'bg-brand-teal-600 text-white shadow-md' : 'text-slate-300 hover:bg-white/10 hover:text-white' ?>">
                    <svg class="w-5 h-5 text-brand-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Galeri Kegiatan</span>
                </a>

                <a href="<?= base_url('admin/kategori') ?>" 
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-medium transition-all <?= ($segment1 == 'kategori') ? 'bg-brand-teal-600 text-white shadow-md' : 'text-slate-300 hover:bg-white/10 hover:text-white' ?>">
                    <svg class="w-5 h-5 text-brand-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    <span>Kelola Kategori</span>
                </a>

                <div class="pt-4 px-3 pb-2 text-[11px] font-semibold text-brand-teal-400 uppercase tracking-wider">
                    Sistem
                </div>

                <a href="<?= base_url('admin/settings') ?>" 
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-medium transition-all <?= ($segment1 == 'settings') ? 'bg-brand-teal-600 text-white shadow-md' : 'text-slate-300 hover:bg-white/10 hover:text-white' ?>">
                    <svg class="w-5 h-5 text-brand-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Pengaturan Situs (Settings)</span>
                </a>
            </nav>

            <!-- Sidebar Footer -->
            <div class="p-4 border-t border-brand-teal-800/60 bg-brand-teal-950/40">
                <a href="<?= base_url() ?>" target="_blank" class="flex items-center justify-center space-x-2 w-full py-2 px-3 rounded-lg text-xs font-medium text-brand-lime-300 hover:text-white bg-white/5 hover:bg-white/10 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
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
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
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
                    <div class="hidden md:flex items-center space-x-2 text-xs text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-full font-medium">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Sistem Aktif (Live)</span>
                    </div>

                    <div class="relative">
                        <button @click="userMenu = !userMenu" @click.outside="userMenu = false" class="flex items-center space-x-3 p-1.5 rounded-xl hover:bg-slate-100 transition focus:outline-none">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-brand-teal-700 to-brand-teal-500 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                <?= strtoupper(substr(auth()->user()->username ?? 'A', 0, 1)) ?>
                            </div>
                            <div class="text-left hidden sm:block">
                                <div class="text-xs font-bold text-slate-800 leading-tight"><?= esc(auth()->user()->username ?? 'Administrator') ?></div>
                                <div class="text-[11px] text-slate-400">Super Admin SIRS</div>
                            </div>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="userMenu" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50"
                             style="display: none;">
                            <div class="px-4 py-2 border-b border-slate-100 text-xs">
                                <p class="text-slate-400">Login sebagai</p>
                                <p class="font-bold text-slate-800 truncate"><?= esc(auth()->user()->email ?? 'admin@karadi.co.id') ?></p>
                            </div>
                            <a href="<?= base_url('admin/settings') ?>" class="flex items-center space-x-2 px-4 py-2 text-xs text-slate-700 hover:bg-slate-50 font-medium">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                                <span>Pengaturan Situs</span>
                            </a>
                            <div class="border-t border-slate-100 my-1"></div>
                            <a href="<?= base_url('logout') ?>" class="flex items-center space-x-2 px-4 py-2 text-xs text-rose-600 hover:bg-rose-50 font-medium">
                                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
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
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
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
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                <?php endif; ?>

                <?= $this->renderSection('content') ?>
            </main>

            <!-- Admin Footer -->
            <footer class="bg-white border-t border-slate-200 py-4 px-8 text-center text-xs text-slate-400">
                &copy; <?= date('Y') ?> Divisi SIRS RSUP Dr. Kariadi Semarang. Powered by CodeIgniter 4 & Shield.
            </footer>
        </div>
    </div>

    <?= $this->renderSection('scripts') ?>
</body>
</html>
