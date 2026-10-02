<!-- navbar publik -->

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="<?= base_url('images/logo.png') ?>">
    <title><?= $this->renderSection('title', site_setting('site_name', 'Divisi SIRS RSUP Dr. Kariadi')) ?> | RSUP Dr. Kariadi Semarang</title>
    
    <meta name="description" content="<?= $this->renderSection('meta_description', site_setting('site_tagline', 'Instalasi SIRS RSUP Dr. Kariadi Semarang')) ?>">
    <meta name="theme-color" content="#4ad9d4">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lenis@1.1.18/dist/lenis.css">
    
    <!-- Alpine.js -->
    <script defer src="<?= base_url('js/alpine.min.js') ?>"></script>
    
    <?= $this->renderSection('styles') ?>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased flex flex-col min-h-screen" 
      x-data="{ mobileNav: false, isScrolled: false }" 
      @scroll.window="isScrolled = (window.pageYOffset > 20)">

    <!-- Top Bar: Hotline Darurat -->
    <div class="bg-brand-emerald-950 text-white text-xs py-2.5 px-4 sm:px-8 border-b border-brand-emerald-900/80">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-2">
            <div class="flex items-center space-x-4 text-slate-300 text-[11px]">
                <span class="flex items-center space-x-1.5">
                    <svg class="w-3.5 h-3.5 text-brand-mint-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    <span>Helpdesk IT: <strong><?= esc(site_setting('contact_phone', '(024) 8413476 Ext. 2100')) ?></strong></span>
                </span>
                <span class="hidden lg:inline text-brand-mint-300 font-semibold text-[11px]">#KariadiAjaYuk</span>
                <span class="hidden sm:inline text-brand-emerald-800">|</span>
                <span class="flex items-center space-x-1.5 text-brand-mint-300 font-medium">
                    <span class="w-2 h-2 rounded-full bg-brand-mint-400 animate-ping"></span>
                    <span>Support 24/7</span>
                </span>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header :class="isScrolled
                ? 'public-navbar--floating mx-3 sm:mx-6 lg:mx-10 mt-3 bg-white/75 backdrop-blur-md shadow-xl shadow-slate-900/10 border border-white/50 py-3'
                : 'public-navbar--top bg-white py-4 border-b border-slate-100'"
            class="public-navbar sticky top-0 z-40 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="<?= base_url() ?>" class="flex items-center space-x-3 group">
                <div class="w-12 h-12 rounded-2xl bg-white p-1 border border-brand-emerald-100 shadow-md shadow-brand-emerald-500/15 group-hover:scale-105 group-hover:shadow-brand-emerald-500/30 transition duration-300 flex items-center justify-center overflow-hidden shrink-0">
                    <img src="<?= base_url('images/logo.png') ?>" alt="Logo SIRS RSUP Dr. Kariadi" class="w-full h-full object-contain">
                </div>
                <div>
                    <div class="flex items-center space-x-1.5">
                        <span class="text-base sm:text-lg font-black text-slate-900 tracking-tight font-heading group-hover:text-brand-emerald-700 transition">INSTALASI SIRS</span>
                    </div>
                    <p class="text-[11px] font-bold tracking-wider text-brand-emerald-700">RSUP Dr. KARIADI SEMARANG</p>
                </div>
            </a>

            <!-- Desktop Navigation Links -->
            <nav class="hidden lg:flex items-center space-x-1 font-medium text-sm text-slate-600">
                <a href="<?= base_url() ?>" class="px-3.5 py-2 rounded-xl hover:text-brand-emerald-700 hover:bg-brand-emerald-50/70 transition font-bold text-brand-emerald-800 bg-brand-emerald-50/40">Beranda</a>
                <a href="<?= base_url('#profil') ?>" class="px-3.5 py-2 rounded-xl hover:text-brand-emerald-700 hover:bg-brand-emerald-50/70 transition">Profil & Visi</a>
                <a href="<?= base_url('#organisasi') ?>" class="px-3.5 py-2 rounded-xl hover:text-brand-emerald-700 hover:bg-brand-emerald-50/70 transition">Struktur</a>
                <a href="<?= base_url('#layanan') ?>" class="px-3.5 py-2 rounded-xl hover:text-brand-emerald-700 hover:bg-brand-emerald-50/70 transition">Layanan IT</a>
                <a href="<?= base_url('#kis') ?>" class="px-3.5 py-2 rounded-xl hover:text-brand-emerald-700 hover:bg-brand-emerald-50/70 transition">Aplikasi KIS</a>
                <a href="<?= base_url('#berita') ?>" class="px-3.5 py-2 rounded-xl hover:text-brand-emerald-700 hover:bg-brand-emerald-50/70 transition">Berita</a>
                <a href="<?= base_url('#galeri') ?>" class="px-3.5 py-2 rounded-xl hover:text-brand-emerald-700 hover:bg-brand-emerald-50/70 transition flex items-center space-x-1 font-semibold text-brand-teal-700">
                    <svg class="w-4 h-4 text-brand-mint-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Galeri</span>
                </a>
            </nav>

            <!-- Mobile Hamburger Toggle -->
            <button @click="mobileNav = !mobileNav" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 focus:outline-none transition">
                <svg x-show="!mobileNav" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="mobileNav" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Mobile Drawer Navigation -->
        <div x-show="mobileNav" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-3"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-3"
             class="lg:hidden bg-white border-b border-slate-200 px-6 py-5 shadow-2xl space-y-3"
             style="display: none;">
            <nav class="flex flex-col space-y-2 text-sm font-medium text-slate-700">
                <a @click="mobileNav = false" href="<?= base_url() ?>" class="px-3.5 py-2.5 rounded-xl hover:bg-brand-emerald-50 hover:text-brand-emerald-700 font-bold text-brand-emerald-800">Beranda</a>
                <a @click="mobileNav = false" href="<?= base_url('#profil') ?>" class="px-3.5 py-2.5 rounded-xl hover:bg-brand-emerald-50 hover:text-brand-emerald-700">Profil & Visi</a>
                <a @click="mobileNav = false" href="<?= base_url('#organisasi') ?>" class="px-3.5 py-2.5 rounded-xl hover:bg-brand-emerald-50 hover:text-brand-emerald-700">Struktur Organisasi</a>
                <a @click="mobileNav = false" href="<?= base_url('#layanan') ?>" class="px-3.5 py-2.5 rounded-xl hover:bg-brand-emerald-50 hover:text-brand-emerald-700">Layanan IT</a>
                <a @click="mobileNav = false" href="<?= base_url('#kis') ?>" class="px-3.5 py-2.5 rounded-xl hover:bg-brand-emerald-50 hover:text-brand-emerald-700">Aplikasi KIS</a>
                <a @click="mobileNav = false" href="<?= base_url('#berita') ?>" class="px-3.5 py-2.5 rounded-xl hover:bg-brand-emerald-50 hover:text-brand-emerald-700">Berita & Informasi</a>
                <a @click="mobileNav = false" href="<?= base_url('#galeri') ?>" class="px-3.5 py-2.5 rounded-xl hover:bg-brand-emerald-50 hover:text-brand-emerald-700 font-semibold text-brand-teal-700">Galeri Kegiatan</a>
            </nav>
            <div class="pt-3 border-t border-slate-100">
                <a href="<?= esc(site_setting('manpro_url', 'https://manpro.rskariadi.id')) ?>" 
                   target="_blank" 
                   class="flex items-center justify-center space-x-2 w-full bg-gradient-to-r from-brand-emerald-600 to-brand-teal-600 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-brand-emerald-500/20 text-sm">
                    <span>Akses Portal Manpro</span>
                    <svg class="w-4 h-4 text-brand-mint-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Dynamic Content -->
    <main class="flex-1">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-300 pt-16 pb-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
            <!-- Col 1: Brand Info -->
            <div class="space-y-4 lg:col-span-1">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-mint-400 via-brand-emerald-500 to-brand-teal-500 flex items-center justify-center text-slate-950 font-black font-heading shadow-md shadow-brand-emerald-500/20">
                        S
                    </div>
                    <div>
                        <h4 class="text-white font-bold font-heading text-base leading-tight">INSTALASI SIRS</h4>
                        <p class="text-[11px] text-brand-emerald-400 font-semibold">RSUP Dr. Kariadi Semarang</p>
                    </div>
                </div>
                <p class="text-xs text-slate-400 leading-relaxed">
                    <?= esc(site_setting('about_sirs', 'Pusat tata kelola teknologi informasi, pengembangan SIMRS terpadu, dan layanan teknis 24/7 RSUP Dr. Kariadi.')) ?>
                </p>
            </div>

            <!-- Col 2: Layanan IT & Galeri -->
            <div class="space-y-3">
                <h5 class="text-white font-bold font-heading text-sm uppercase tracking-wider text-brand-mint-400">Navigasi Utama</h5>
                <ul class="text-xs space-y-2 text-slate-400">
                    <li><a href="<?= base_url('#layanan') ?>" class="hover:text-brand-mint-300 transition">Hardware & Infrastruktur</a></li>
                    <li><a href="<?= base_url('#layanan') ?>" class="hover:text-brand-mint-300 transition">Software & SIMRS (KIS)</a></li>
                    <li><a href="<?= base_url('#layanan') ?>" class="hover:text-brand-mint-300 transition">Jaringan & Keamanan TI</a></li>
                    <li><a href="<?= base_url('#layanan') ?>" class="hover:text-brand-mint-300 transition">Technical Support 24 Jam</a></li>
                    <li><a href="<?= base_url('#galeri') ?>" class="hover:text-brand-mint-300 transition font-semibold text-brand-emerald-400">Galeri Dokumentasi &rarr;</a></li>
                    <li><a href="<?= esc(site_setting('manpro_url', 'https://manpro.rskariadi.id')) ?>" target="_blank" class="hover:text-brand-mint-300 transition font-semibold text-brand-mint-300">Sistem Manpro Pelaporan &rarr;</a></li>
                </ul>
            </div>

            <!-- Col 3: Kontak & Lokasi -->
            <div class="space-y-3">
                <h5 class="text-white font-bold font-heading text-sm uppercase tracking-wider text-brand-mint-400">Kontak & Lokasi</h5>
                <ul class="text-xs space-y-2.5 text-slate-400">
                    <li class="flex items-start space-x-2">
                        <svg class="w-4 h-4 text-brand-emerald-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span><?= esc(site_setting('hospital_address', 'Jl. Dr. Sutomo No.16, Randusari, Semarang 50244')) ?></span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-brand-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span><?= esc(site_setting('contact_email', 'sirs@rskariadi.id')) ?></span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-brand-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span><?= esc(site_setting('contact_phone', '(024) 8413476 Ext. 2100')) ?></span>
                    </li>
                </ul>
            </div>

            <!-- Col 4: Jam Layanan -->
            <div class="space-y-3">
                <h5 class="text-white font-bold font-heading text-sm uppercase tracking-wider text-brand-mint-400">Jam Layanan</h5>
                <div class="bg-slate-800/90 p-4 rounded-2xl border border-slate-700/60 space-y-2 text-xs">
                    <p class="text-slate-300 font-medium"><?= esc(site_setting('service_hours', 'Senin - Jumat: 07.30 - 16.00 WIB')) ?></p>
                    <div class="pt-2 border-t border-slate-700 flex items-center space-x-2 text-brand-mint-400 font-semibold">
                        <span class="w-2 h-2 rounded-full bg-brand-mint-400 animate-pulse"></span>
                        <span>Technical Support Standby 24/7</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="max-w-7xl mx-auto px-4 sm:px-8 mt-12 pt-6 border-t border-slate-800/80 flex flex-col sm:flex-row justify-between items-center text-xs text-slate-500 gap-3">
            <p>&copy; <?= date('Y') ?> Instalasi SIRS RSUP Dr. Kariadi Semarang. Hak Cipta Dilindungi Undang-Undang.</p>
            <div class="flex items-center space-x-4">
                <a href="<?= base_url('login') ?>" class="text-slate-500 hover:text-brand-emerald-400 transition">Admin Login</a>
            </div>
        </div>
    </footer>

    <?= $this->renderSection('scripts') ?>

    <script src="https://cdn.jsdelivr.net/npm/lenis@1.1.18/dist/lenis.min.js"></script>
    <script>
        (() => {
            if (typeof window.Lenis !== 'function') {
                return;
            }

            const lenis = new window.Lenis({
                duration: 1.6,
                smoothWheel: true,
                wheelMultiplier: 0.55,
                touchMultiplier: 1.5,
                easing: (time) => Math.min(1, 1.001 - Math.pow(2, -10 * time)),
                autoRaf: false,
            });

            if (window.gsap?.ticker && window.ScrollTrigger) {
                window.gsap.registerPlugin(window.ScrollTrigger);
                lenis.on('scroll', () => window.ScrollTrigger.update());
                window.gsap.ticker.add((time) => lenis.raf(time * 1000));
                window.gsap.ticker.lagSmoothing(0);
                return;
            }

            if (window.ScrollTrigger) {
                lenis.on('scroll', () => window.ScrollTrigger.update());
            }

            const raf = (time) => {
                lenis.raf(time);
                window.requestAnimationFrame(raf);
            };

            window.requestAnimationFrame(raf);
        })();
    </script>
</body>
</html>
