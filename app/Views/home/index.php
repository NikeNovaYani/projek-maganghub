<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<?php
// Ambil daftar foto background hero dari site_settings (hero_bg_images)
$heroBgSetting = site_setting('hero_bg_images', '');
$heroImages = array_filter(array_map('trim', explode(',', $heroBgSetting)));
if (empty($heroImages)) {
    // Fallback default sample hero images (interior rumah sakit & data center)
    $heroImages = [
        'https://images.unsplash.com/photo-1519491268859-84d2ef372743?auto=format&fit=crop&w=1920&q=80',
        'https://images.unsplash.com/photo-1551076805-e2b08c58b580?auto=format&fit=crop&w=1920&q=80',
        'https://images.unsplash.com/photo-1587815009596-4ab26b810db2?auto=format&fit=crop&w=1920&q=80'
    ];
}
$heroJSON = json_encode(array_values($heroImages));
?>

<div class="hero-parallax-stack">
    <!-- HERO SECTION WITH DYNAMIC SLIDESHOW BACKGROUND -->
    <section id="hero" x-data="heroSlideshow()" data-hero-section
        class="hero-sticky relative min-h-screen md:h-screen bg-slate-100 text-white pt-20 pb-28 sm:pb-42 overflow-hidden">

        <!-- Slideshow Background Images with Smooth Zoom-In (Ken Burns) & Fade Transition -->
        <div class="absolute inset-0 -z-0 overflow-hidden pointer-events-none">
            <template x-for="(img, index) in slides" :key="index">
                <div x-show="current === index"
                    x-transition:enter="transition opacity duration-1000 ease-out"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition opacity duration-1000 ease-in"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="absolute inset-0">
                    <img :src="img.startsWith('http') ? img : '<?= base_url() ?>' + img"
                        class="hero-slide-image w-full h-full object-cover"
                        alt="Hero Background Slide">
                </div>
            </template>
        </div>

        <!-- UBAH WARNA/OPASITAS HERO DI resources/css/app.css: blok .hero-sticky -->
        <div class="hero-color-overlay absolute inset-0 pointer-events-none"></div>
        <div class="hero-bottom-overlay absolute inset-0 pointer-events-none"></div>
        <div data-hero-dimmer class="absolute inset-0 z-[1] bg-slate-950 opacity-0 pointer-events-none"></div>

        <!-- Glowing aura gradients -->
        <div class="absolute -top-32 -left-32 w-[32rem] h-[32rem] bg-brand-mint-500/20 rounded-full blur-2xl animate-pulse-slow"></div>
        <div class="absolute top-1/3 -right-32 w-[32rem] h-[32rem] bg-brand-teal-500/20 rounded-full blur-2xl animate-pulse-slow" style="animation-delay: 2s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                    <div class="space-y-4">

                        <!-- Hero Typography with Glowing Burst Backdrop -->
                        <div id="hero-text-block" class="relative py-1 select-none inline-block">
                            <!-- Subtle radial glow behind ZER0% -->
                            <div class="absolute -top-10 left-1/4 w-48 h-48 bg-red-500/20 rounded-full blur-2xl pointer-events-none"></div>

                            <div class="font-heading font-black text-white leading-none tracking-tight">
                                <div class="text-2xl sm:text-4xl lg:text-5xl font-extrabold tracking-[0.2em] text-slate-100/90 mb-1 sm:mb-2 drop-shadow-sm">
                                    OPERATION
                                </div>

                                <div class="text-6xl sm:text-8xl lg:text-9xl font-black flex items-center justify-center lg:justify-start space-x-1 my-1">
                                    <span class="tracking-wider text-white">ZER</span>

                                    <!-- Stylized Glowing Red 0% -->
                                    <span class="relative inline-flex items-baseline group cursor-default transition transform hover:scale-105 duration-300">
                                        <span class="text-transparent bg-clip-text bg-gradient-to-b from-red-400 via-red-500 to-rose-600 font-black drop-shadow-[0_6px_26px_rgba(239,68,68,0.7)]">
                                            0
                                        </span>
                                        <span class="text-red-400 text-[0.45em] font-black absolute bottom-0 sm:bottom-1 -right-3.5 sm:-right-5 leading-none select-none drop-shadow-[0_2px_8px_rgba(239,68,68,0.8)]">%</span>
                                    </span>

                                    <span class="w-4 sm:w-6 inline-block"></span>
                                </div>

                                <div class="text-4xl sm:text-6xl lg:text-7xl font-black tracking-wider text-slate-100 mt-2 sm:mt-3">
                                    COMPLAIN
                                </div>
                            </div>
                        </div>

                        <!-- Infinite Smooth Marquee Ticker - Lebar dijimati hanya segaris dengan teks -->
                        <?php
                        $punchlines = array_filter(array_map('trim', explode('|', site_setting('site_tagline', 'Zero Downtime Server & Data Center 24/7|Zero Error Integrasi SIMRS & SatuSehat|Zero Delay Penanganan Helpdesk Medis|100% Keandalan Komputasi RSUP Dr. Kariadi'))));
                        if (empty($punchlines)) {
                            $punchlines = ['Zero Downtime Server & Data Center 24/7', 'Zero Error Integrasi SIMRS & SatuSehat', 'Zero Delay Penanganan Helpdesk Medis', '100% Keandalan Komputasi RSUP Dr. Kariadi'];
                        }
                        ?>
                        <div class="overflow-hidden relative w-full max-w-lg lg:max-w-xl bg-white/5 border border-white/10 rounded-2xl backdrop-blur-md py-2.5 mx-auto lg:mx-0">
                            <!-- Gradient fade masks -->
                            <div class="absolute left-0 top-0 bottom-0 w-8 bg-gradient-to-r from-brand-emerald-950/90 to-transparent z-10 pointer-events-none"></div>
                            <div class="absolute right-0 top-0 bottom-0 w-8 bg-gradient-to-l from-brand-emerald-950/90 to-transparent z-10 pointer-events-none"></div>

                            <div class="animate-marquee-smooth flex items-center space-x-6 text-xs sm:text-sm font-semibold text-brand-mint-300 select-none">
                                <!-- Track 1 -->
                                <?php foreach ($punchlines as $punchline): ?>
                                    <span class="inline-flex items-center space-x-2 whitespace-nowrap">
                                        <span class="w-1.5 h-1.5 rounded-full bg-brand-mint-400"></span>
                                        <span><?= esc($punchline) ?></span>
                                    </span>
                                    <span class="text-white/30">ï¿½&nbsp;</span>
                                <?php endforeach; ?>

                                <!-- Track 2 (Replica) -->
                                <?php foreach ($punchlines as $punchline): ?>
                                    <span class="inline-flex items-center space-x-2 whitespace-nowrap">
                                        <span class="w-1.5 h-1.5 rounded-full bg-brand-mint-400"></span>
                                        <span><?= esc($punchline) ?></span>
                                    </span>
                                    <span class="text-white/30">ï¿½&nbsp;</span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- CTAs -->
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-3">
                        <a href="<?= esc(site_setting('manpro_url', 'https://manpro.rskariadi.id')) ?>"
                            target="_blank"
                            class="w-full sm:w-auto inline-flex items-center justify-center space-x-3 bg-gradient-to-r from-brand-mint-400 via-brand-emerald-400 to-brand-teal-400 hover:from-brand-mint-300 hover:to-brand-teal-300 text-slate-950 font-black text-sm px-8 py-4 rounded-2xl shadow-xl shadow-brand-emerald-500/25 hover:scale-[1.03] active:scale-[0.98] transition duration-200">
                            <span>Lapor Kendala (Manpro)</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                        <a href="#layanan-it"
                            class="w-full sm:w-auto inline-flex items-center justify-center space-x-2 bg-white/10 hover:bg-white/20 text-white font-semibold text-sm px-7 py-4 rounded-2xl backdrop-blur-md border border-white/15 transition duration-200">
                            <span>Jelajahi Layanan SIRS</span>
                        </a>
                    </div>

                    <!-- Highlight Badges -->
                    <div class="pt-6 grid grid-cols-3 gap-4 border-t border-brand-emerald-800/80 max-w-lg mx-auto lg:mx-0">
                        <div class="text-center lg:text-left">
                            <div class="text-2xl sm:text-3xl font-black font-heading text-brand-mint-300">24/7</div>
                            <div class="text-[11px] text-slate-300 font-medium">Technical Support</div>
                        </div>
                        <div class="text-center lg:text-left">
                            <div class="text-2xl sm:text-3xl font-black font-heading text-brand-mint-300">100%</div>
                            <div class="text-[11px] text-slate-300 font-medium">Integrasi SatuSehat</div>
                        </div>
                        <div class="text-center lg:text-left">
                            <div class="text-2xl sm:text-3xl font-black font-heading text-brand-mint-300">KIS</div>
                            <div class="text-[11px] text-slate-300 font-medium">SIMRS Terpusat</div>
                        </div>
                    </div>
                </div>

                <!-- Right: Visual Card with Floating Animation -->
                <div class="lg:col-span-5 animate-float">
                    <div class="relative bg-white/10 backdrop-blur-xl rounded-3xl p-6 sm:p-7 border border-white/20 shadow-2xl shadow-brand-emerald-950/50 space-y-5">
                        <!-- Header Card -->
                        <div class="flex items-center justify-between pb-4 border-b border-white/15">
                            <div class="flex items-center space-x-2.5">
                                <div class="w-3.5 h-3.5 rounded-full" style="background-color: #04877e;"></div>
                                <div class="w-3.5 h-3.5 rounded-full" style="background-color: #00a99d;"></div>
                                <div class="w-3.5 h-3.5 rounded-full" style="background-color: #70d9ff;"></div>
                            </div>
                        </div>

                        <!-- Highlights -->
                        <div class="space-y-3 text-xs">
                            <div class="p-3.5 rounded-2xl bg-white/10 border border-white/10 flex items-center space-x-3.5 hover:bg-white/15 transition">
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-brand-mint-400 to-brand-teal-500 flex items-center justify-center text-slate-950 font-black shrink-0">1</div>
                                <div>
                                    <div class="font-bold text-white text-sm">Hardware</div>
                                    <div class="text-slate-200 text-[11px]">Workstation medis, printer resep, intranet klinis</div>
                                </div>
                            </div>
                            <div class="p-3.5 rounded-2xl bg-white/10 border border-white/10 flex items-center space-x-3.5 hover:bg-white/15 transition">
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-brand-mint-400 to-brand-teal-500 flex items-center justify-center text-slate-950 font-black shrink-0">2</div>
                                <div>
                                    <div class="font-bold text-white text-sm">Software</div>
                                    <div class="text-slate-200 text-[11px]">Rekam medis elektronik, billing kasir, farmasi, lab</div>
                                </div>
                            </div>
                            <div class="p-3.5 rounded-2xl bg-white/10 border border-white/10 flex items-center space-x-3.5 hover:bg-white/15 transition">
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-brand-mint-400 to-brand-teal-500 flex items-center justify-center text-slate-950 font-black shrink-0">3</div>
                                <div>
                                    <div class="font-bold text-white text-sm">Penunjang TI</div>
                                    <div class="text-slate-200 text-[11px]">Rekam medis elektronik, billing kasir, farmasi, lab</div>
                                </div>
                            </div>
                            <div class="p-3.5 rounded-2xl bg-white/10 border border-white/10 flex items-center space-x-3.5 hover:bg-white/15 transition">
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-brand-mint-400 to-brand-teal-500 flex items-center justify-center text-slate-950 font-black shrink-0">4</div>
                                <div>
                                    <div class="font-bold text-white text-sm">Service Desk & Teknisi</div>
                                    <div class="text-slate-200 text-[11px]">Hotline on-call Ext. 2100 & tiket pelaporan Manpro</div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-2">
                            <a href="<?= esc(site_setting('manpro_url', 'https://manpro.rskariadi.id')) ?>" target="_blank" class="w-full flex items-center justify-center space-x-2 py-3.5 bg-gradient-to-r from-brand-emerald-600 to-brand-teal-600 hover:from-brand-emerald-500 hover:to-brand-teal-500 text-white rounded-xl font-bold text-xs transition duration-200 shadow-md">
                                <span>Buka Sistem Pelaporan Manpro &rarr;</span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION: PROFIL & VISI MISI -->
    <?php
    $visionText = trim((string) ($visi ?? site_setting('visi_sirs', '')));
    $missionText = trim((string) ($misi ?? site_setting('misi_sirs', '')));
    $missionLines = preg_split('/\r\n|\r|\n/', $missionText);

    $missionItems = array_values(
        array_filter(
            array_map(
                static function ($line) {
                    return trim(
                        (string) preg_replace(
                            '/^\d+[.)]\s*/',
                            '',
                            $line
                        )
                    );
                },
                $missionLines
            )
        )
    );
    $renderRevealWords = static function (string $text): void {
        $words = preg_split('/\s+/', trim($text), -1, PREG_SPLIT_NO_EMPTY);
        foreach ($words as $index => $word) {
            echo '<span class="reveal-word" style="--word-index:' . $index . '">' . esc($word) . '</span> ';
        }
    };
    ?>
    <div class="curtain-transition" data-curtain-transition>
        <section id="profil" data-vision-panel data-vision-reveal class="vision-panel py-24 bg-white relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="reveal-text text-3xl sm:text-4xl font-extrabold font-heading text-slate-900 mt-1"><?php $renderRevealWords('Profil Instalasi SIRS'); ?></h2>
                    <p class="reveal-text text-slate-600 mt-1 text-sm sm:text-base leading-relaxed">
                        <?php $renderRevealWords((string) site_setting('about_sirs', '')); ?>
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Visi -->
                    <div class="vision-mission-card reveal-card p-6 sm:p-10 rounded-3xl hover:shadow-xl hover:shadow-brand-emerald-500/10 transition duration-300" data-reveal-card style="--card-delay: 0ms">
                        <!-- UBAH LAYOUT VISI DI BLOK flex: logo kiri, teks kanan. -->
                        <div class="flex items-start gap-5">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-brand-emerald-700 to-brand-teal-600 text-brand-mint-200 flex items-center justify-center font-bold text-xl shadow-lg shadow-brand-emerald-700/25 shrink-0">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <h3 class="reveal-text text-2xl font-bold font-heading text-slate-900 mb-3"><?php $renderRevealWords('Visi'); ?></h3>
                                <p class="reveal-text text-sm sm:text-base text-slate-600 leading-relaxed">
                                    <?php $renderRevealWords($visionText); ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Misi -->
                    <div class="vision-mission-card reveal-card p-8 sm:p-10 rounded-3xl hover:shadow-xl hover:shadow-brand-teal-500/10 transition duration-300" data-reveal-card style="--card-delay: 140ms">
                        <!-- UBAH LAYOUT MISI DI BLOK flex: logo kiri, daftar teks kanan. -->
                        <div class="flex items-start gap-5">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-brand-mint-500 to-brand-emerald-600 text-slate-950 flex items-center justify-center font-bold text-xl shadow-lg shadow-brand-mint-500/25 shrink-0">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <h3 class="reveal-text text-2xl font-bold font-heading text-slate-900 mb-3"><?php $renderRevealWords('Misi'); ?></h3>
                                <?php if (! empty($missionItems)): ?>

                                    <ol class="mission-list">

                                        <?php foreach ($missionItems as $itemIndex => $missionItem): ?>

                                            <li
                                                class="mission-item reveal-text"
                                                style="--item-delay: <?= $itemIndex * 120 ?>ms">

                                                <span class="mission-number">
                                                    <?= $itemIndex + 1 ?>
                                                </span>

                                                <span class="mission-content">
                                                    <?php $renderRevealWords($missionItem); ?>
                                                </span>

                                            </li>

                                        <?php endforeach; ?>

                                    </ol>

                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="curtain-organization" data-curtain-organization>
            <?= view('sections/struktur', ['organisasiTree' => $organisasi]) ?>
        </div>
    </div>
</div>

<!-- SECTION: LAYANAN IT (4 KATEGORI) -->
<section id="layanan-it" class="relative z-20 pt-28 pb-24 sm:pt-36 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-8">
        <div class="services-scroll-stage" data-services-stage>
            <div class="services-scroll-pin">
                <div class="services-heading text-center max-w-2xl mx-auto">
                    <h2 class="text-3xl sm:text-4xl font-extrabold font-heading text-slate-900">Layanan Teknologi Informasi</h2>
                    <p class="text-slate-600 mt-3 text-sm">4 Pilar layanan komputasi dan asistensi teknologi bagi seluruh civitas RSUP Dr. Kariadi</p>
                </div>
                <div class="services-card-track" data-services-track>
                    <?php foreach ($layananKategori as $serviceIndex => $kat): ?>
                        <article class="service-card bg-slate-50 rounded-3xl p-6 sm:p-7 border border-slate-200/80 hover:border-brand-emerald-400 hover:shadow-xl hover:shadow-brand-emerald-500/10 transition duration-300 flex flex-col justify-between group" data-service-slide style="--service-offset: <?= $serviceIndex * 14 ?>px; --service-layer: <?= 20 - $serviceIndex ?>;">
                            <div>
                                <?php if (! empty($kat['gambar_kategori'])): ?>
                                    <?php $categoryIsVideo = strtolower(pathinfo($kat['gambar_kategori'], PATHINFO_EXTENSION)) === 'webm'; ?>
                                    <?php if ($categoryIsVideo): ?>
                                        <video src="<?= base_url('uploads/layanan/' . rawurlencode(basename($kat['gambar_kategori']))) ?>" aria-label="<?= esc($kat['nama']) ?>" autoplay muted loop playsinline class="mb-5 aspect-video w-full rounded-2xl object-cover"></video>
                                    <?php else: ?>
                                        <img src="<?= base_url('uploads/layanan/' . rawurlencode(basename($kat['gambar_kategori']))) ?>" alt="<?= esc($kat['nama']) ?>" loading="lazy" class="mb-5 aspect-video w-full rounded-2xl object-cover">
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="w-13 h-13 w-12 h-12 rounded-2xl bg-gradient-to-tr from-brand-emerald-700 to-brand-teal-700 text-brand-mint-300 flex items-center justify-center font-bold mb-5 shadow-lg shadow-brand-emerald-800/20 group-hover:scale-105 transition">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                    </div>
                                <?php endif; ?>
                                <h3 class="text-lg font-bold font-heading text-slate-900 mb-2 group-hover:text-brand-emerald-700 transition"><?= esc($kat['nama']) ?></h3>
                                <p class="text-xs text-slate-600 leading-relaxed mb-4"><?= esc($kat['deskripsi']) ?></p>

                                <!-- Sub Items -->
                                <?php if (!empty($itemsByKategori[$kat['id']])): ?>
                                    <div class="space-y-2 pt-4 border-t border-slate-200/80">
                                        <?php foreach ($itemsByKategori[$kat['id']] as $subItem): ?>
                                            <div class="flex items-start space-x-2 text-xs text-slate-700">
                                                <span class="text-brand-emerald-500 font-bold">&#8226;</span>
                                                <?php if (! empty($subItem['gambar_layanan'])): ?>
                                                    <?php $mediaType = strtolower(pathinfo($subItem['gambar_layanan'], PATHINFO_EXTENSION)) === 'webm' ? 'video' : 'image'; ?>
                                                    <button type="button" data-layanan-viewer
                                                        data-title="<?= esc($subItem['judul'], 'attr') ?>"
                                                        data-src="<?= esc(base_url('uploads/layanan/' . rawurlencode(basename($subItem['gambar_layanan']))), 'attr') ?>"
                                                        data-media-type="<?= $mediaType ?>"
                                                        class="text-left font-semibold text-brand-emerald-800 underline decoration-brand-emerald-300 underline-offset-2 hover:text-brand-teal-700 focus:outline-none focus:ring-2 focus:ring-brand-emerald-500">
                                                        <?= esc($subItem['judul']) ?>
                                                    </button>
                                                <?php else: ?>
                                                    <span><strong><?= esc($subItem['judul']) ?></strong></span>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="pt-6 mt-4 border-t border-slate-200/60">
                                <a href="<?= esc(site_setting('manpro_url', 'https://manpro.rskariadi.id')) ?>" target="_blank" class="inline-flex items-center space-x-1.5 text-xs font-bold text-brand-emerald-700 hover:text-brand-teal-700 transition">
                                    <span>Lapor kendala layanan ini</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="layanan-media-viewer" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/25 p-3 backdrop-blur-xl sm:p-6" role="dialog" aria-modal="true" aria-labelledby="layanan-viewer-title" hidden>
    <div class="flex h-[94vh] w-full max-w-7xl flex-col overflow-hidden rounded-2xl border border-white/70 bg-white/95 shadow-2xl">
        <div class="flex shrink-0 items-center justify-between gap-3 border-b border-slate-200 px-4 py-3 text-slate-800 sm:px-5">
            <h2 id="layanan-viewer-title" class="min-w-0 truncate text-sm font-semibold sm:text-base"></h2>
            <div class="flex shrink-0 items-center gap-1.5">
                <button type="button" data-viewer-zoom-out aria-label="Zoom out" title="Zoom out" class="grid h-10 w-10 place-items-center rounded-lg text-xl hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-teal-600">−</button>
                <button type="button" data-viewer-reset aria-label="Reset zoom" title="Reset zoom" class="h-10 rounded-lg px-2 text-xs font-semibold hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-teal-600 sm:px-3">Reset</button>
                <button type="button" data-viewer-zoom-in aria-label="Zoom in" title="Zoom in" class="grid h-10 w-10 place-items-center rounded-lg text-xl hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-teal-600">+</button>
                <button type="button" data-viewer-close aria-label="Tutup viewer" class="ml-1 grid h-10 w-10 place-items-center rounded-lg hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-teal-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 6l12 12M18 6L6 18" />
                    </svg>
                </button>
            </div>
        </div>
        <div data-viewer-stage class="flex min-h-0 flex-1 items-center justify-center overflow-auto bg-slate-100/80 p-2 sm:p-6">
            <img data-viewer-image alt="" class="hidden max-h-[86vh] max-w-full origin-center object-contain transition-transform duration-150">
            <video data-viewer-video controls playsinline class="hidden max-h-[86vh] max-w-full origin-center object-contain transition-transform duration-150"></video>
        </div>
    </div>
</div>

<!-- SECTION: KARIADI INFORMATION SYSTEM (KIS) -->
<!-- SECTION: KARIADI INFORMATION SYSTEM (KIS) -->
<?php
$kisKategori = $kisKategori ?? [];
$kisKlasLabels = ['pmk82' => 'PMK 82', 'diluar_pmk' => 'Diluar PMK', 'eksternal' => 'Integrasi Eksternal'];
$kisKlasColors = ['pmk82' => '#22c55e', 'diluar_pmk' => '#3b82f6', 'eksternal' => '#f59e0b'];
$kisVariants = ['bento', 'carousel', 'nodes', 'chips'];

$kisAllApps = [];
foreach ($kisKategori as $kisKat) {
    foreach ($kisKat['apps'] as $kisApp) {
        $kisAllApps[] = $kisApp;
    }
}
$kisKatCount = count($kisKategori);

// Label klasifikasi dan akses internal
$kisTags = static function (array $app) use ($kisKlasLabels, $kisKlasColors): void {
    $klas = (string) ($app['klasifikasi'] ?? '');
    $internal = (int) ($app['akses_internal'] ?? 0) === 1;
    if (! isset($kisKlasLabels[$klas]) && ! $internal) {
        return;
    }
?>
    <div class="kis-tags">
        <?php if (isset($kisKlasLabels[$klas])): ?>
            <span class="kis-pill"><i style="--dot: <?= esc($kisKlasColors[$klas], 'attr') ?>"></i><?= esc($kisKlasLabels[$klas]) ?></span>
        <?php endif; ?>
        <?php if ($internal): ?>
            <span class="kis-pill kis-pill--internal">
                <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                Akses internal
            </span>
        <?php endif; ?>
    </div>
<?php
};

// Kartu penuh (gaya bento, carousel, dan node). Kelas kartu lama dipertahankan.
$kisCard = static function (array $app) use ($kisKlasLabels, $kisTags): void {
    $url = trim((string) ($app['url'] ?? ''));
    $hasLink = $url !== '';
    $klas = (string) ($app['klasifikasi'] ?? '');
    $search = mb_strtolower(trim((string) $app['nama_aplikasi'] . ' ' . (string) ($app['deskripsi_singkat'] ?? '')));
    $tag = $hasLink ? 'a' : 'div';
    $class = $hasLink
        ? 'group bg-white/5 hover:bg-white/10 p-6 rounded-3xl border border-white/10 hover:border-brand-mint-400/50 hover:shadow-xl hover:shadow-brand-emerald-500/10 transition duration-300 flex flex-col justify-between kis-fx'
        : 'bg-white/5 p-6 rounded-3xl border border-white/10 transition duration-300 flex flex-col justify-between kis-fx';
?>
    <<?= $tag ?> <?= $hasLink ? 'href="' . esc($url, 'attr') . '" target="_blank" rel="noopener noreferrer"' : '' ?> class="<?= $class ?>" data-kis-card data-klas="<?= esc($klas, 'attr') ?>" data-search="<?= esc($search, 'attr') ?>">
        <div>
            <div class="flex items-start justify-between gap-3 mb-4">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-brand-mint-400 to-brand-teal-500 flex items-center justify-center text-slate-950 font-bold group-hover:scale-105 transition shadow-md shadow-brand-mint-400/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                    </svg>
                </div>
                <?php $kisTags($app); ?>
            </div>
            <h3 class="text-base font-bold font-heading text-white group-hover:text-brand-mint-300 transition"><?= esc($app['nama_aplikasi']) ?></h3>
            <?php if (! empty($app['deskripsi_singkat'])): ?>
                <p class="text-xs text-slate-300 mt-2 leading-relaxed"><?= esc($app['deskripsi_singkat']) ?></p>
            <?php endif; ?>
        </div>
        <?php if ($hasLink): ?>
            <div class="mt-5 pt-3 border-t border-white/10 flex items-center justify-between text-xs text-brand-mint-300 group-hover:text-white">
                <span class="font-mono text-[11px] truncate mr-2"><?= esc($url) ?></span>
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
            </div>
        <?php endif; ?>
    </<?= $tag ?>>
<?php
};

// Chip ringkas (gaya Aplikasi Pendukung)
$kisChip = static function (array $app) use ($kisKlasColors): void {
    $url = trim((string) ($app['url'] ?? ''));
    $hasLink = $url !== '';
    $klas = (string) ($app['klasifikasi'] ?? '');
    $internal = (int) ($app['akses_internal'] ?? 0) === 1;
    $search = mb_strtolower(trim((string) $app['nama_aplikasi'] . ' ' . (string) ($app['deskripsi_singkat'] ?? '')));
    $tag = $hasLink ? 'a' : 'span';
?>
    <<?= $tag ?> <?= $hasLink ? 'href="' . esc($url, 'attr') . '" target="_blank" rel="noopener noreferrer"' : '' ?> class="kis-chip kis-fx" title="<?= esc((string) ($app['deskripsi_singkat'] ?? ''), 'attr') ?>" data-kis-card data-klas="<?= esc($klas, 'attr') ?>" data-search="<?= esc($search, 'attr') ?>">
        <?php if (isset($kisKlasColors[$klas])): ?><i class="kis-dot" style="--dot: <?= esc($kisKlasColors[$klas], 'attr') ?>"></i><?php endif; ?>
        <span><?= esc($app['nama_aplikasi']) ?></span>
        <?php if ($internal): ?>
            <span class="kis-chip__lock" title="Akses internal">
                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                Akses internal
            </span>
        <?php endif; ?>
        <?php if ($hasLink): ?>
            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
            </svg>
        <?php endif; ?>
    </<?= $tag ?>>
<?php
};
?>
<section id="kis" class="py-24 bg-gradient-to-br from-slate-950 via-brand-emerald-950 to-slate-950 text-white relative overflow-hidden">
    <style>
        /* ===== KIS: tambahan tampilan (kelas kartu lama tidak diubah) ===== */
        /* overflow: clip menggantikan overflow-hidden supaya rel navigasi bisa lengket (sticky) */
        #kis {
            overflow: clip;
            --kis-sticky-top: 5.5rem;
        }

        #kis [hidden] {
            display: none !important;
        }

        /* Hub */
        #kis .kis-hub {
            position: relative;
            max-width: 52rem;
            margin: 0 auto 3rem;
            aspect-ratio: 16 / 8;
        }

        #kis .kis-hub__lines {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
        }

        #kis .kis-hub__line {
            stroke: rgba(110, 231, 183, .3);
            stroke-width: 1.5;
            stroke-dasharray: 4 5;
            vector-effect: non-scaling-stroke;
            transition: stroke .3s ease;
        }

        #kis .kis-hub__line.is-on {
            stroke: rgba(110, 231, 183, .95);
            stroke-dasharray: none;
        }

        #kis .kis-hub__core {
            position: absolute;
            left: 50%;
            top: 50%;
            display: grid;
            place-items: center;
            width: 8.5rem;
            height: 8.5rem;
            padding: .5rem;
            text-align: center;
            transform: translate(-50%, -50%);
            border: 1px solid rgba(110, 231, 183, .35);
            border-radius: 9999px;
            background: radial-gradient(circle at 35% 30%, rgba(110, 231, 183, .35), rgba(15, 118, 110, .25) 60%, rgba(15, 23, 42, .6));
            box-shadow: 0 0 60px rgba(45, 212, 191, .18);
        }

        #kis .kis-hub__core strong {
            display: block;
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
            letter-spacing: .04em;
        }

        #kis .kis-hub__core small {
            display: block;
            margin-top: .25rem;
            font-size: .6rem;
            line-height: 1.3;
            color: #a7f3d0;
        }

        #kis .kis-hub__node {
            position: absolute;
            left: var(--x);
            top: var(--y);
            display: block;
            min-width: 9.5rem;
            padding: .7rem 1rem;
            text-align: center;
            transform: translate(-50%, -50%);
            border: 1px solid rgba(255, 255, 255, .14);
            border-radius: 1rem;
            background: rgba(255, 255, 255, .06);
            -webkit-backdrop-filter: blur(6px);
            backdrop-filter: blur(6px);
            transition: border-color .3s ease, background .3s ease, transform .3s ease;
        }

        #kis .kis-hub__node:hover,
        #kis .kis-hub__node:focus-visible {
            border-color: rgba(110, 231, 183, .6);
            background: rgba(255, 255, 255, .12);
            transform: translate(-50%, calc(-50% - 2px));
        }

        #kis .kis-hub__node b {
            display: block;
            font-size: .7rem;
            font-weight: 800;
            letter-spacing: .08em;
            color: #6ee7b7;
        }

        #kis .kis-hub__node span {
            display: block;
            font-size: .85rem;
            font-weight: 700;
            color: #fff;
        }

        #kis .kis-hub__node em {
            display: block;
            margin-top: .1rem;
            font-size: .68rem;
            font-style: normal;
            color: #94a3b8;
        }

        @media (max-width: 639px) {
            #kis .kis-hub {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: .75rem;
                aspect-ratio: auto;
            }

            #kis .kis-hub__lines {
                display: none;
            }

            #kis .kis-hub__core {
                position: static;
                grid-column: 1 / -1;
                width: 6.5rem;
                height: 6.5rem;
                margin: 0 auto;
                transform: none;
            }

            #kis .kis-hub__core strong {
                font-size: 1.5rem;
            }

            #kis .kis-hub__node {
                position: static;
                min-width: 0;
                transform: none;
            }

            #kis .kis-hub__node:hover,
            #kis .kis-hub__node:focus-visible {
                transform: none;
            }
        }

        /* Ticker */
        #kis .kis-ticker {
            overflow: hidden;
            margin-bottom: 3rem;
            padding: .9rem 0;
            border-block: 1px solid rgba(255, 255, 255, .08);
            -webkit-mask-image: linear-gradient(90deg, transparent, #000 12%, #000 88%, transparent);
            mask-image: linear-gradient(90deg, transparent, #000 12%, #000 88%, transparent);
        }

        #kis .kis-ticker__track {
            display: flex;
            width: max-content;
            animation: kis-marquee 90s linear infinite;
        }

        #kis .kis-ticker:hover .kis-ticker__track {
            animation-play-state: paused;
        }

        #kis .kis-ticker__track span {
            padding-right: 2.5rem;
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            white-space: nowrap;
            color: rgba(167, 243, 208, .5);
        }

        #kis .kis-ticker__track span::before {
            content: '\25C6';
            margin-right: 2.5rem;
            font-size: .5rem;
            vertical-align: middle;
            color: rgba(110, 231, 183, .35);
        }

        @keyframes kis-marquee {
            to {
                transform: translateX(-50%);
            }
        }

        /* Filter dan pencarian */
        #kis .kis-toolbar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: .75rem 1rem;
            margin-bottom: 1.25rem;
        }

        #kis .kis-filter {
            display: inline-flex;
            flex-wrap: wrap;
            align-items: center;
            gap: .4rem;
        }

        #kis .kis-filter__label {
            margin-right: .25rem;
            font-size: .68rem;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #64748b;
        }

        #kis .kis-filter button {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .4rem .85rem;
            font-size: .72rem;
            font-weight: 700;
            color: #cbd5e1;
            cursor: pointer;
            border: 1px solid rgba(255, 255, 255, .14);
            border-radius: 9999px;
            background: rgba(255, 255, 255, .04);
            transition: all .2s ease;
        }

        #kis .kis-filter button:hover {
            border-color: rgba(110, 231, 183, .5);
        }

        #kis .kis-filter button[aria-pressed="true"] {
            color: #022c22;
            border-color: #6ee7b7;
            background: #6ee7b7;
        }

        #kis .kis-search {
            width: 100%;
            max-width: 17rem;
            padding: .55rem 1rem;
            font-size: .8rem;
            color: #fff;
            border: 1px solid rgba(255, 255, 255, .16);
            border-radius: 9999px;
            background: rgba(255, 255, 255, .06);
            outline: none;
            transition: border-color .2s ease;
        }

        #kis .kis-search::placeholder {
            color: #94a3b8;
        }

        #kis .kis-search:focus {
            border-color: #6ee7b7;
        }

        #kis .kis-empty {
            padding: 3rem 1rem;
            text-align: center;
            font-size: .9rem;
            color: #94a3b8;
        }

        /* Rel navigasi lengket */
        #kis .kis-rail {
            position: sticky;
            top: var(--kis-sticky-top);
            z-index: 20;
            display: flex;
            gap: .35rem;
            width: max-content;
            max-width: 100%;
            margin: 0 auto 3rem;
            padding: .35rem;
            overflow-x: auto;
            scrollbar-width: none;
            border: 1px solid rgba(255, 255, 255, .12);
            border-radius: 9999px;
            background: rgba(2, 6, 23, .7);
            -webkit-backdrop-filter: blur(10px);
            backdrop-filter: blur(10px);
        }

        #kis .kis-rail::-webkit-scrollbar {
            display: none;
        }

        #kis .kis-rail a {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .45rem .95rem;
            font-size: .78rem;
            font-weight: 700;
            white-space: nowrap;
            color: #cbd5e1;
            border-radius: 9999px;
            transition: all .25s ease;
        }

        #kis .kis-rail a b {
            font-weight: 800;
            color: #6ee7b7;
        }

        #kis .kis-rail a:hover {
            color: #fff;
        }

        #kis .kis-rail a.is-active {
            color: #022c22;
            background: #6ee7b7;
        }

        #kis .kis-rail a.is-active b {
            color: #022c22;
        }

        /* Kategori */
        #kis .kis-cat {
            --kis-accent: #2dd4bf;
            margin-bottom: 5rem;
            scroll-margin-top: calc(var(--kis-sticky-top) + 4.5rem);
        }

        #kis .kis-cat--bento {
            --kis-accent: #2dd4bf;
        }

        #kis .kis-cat--carousel {
            --kis-accent: #38bdf8;
        }

        #kis .kis-cat--nodes {
            --kis-accent: #a78bfa;
        }

        #kis .kis-cat--chips {
            --kis-accent: #fb7185;
        }

        #kis .kis-cat__head {
            position: relative;
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            gap: .5rem 1.25rem;
            margin-bottom: 1.75rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px solid rgba(255, 255, 255, .1);
        }

        #kis .kis-cat__head::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -1px;
            width: 6rem;
            height: 2px;
            background: linear-gradient(90deg, var(--kis-accent), transparent);
        }

        #kis .kis-cat__num {
            font-size: clamp(3rem, 8vw, 5rem);
            font-weight: 800;
            line-height: .9;
            color: transparent;
            opacity: .6;
            -webkit-text-stroke: 1px var(--kis-accent);
        }

        #kis .kis-cat__title {
            flex: 1 1 16rem;
            min-width: 0;
        }

        #kis .kis-cat__count {
            padding: .3rem .8rem;
            font-size: .72rem;
            font-weight: 700;
            color: var(--kis-accent);
            border: 1px solid currentColor;
            border-radius: 9999px;
        }

        #kis .kis-cat__arrows {
            display: none;
            gap: .4rem;
        }

        #kis .kis-cat--carousel .kis-cat__arrows {
            display: inline-flex;
        }

        #kis .kis-arrow {
            width: 2.4rem;
            height: 2.4rem;
            font-size: 1rem;
            color: #fff;
            cursor: pointer;
            border: 1px solid rgba(255, 255, 255, .2);
            border-radius: 9999px;
            background: rgba(255, 255, 255, .06);
            transition: background .2s ease;
        }

        #kis .kis-arrow:hover {
            background: rgba(255, 255, 255, .14);
        }

        #kis .kis-grid {
            display: grid;
            gap: 1.25rem;
        }

        #kis .kis-item {
            display: flex;
            min-width: 0;
        }

        #kis .kis-item>* {
            flex: 1 1 auto;
            min-width: 0;
        }

        #kis .kis-item.is-dim>* {
            opacity: .22;
            filter: grayscale(.7);
        }

        /* Gaya 1: bento */
        #kis .kis-grid--bento {
            grid-template-columns: minmax(0, 1fr);
        }

        @media (min-width: 640px) {
            #kis .kis-grid--bento {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (min-width: 1024px) {
            #kis .kis-grid--bento {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            #kis .kis-grid--bento .kis-item:first-child:nth-last-child(n + 3) {
                grid-column: span 2;
            }

            #kis .kis-grid--bento .kis-item:first-child:nth-last-child(n + 3) h3 {
                font-size: 1.35rem;
            }
        }

        /* Gaya 2: carousel */
        #kis .kis-grid--carousel {
            display: flex;
            gap: 1.25rem;
            padding: .25rem .25rem 1.25rem;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            scrollbar-width: none;
        }

        #kis .kis-grid--carousel::-webkit-scrollbar {
            display: none;
        }

        #kis .kis-grid--carousel .kis-item {
            flex: 0 0 min(19rem, 82%);
            scroll-snap-align: start;
        }

        /* Gaya 3: node terhubung */
        #kis .kis-grid--nodes {
            grid-template-columns: minmax(0, 1fr);
            row-gap: 1.25rem;
        }

        #kis .kis-grid--nodes .kis-item {
            position: relative;
        }

        #kis .kis-grid--nodes .kis-item::before,
        #kis .kis-grid--nodes .kis-item::after {
            content: '';
            display: none;
            position: absolute;
        }

        #kis .kis-grid--nodes .kis-item::after {
            top: 50%;
            left: 100%;
            width: 3rem;
            height: 2px;
            opacity: .6;
            background: linear-gradient(90deg, var(--kis-accent), transparent);
        }

        #kis .kis-grid--nodes .kis-item::before {
            top: calc(50% - 4px);
            left: calc(100% - 4px);
            z-index: 1;
            width: 8px;
            height: 8px;
            border-radius: 9999px;
            background: var(--kis-accent);
            box-shadow: 0 0 12px var(--kis-accent);
        }

        @media (min-width: 640px) {
            #kis .kis-grid--nodes {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                column-gap: 3rem;
            }

            #kis .kis-grid--nodes .kis-item:not(:nth-child(2n)):not(:last-child)::before,
            #kis .kis-grid--nodes .kis-item:not(:nth-child(2n)):not(:last-child)::after {
                display: block;
            }
        }

        @media (min-width: 1024px) {
            #kis .kis-grid--nodes {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            #kis .kis-grid--nodes .kis-item::before,
            #kis .kis-grid--nodes .kis-item::after {
                display: none;
            }

            #kis .kis-grid--nodes .kis-item:not(:nth-child(3n)):not(:last-child)::before,
            #kis .kis-grid--nodes .kis-item:not(:nth-child(3n)):not(:last-child)::after {
                display: block;
            }
        }

        /* Gaya 4: chip */
        #kis .kis-grid--chips {
            display: flex;
            flex-wrap: wrap;
            gap: .75rem;
        }

        #kis .kis-grid--chips .kis-item>* {
            flex: 0 1 auto;
        }

        #kis .kis-chip {
            display: inline-flex;
            align-items: center;
            gap: .55rem;
            padding: .7rem 1.1rem;
            font-size: .85rem;
            font-weight: 700;
            color: #fff;
            border: 1px solid rgba(255, 255, 255, .14);
            border-radius: 9999px;
            background: rgba(255, 255, 255, .05);
            transition: border-color .25s ease, background .25s ease, transform .25s ease;
        }

        #kis a.kis-chip:hover {
            border-color: var(--kis-accent);
            background: rgba(255, 255, 255, .1);
            transform: translateY(-2px);
        }

        #kis span.kis-chip {
            cursor: default;
        }

        #kis .kis-dot {
            display: inline-block;
            width: .5rem;
            height: .5rem;
            border-radius: 9999px;
            background: var(--dot, #94a3b8);
        }

        #kis .kis-chip__lock {
            display: inline-flex;
            align-items: center;
            gap: .25rem;
            padding: .1rem .45rem;
            font-size: .62rem;
            font-weight: 700;
            color: #c7d2fe;
            border-radius: 9999px;
            background: rgba(129, 140, 248, .18);
        }

        /* Label di kartu */
        #kis .kis-tags {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: .35rem;
        }

        #kis .kis-pill {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .18rem .55rem;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .02em;
            color: #e2e8f0;
            border-radius: 9999px;
            background: rgba(255, 255, 255, .08);
        }

        #kis .kis-pill i {
            width: .45rem;
            height: .45rem;
            border-radius: 9999px;
            background: var(--dot, #94a3b8);
        }

        #kis .kis-pill--internal {
            color: #c7d2fe;
            background: rgba(129, 140, 248, .18);
        }

        /* Cahaya lembut mengikuti kursor */
        #kis .kis-fx {
            position: relative;
            overflow: hidden;
        }

        #kis .kis-fx::before {
            content: '';
            position: absolute;
            inset: 0;
            pointer-events: none;
            opacity: 0;
            background: radial-gradient(260px circle at var(--mx, 50%) var(--my, 50%), rgba(110, 231, 183, .14), transparent 70%);
            transition: opacity .3s ease;
        }

        #kis .kis-fx:hover::before {
            opacity: 1;
        }

        /* Muncul perlahan saat discroll (hanya aktif jika JavaScript berjalan) */
        #kis.kis-js .kis-reveal {
            opacity: 0;
            transform: translateY(16px);
            transition: opacity .7s ease, transform .7s ease;
            transition-delay: calc(var(--i, 0) * 70ms);
        }

        #kis.kis-js .kis-reveal.is-in {
            opacity: 1;
            transform: none;
        }

        @media (prefers-reduced-motion: reduce) {
            #kis .kis-ticker__track {
                animation: none;
            }

            #kis.kis-js .kis-reveal {
                opacity: 1;
                transform: none;
                transition: none;
            }

            #kis * {
                scroll-behavior: auto !important;
            }
        }
    </style>

    <div class="max-w-7xl mx-auto px-4 sm:px-8 relative z-10">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
            <div>
                <h2 class="text-3xl sm:text-4xl font-extrabold font-heading text-white mt-4">APLIKASI KIS (Kariadi Information System)</h2>
                <p class="text-slate-300 mt-2 text-sm max-w-xl">Portal akses cepat ke modul-modul sistem informasi pelayanan medis dan administrasi rumah sakit.</p>
            </div>
            <div>
                <a href="<?= esc(site_setting('manpro_url', 'https://manpro.rskariadi.id')) ?>" target="_blank" class="inline-flex items-center space-x-2 bg-white/10 hover:bg-white/20 text-white text-xs font-bold px-4 py-3 rounded-xl border border-white/15 transition backdrop-blur-sm">
                    <span>Bantuan Kendala KIS (Manpro) &rarr;</span>
                </a>
            </div>
        </div>

        <?php if ($kisKategori === []): ?>
            <p class="kis-empty">Daftar aplikasi sedang disiapkan.</p>
        <?php else: ?>

            <!-- Hub KIS -->
            <div class="kis-hub">
                <svg class="kis-hub__lines" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                    <?php foreach ($kisKategori as $i => $kat): ?>
                        <?php
                        $angle = deg2rad(-90 + $i * 360 / max($kisKatCount, 1));
                        $x = round(50 + 38 * cos($angle), 2);
                        $y = round(50 + 38 * sin($angle), 2);
                        ?>
                        <line class="kis-hub__line" data-kis-spoke="<?= $i ?>" x1="50" y1="50" x2="<?= $x ?>" y2="<?= $y ?>" />
                    <?php endforeach; ?>
                </svg>
                <div class="kis-hub__core">
                    <div>
                        <strong class="font-heading">KIS</strong>
                        <small>Kariadi Information System<br><?= count($kisAllApps) ?> aplikasi</small>
                    </div>
                </div>
                <?php foreach ($kisKategori as $i => $kat): ?>
                    <?php
                    $angle = deg2rad(-90 + $i * 360 / max($kisKatCount, 1));
                    $x = round(50 + 38 * cos($angle), 2);
                    $y = round(50 + 38 * sin($angle), 2);
                    ?>
                    <a href="#kis-cat-<?= esc($kat['slug'], 'attr') ?>" class="kis-hub__node" data-kis-node="<?= $i ?>" style="--x: <?= $x ?>%; --y: <?= $y ?>%;">
                        <b><?= sprintf('%02d', $i + 1) ?></b>
                        <span><?= esc($kat['nama']) ?></span>
                        <em><?= count($kat['apps']) ?> aplikasi</em>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Ticker nama aplikasi -->
            <?php if (count($kisAllApps) >= 4): ?>
                <div class="kis-ticker" aria-hidden="true">
                    <div class="kis-ticker__track">
                        <?php for ($copy = 0; $copy < 2; $copy++): ?>
                            <?php foreach ($kisAllApps as $app): ?>
                                <span><?= esc($app['nama_aplikasi']) ?></span>
                            <?php endforeach; ?>
                        <?php endfor; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Filter klasifikasi dan pencarian -->
            <div class="kis-toolbar">
                <div class="kis-filter" role="group" aria-label="Filter klasifikasi">
                    <span class="kis-filter__label">Klasifikasi</span>
                    <button type="button" data-kis-klas="all" aria-pressed="true">Semua</button>
                    <?php foreach ($kisKlasLabels as $key => $label): ?>
                        <button type="button" data-kis-klas="<?= esc($key, 'attr') ?>" aria-pressed="false">
                            <i class="kis-dot" style="--dot: <?= esc($kisKlasColors[$key], 'attr') ?>"></i><?= esc($label) ?>
                        </button>
                    <?php endforeach; ?>
                </div>
                <input type="search" class="kis-search" placeholder="Cari aplikasi..." aria-label="Cari aplikasi" data-kis-search>
            </div>

            <!-- Rel navigasi kategori -->
            <nav class="kis-rail" aria-label="Kategori aplikasi KIS">
                <?php foreach ($kisKategori as $i => $kat): ?>
                    <a href="#kis-cat-<?= esc($kat['slug'], 'attr') ?>" data-kis-rail="kis-cat-<?= esc($kat['slug'], 'attr') ?>">
                        <b><?= sprintf('%02d', $i + 1) ?></b><?= esc($kat['nama']) ?>
                    </a>
                <?php endforeach; ?>
            </nav>

            <!-- Kategori -->
            <?php foreach ($kisKategori as $ki => $kat): ?>
                <?php $variant = $kisVariants[$ki % count($kisVariants)]; ?>
                <section id="kis-cat-<?= esc($kat['slug'], 'attr') ?>" class="kis-cat kis-cat--<?= $variant ?>" data-kis-section>
                    <div class="kis-cat__head">
                        <span class="kis-cat__num font-heading" aria-hidden="true"><?= sprintf('%02d', $ki + 1) ?></span>
                        <div class="kis-cat__title">
                            <h3 class="text-2xl sm:text-3xl font-extrabold font-heading text-white"><?= esc($kat['nama']) ?></h3>
                            <?php if (! empty($kat['deskripsi'])): ?>
                                <p class="text-sm text-slate-300 mt-1 max-w-xl"><?= esc($kat['deskripsi']) ?></p>
                            <?php endif; ?>
                        </div>
                        <span class="kis-cat__count"><?= count($kat['apps']) ?> aplikasi</span>
                
                    </div>

                    <div class="kis-grid kis-grid--<?= $variant ?>" <?= $variant === 'carousel' ? 'data-kis-track' : '' ?>>
                        <?php foreach ($kat['apps'] as $ai => $app): ?>
                            <div class="kis-item kis-reveal" style="--i: <?= min($ai, 8) ?>;">
                                <?php $variant === 'chips' ? $kisChip($app) : $kisCard($app); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endforeach; ?>

            <p class="kis-empty" data-kis-empty hidden>Tidak ada aplikasi yang cocok dengan pencarian.</p>

        <?php endif; ?>
    </div>

    <script>
        (() => {
            const root = document.getElementById('kis');
            if (!root) return;
            root.classList.add('kis-js');

            const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            const behavior = reduce ? 'auto' : 'smooth';

            // 1) Muncul perlahan saat discroll
            const reveals = root.querySelectorAll('.kis-reveal');
            if ('IntersectionObserver' in window && !reduce) {
                const io = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-in');
                            io.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.12
                });
                reveals.forEach((el) => io.observe(el));
            } else {
                reveals.forEach((el) => el.classList.add('is-in'));
            }

            // 2) Cahaya lembut mengikuti kursor
            root.addEventListener('pointermove', (event) => {
                const card = event.target.closest('.kis-fx');
                if (!card) return;
                const rect = card.getBoundingClientRect();
                card.style.setProperty('--mx', (event.clientX - rect.left) + 'px');
                card.style.setProperty('--my', (event.clientY - rect.top) + 'px');
            });

            // 3) Filter klasifikasi dan pencarian
            const cards = [...root.querySelectorAll('[data-kis-card]')];
            const sections = [...root.querySelectorAll('[data-kis-section]')];
            const emptyNote = root.querySelector('[data-kis-empty]');
            const searchInput = root.querySelector('[data-kis-search]');
            const klasButtons = [...root.querySelectorAll('[data-kis-klas]')];
            let klas = 'all';

            const applyFilter = () => {
                const query = (searchInput ? searchInput.value : '').trim().toLowerCase();
                let visible = 0;

                cards.forEach((card) => {
                    const item = card.closest('.kis-item');
                    const textMatch = query === '' || (card.dataset.search || '').includes(query);
                    const klasMatch = klas === 'all' || card.dataset.klas === klas;
                    item.hidden = !textMatch;
                    item.classList.toggle('is-dim', textMatch && !klasMatch);
                    if (textMatch) visible++;
                });

                sections.forEach((section) => {
                    const anyVisible = [...section.querySelectorAll('.kis-item')].some((item) => !item.hidden);
                    section.hidden = query !== '' && !anyVisible;
                });

                if (emptyNote) emptyNote.hidden = !(query !== '' && visible === 0);
            };

            klasButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    klas = button.dataset.kisKlas;
                    klasButtons.forEach((b) => b.setAttribute('aria-pressed', b === button ? 'true' : 'false'));
                    applyFilter();
                });
            });
            if (searchInput) searchInput.addEventListener('input', applyFilter);

            // 4) Tombol panah carousel
            root.querySelectorAll('[data-kis-scroll]').forEach((button) => {
                button.addEventListener('click', () => {
                    const track = button.closest('[data-kis-section]').querySelector('[data-kis-track]');
                    if (!track) return;
                    track.scrollBy({
                        left: Number(button.dataset.kisScroll) * track.clientWidth * 0.8,
                        behavior
                    });
                });
            });

            // 5) Lompat halus ke kategori (dari hub dan rel navigasi)
            root.querySelectorAll('a[href^="#kis-cat-"]').forEach((link) => {
                link.addEventListener('click', (event) => {
                    const target = document.getElementById(link.getAttribute('href').slice(1));
                    if (!target) return;
                    event.preventDefault();
                    target.scrollIntoView({
                        behavior,
                        block: 'start'
                    });
                });
            });

            // 6) Rel navigasi mengikuti posisi scroll
            const railLinks = [...root.querySelectorAll('[data-kis-rail]')];
            if ('IntersectionObserver' in window && railLinks.length) {
                const spy = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (!entry.isIntersecting) return;
                        railLinks.forEach((link) => link.classList.toggle('is-active', link.dataset.kisRail === entry.target.id));
                    });
                }, {
                    rootMargin: '-35% 0px -55% 0px'
                });
                sections.forEach((section) => spy.observe(section));
            }

            // 7) Garis hub menyala saat kategori disorot
            root.querySelectorAll('[data-kis-node]').forEach((node) => {
                const spoke = root.querySelector('[data-kis-spoke="' + node.dataset.kisNode + '"]');
                if (!spoke) return;
                ['mouseenter', 'focus'].forEach((name) => node.addEventListener(name, () => spoke.classList.add('is-on')));
                ['mouseleave', 'blur'].forEach((name) => node.addEventListener(name, () => spoke.classList.remove('is-on')));
            });
        })();
    </script>
</section>

<!-- SECTION: BERITA & PENGUMUMAN -->
<section id="berita" class="py-24 bg-slate-50 border-t border-slate-200/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-16 gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-brand-emerald-800 bg-brand-emerald-100/70 px-3.5 py-1.5 rounded-full border border-brand-emerald-200">Informasi Terkini</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold font-heading text-slate-900 mt-4">Berita & Update SIMRS</h2>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php foreach ($berita as $news): ?>
                <article class="bg-white rounded-3xl overflow-hidden border border-slate-200/80 shadow-sm hover:shadow-xl hover:border-brand-emerald-300 transition duration-300 flex flex-col justify-between group">
                    <div>
                        <div class="h-48 bg-gradient-to-tr from-brand-emerald-950 via-brand-emerald-900 to-slate-800 relative flex items-center justify-center text-white overflow-hidden">
                            <?php if (!empty($news['thumbnail'])): ?>
                                <img src="<?= base_url('uploads/berita/' . $news['thumbnail']) ?>" alt="<?= esc($news['judul']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <?php else: ?>
                                <svg class="w-12 h-12 text-brand-mint-400 opacity-60 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                            <?php endif; ?>
                            <span class="absolute top-4 left-4 bg-brand-emerald-950/80 backdrop-blur-md text-brand-mint-300 text-[10px] font-bold px-3 py-1 rounded-full border border-brand-emerald-700">
                                <?= esc($news['kategori_nama'] ?? 'Berita') ?>
                            </span>
                        </div>
                        <div class="p-6">
                            <div class="text-xs text-slate-400 mb-2 font-medium"><?= date('d F Y', strtotime($news['published_at'] ?? $news['created_at'])) ?></div>
                            <h3 class="text-base font-bold font-heading text-slate-900 leading-snug mb-3 group-hover:text-brand-emerald-700 transition">
                                <?= esc($news['judul']) ?>
                            </h3>
                            <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">
                                <?= esc($news['ringkasan'] ?? strip_tags($news['konten'])) ?>
                            </p>
                        </div>
                    </div>
                    <div class="p-6 pt-0">
                        <span class="text-xs font-bold text-brand-emerald-700 group-hover:text-brand-teal-700 flex items-center space-x-1">
                            <span>Baca Selengkapnya</span>
                            <span class="group-hover:translate-x-1 transition">&rarr;</span>
                        </span>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- SECTION: GALERI KEGIATAN SIRS (DENGAN FILTER & ALPINE LIGHTBOX MODAL) -->
<section id="galeri" class="py-24 bg-white" x-data="{ 
        activeTab: 'all',
        lightboxOpen: false,
        activeAlbum: null,
        activePhotoIndex: 0,
        openLightbox(album, index = 0) {
            this.activeAlbum = album;
            this.activePhotoIndex = index;
            this.lightboxOpen = true;
            document.body.classList.add('overflow-hidden');
        },
        closeLightbox() {
            this.lightboxOpen = false;
            document.body.classList.remove('overflow-hidden');
        },
        nextPhoto() {
            if (!this.activeAlbum || !this.activeAlbum.fotos.length) return;
            this.activePhotoIndex = (this.activePhotoIndex + 1) % this.activeAlbum.fotos.length;
        },
        prevPhoto() {
            if (!this.activeAlbum || !this.activeAlbum.fotos.length) return;
            this.activePhotoIndex = (this.activePhotoIndex - 1 + this.activeAlbum.fotos.length) % this.activeAlbum.fotos.length;
        }
    }"
    @keydown.escape.window="closeLightbox()"
    @keydown.arrow-right.window="lightboxOpen && nextPhoto()"
    @keydown.arrow-left.window="lightboxOpen && prevPhoto()">

    <div class="max-w-7xl mx-auto px-4 sm:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
            <div>
                <h2 class="text-3xl sm:text-4xl font-extrabold font-heading text-slate-900 mt-4">Galeri Kegiatan SIRS</h2>
            </div>

            <!-- Filter Category Buttons -->
            <div class="flex flex-wrap items-center gap-2">
                <button @click="activeTab = 'all'"
                    :class="activeTab === 'all' ? 'bg-gradient-to-r from-brand-emerald-600 to-brand-teal-600 text-white shadow-md shadow-brand-emerald-500/20' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition duration-200">
                    Semua Album
                </button>
                <?php foreach ($kategoriKegiatan as $katKeg): ?>
                    <button @click="activeTab = '<?= esc($katKeg['slug']) ?>'"
                        :class="activeTab === '<?= esc($katKeg['slug']) ?>' ? 'bg-gradient-to-r from-brand-emerald-600 to-brand-teal-600 text-white shadow-md shadow-brand-emerald-500/20' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition duration-200">
                        <?= esc($katKeg['nama']) ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Galeri Grid (Responsive Cards) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($kegiatan as $idx => $album): ?>
                <div x-show="activeTab === 'all' || activeTab === '<?= esc($album['kategori_slug'] ?? '') ?>'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="bg-slate-50 rounded-3xl overflow-hidden border border-slate-200/80 shadow-sm hover:shadow-xl hover:border-brand-emerald-400 transition duration-300 flex flex-col justify-between group cursor-pointer"
                    @click="openLightbox(<?= htmlspecialchars(json_encode($album), ENT_QUOTES, 'UTF-8') ?>)">

                    <div>
                        <!-- Album Cover Container -->
                        <div class="h-56 bg-gradient-to-tr from-brand-emerald-900 via-brand-teal-800 to-slate-900 relative overflow-hidden flex items-center justify-center">
                            <?php if (!empty($album['cover_foto'])): ?>
                                <img src="<?= base_url('uploads/kegiatan/' . $album['cover_foto']) ?>" alt="<?= esc($album['judul']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <?php else: ?>
                                <!-- Dynamic Stylized Geometric Cover -->
                                <div class="w-full h-full bg-gradient-to-br from-brand-emerald-800 to-slate-900 flex flex-col items-center justify-center p-6 text-center">
                                    <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-sm flex items-center justify-center text-brand-mint-300 mb-2 group-hover:scale-110 transition duration-300">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <span class="text-xs text-brand-mint-200 font-semibold"><?= esc($album['kategori_nama'] ?? 'Dokumentasi') ?></span>
                                </div>
                            <?php endif; ?>

                            <!-- Floating Badge -->
                            <div class="absolute top-4 left-4 flex items-center space-x-2">
                                <span class="bg-brand-emerald-950/80 backdrop-blur-md text-brand-mint-300 text-[10px] font-bold px-3 py-1 rounded-full border border-brand-emerald-700">
                                    <?= esc($album['kategori_nama'] ?? 'Galeri') ?>
                                </span>
                            </div>
                            <div class="absolute bottom-4 right-4 bg-slate-900/80 backdrop-blur-md text-white text-[11px] font-bold px-3 py-1 rounded-full flex items-center space-x-1.5">
                                <svg class="w-3.5 h-3.5 text-brand-mint-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span><?= $album['total_foto'] ?> Foto</span>
                            </div>
                        </div>

                        <!-- Content Details -->
                        <div class="p-6">
                            <div class="text-xs text-brand-emerald-700 font-bold mb-1.5 flex items-center space-x-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span><?= date('d M Y', strtotime($album['tanggal'])) ?></span>
                            </div>
                            <h3 class="text-base font-bold font-heading text-slate-900 group-hover:text-brand-emerald-700 transition leading-snug mb-2">
                                <?= esc($album['judul']) ?>
                            </h3>
                            <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
                                <?= esc($album['deskripsi']) ?>
                            </p>
                        </div>
                    </div>

                    <div class="px-6 pb-6 pt-0 flex items-center justify-between text-xs font-bold text-brand-emerald-700">
                        <span>Buka Album Foto</span>
                        <span class="group-hover:translate-x-1 transition duration-200">&rarr;</span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- LIGHTBOX MODAL (ALPINE.JS) -->
    <div x-show="lightboxOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/95 backdrop-blur-lg p-4 sm:p-8"
        style="display: none;">

        <!-- Close Button -->
        <button @click="closeLightbox()" class="absolute top-5 right-5 text-slate-400 hover:text-white p-2 rounded-2xl bg-white/10 hover:bg-white/20 transition z-50">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Lightbox Container -->
        <template x-if="activeAlbum">
            <div class="w-full max-w-5xl flex flex-col items-center max-h-full">
                <!-- Album Header -->
                <div class="text-center mb-4 text-white max-w-2xl px-4">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-brand-mint-300" x-text="activeAlbum.kategori_nama"></span>
                    <h3 class="text-lg sm:text-xl font-bold font-heading" x-text="activeAlbum.judul"></h3>
                </div>

                <!-- Photo Display & Navigation -->
                <div class="relative w-full flex items-center justify-center my-auto min-h-[300px] sm:min-h-[420px] max-h-[65vh]">
                    <!-- Prev Button -->
                    <button @click="prevPhoto()"
                        x-show="activeAlbum.fotos && activeAlbum.fotos.length > 1"
                        class="absolute left-2 sm:-left-6 text-white p-3 rounded-2xl bg-white/10 hover:bg-white/25 backdrop-blur-md transition z-40">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <!-- Main Picture Preview Area -->
                    <div class="w-full h-full flex flex-col items-center justify-center">
                        <div class="w-full max-h-[55vh] flex items-center justify-center rounded-2xl overflow-hidden bg-slate-900 border border-slate-800">
                            <template x-if="activeAlbum.fotos && activeAlbum.fotos.length > 0">
                                <div class="p-8 text-center space-y-4">
                                    <div class="w-20 h-20 mx-auto rounded-3xl bg-gradient-to-tr from-brand-mint-400 to-brand-teal-500 flex items-center justify-center text-slate-950 font-black text-2xl shadow-xl shadow-brand-mint-400/20">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-white max-w-lg" x-text="activeAlbum.fotos[activePhotoIndex].caption || 'Dokumentasi ' + activeAlbum.judul"></p>
                                    <p class="text-xs text-brand-mint-300 font-mono">Foto <span x-text="activePhotoIndex + 1"></span> dari <span x-text="activeAlbum.fotos.length"></span></p>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Next Button -->
                    <button @click="nextPhoto()"
                        x-show="activeAlbum.fotos && activeAlbum.fotos.length > 1"
                        class="absolute right-2 sm:-right-6 text-white p-3 rounded-2xl bg-white/10 hover:bg-white/25 backdrop-blur-md transition z-40">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>

                <!-- Thumbnails Strip -->
                <template x-if="activeAlbum.fotos && activeAlbum.fotos.length > 1">
                    <div class="flex items-center space-x-2 mt-4 overflow-x-auto max-w-full p-2">
                        <template x-for="(f, i) in activeAlbum.fotos" :key="i">
                            <button @click="activePhotoIndex = i"
                                :class="activePhotoIndex === i ? 'ring-2 ring-brand-mint-400 scale-105 bg-brand-emerald-700' : 'opacity-60 hover:opacity-100 bg-slate-800'"
                                class="w-12 h-12 rounded-xl text-white text-xs font-bold transition flex items-center justify-center shrink-0">
                                <span x-text="i + 1"></span>
                            </button>
                        </template>
                    </div>
                </template>
            </div>
        </template>
    </div>
</section>

<!-- SECTION: CTA MANPRO BANNER INTEGRASI -->
<section class="py-20 bg-gradient-to-r from-brand-emerald-800 via-brand-emerald-700 to-brand-teal-800 text-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-8 text-center relative z-10 space-y-6">
        <div class="inline-flex items-center space-x-2 bg-brand-mint-300 text-slate-950 px-4 py-1.5 rounded-full text-xs font-extrabold shadow-md">
            <span>PORTAL RESMI PELAPORAN IT</span>
        </div>
        <h2 class="text-3xl sm:text-5xl font-extrabold font-heading text-white max-w-3xl mx-auto leading-tight">
            Mengalami Kendala Teknis Hardware, SIMRS, atau Jaringan?
        </h2>
        <p class="text-sm sm:text-base text-brand-mint-100 max-w-2xl mx-auto leading-relaxed">
            Laporkan seluruh kendala teknologi informasi Anda langsung melalui sistem Manpro untuk penanganan cepat dan pemantauan status pengerjaan secara transparan.
        </p>
        <div class="pt-4">
            <a href="<?= esc(site_setting('manpro_url', 'https://manpro.rskariadi.id')) ?>"
                target="_blank"
                class="inline-flex items-center space-x-3 bg-gradient-to-r from-brand-mint-300 via-brand-mint-400 to-brand-teal-300 hover:from-brand-mint-200 hover:to-brand-teal-200 text-slate-950 font-black text-sm px-9 py-4 rounded-2xl shadow-2xl hover:scale-105 active:scale-95 transition duration-200">
                <span>Akses Sistem Manpro Sekarang</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
            </a>
        </div>
    </div>
</section>


<script>
    function heroSlideshow() {
        const slides = <?= $heroJSON ?>;
        return {
            slides: slides,
            current: 0,
            init() {
                if (this.slides.length > 1) {
                    setInterval(() => {
                        this.current = (this.current + 1) % this.slides.length;
                    }, 6000);
                }
            }
        };
    }

    (() => {
        const hero = document.querySelector('[data-hero-section]');
        const visionPanel = document.querySelector('[data-vision-panel]');
        const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (!hero || !visionPanel || reducedMotion) {
            return;
        }

        let framePending = false;
        const updateHeroTransition = () => {
            const viewportHeight = Math.max(window.innerHeight, 1);
            const panelTop = visionPanel.getBoundingClientRect().top;
            const progress = Math.min(1, Math.max(0, (viewportHeight - panelTop) / viewportHeight));

            hero.style.setProperty('--hero-dimmer', (progress * 0.52).toFixed(3));
            framePending = false;
        };

        const requestHeroTransitionUpdate = () => {
            if (framePending) {
                return;
            }

            framePending = true;
            window.requestAnimationFrame(updateHeroTransition);
        };

        updateHeroTransition();
        window.addEventListener('scroll', requestHeroTransitionUpdate, {
            passive: true
        });
        window.addEventListener('resize', requestHeroTransitionUpdate, {
            passive: true
        });
    })();

    (() => {
        const visionReveal = document.querySelector('[data-vision-reveal]');
        if (!visionReveal) {
            return;
        }

        const revealCards = visionReveal.querySelectorAll('.reveal-card');
        const revealWords = visionReveal.querySelectorAll('.reveal-word');
        visionReveal.classList.add('reveal-ready');

        const setRevealStyles = (visible) => {
            revealCards.forEach((card) => {
                card.style.setProperty('opacity', visible ? '1' : '0', 'important');
                card.style.setProperty('transform', visible ? 'translateY(0)' : 'translateY(28px)', 'important');
            });
            revealWords.forEach((word) => {
                word.style.setProperty('opacity', visible ? '1' : '0', 'important');
                word.style.setProperty('transform', visible ? 'translateX(0)' : 'translateX(-8px)', 'important');
            });
        };

        const revealSection = () => {
            visionReveal.classList.add('is-revealed');
            setRevealStyles(true);
        };

        const resetSection = () => {
            visionReveal.classList.remove('is-revealed');
            setRevealStyles(false);
        };

        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches || !('IntersectionObserver' in window)) {
            revealSection();
            return;
        }

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    revealSection();
                    return;
                }

                resetSection();
            });
        }, {
            threshold: 0.9,
            rootMargin: '0px 0px 12% 0px'
        });

        observer.observe(visionReveal);
    })();

    (() => {
        const transition = document.querySelector('[data-curtain-transition]');
        const panel = document.querySelector('[data-vision-panel]');
        const organization = document.querySelector('[data-curtain-organization]');
        const reducedMotionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');

        if (!transition || !panel || !organization) {
            return;
        }

        let framePending = false;

        const updateCurtain = () => {
            framePending = false;
            const panelHeight = panel.offsetHeight;
            transition.style.setProperty('--curtain-height', `${panelHeight}px`);

            if (reducedMotionQuery.matches) {
                organization.style.removeProperty('transform');
                return;
            }

            const transitionTop = transition.getBoundingClientRect().top + window.scrollY;
            const progress = Math.min(1, Math.max(0, (window.scrollY - transitionTop) / Math.max(panelHeight, 1)));
            organization.style.transform = `translate3d(0, ${(progress - 1) * panelHeight}px, 0)`;
        };

        const requestCurtainUpdate = () => {
            if (framePending) {
                return;
            }

            framePending = true;
            window.requestAnimationFrame(updateCurtain);
        };

        window.addEventListener('scroll', requestCurtainUpdate, {
            passive: true
        });
        window.addEventListener('resize', requestCurtainUpdate, {
            passive: true
        });
        reducedMotionQuery.addEventListener('change', requestCurtainUpdate);
        requestCurtainUpdate();
    })();

    (() => {
        const stage = document.querySelector('[data-services-stage]');
        const track = document.querySelector('[data-services-track]');
        const reducedMotionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');

        if (!stage || !track) {
            return;
        }

        let framePending = false;
        const updateDesktopTrack = () => {
            framePending = false;
            if (window.innerWidth < 1024 || reducedMotionQuery.matches) {
                track.style.removeProperty('transform');
                return;
            }

            const startOffset = 96;
            const scrollDistance = Math.max(stage.offsetHeight - window.innerHeight + startOffset, 1);
            const progress = Math.min(1, Math.max(0, (startOffset - stage.getBoundingClientRect().top) / scrollDistance));
            track.style.transform = `translate3d(${110 - progress * 220}vw, 0, 0)`;
        };

        const requestDesktopUpdate = () => {
            if (framePending) {
                return;
            }

            framePending = true;
            window.requestAnimationFrame(updateDesktopTrack);
        };

        window.addEventListener('scroll', requestDesktopUpdate, {
            passive: true
        });
        window.addEventListener('resize', requestDesktopUpdate, {
            passive: true
        });
        reducedMotionQuery.addEventListener('change', requestDesktopUpdate);
        requestDesktopUpdate();
    })();

    (() => {
        const viewer = document.getElementById('layanan-media-viewer');
        if (!viewer) {
            return;
        }

        const title = viewer.querySelector('#layanan-viewer-title');
        const image = viewer.querySelector('[data-viewer-image]');
        const video = viewer.querySelector('[data-viewer-video]');
        const content = [image, video];
        const triggerSelector = '[data-layanan-viewer]';
        let zoom = 1;
        let lastTrigger = null;
        let previousOverflow = '';

        const applyZoom = () => {
            content.forEach((element) => {
                element.style.transform = `scale(${zoom})`;
            });
        };

        const closeViewer = () => {
            if (viewer.hidden) {
                return;
            }
            viewer.hidden = true;
            viewer.classList.add('hidden');
            video.pause();
            video.removeAttribute('src');
            video.load();
            image.removeAttribute('src');
            zoom = 1;
            applyZoom();
            document.body.style.overflow = previousOverflow;
            if (lastTrigger) {
                lastTrigger.focus();
            }
        };

        document.querySelectorAll(triggerSelector).forEach((trigger) => {
            trigger.addEventListener('click', () => {
                lastTrigger = trigger;
                title.textContent = trigger.dataset.title || '';
                zoom = 1;
                applyZoom();

                const isVideo = trigger.dataset.mediaType === 'video';
                image.classList.toggle('hidden', isVideo);
                video.classList.toggle('hidden', !isVideo);
                if (isVideo) {
                    video.src = trigger.dataset.src;
                    video.load();
                } else {
                    image.src = trigger.dataset.src;
                    image.alt = trigger.dataset.title || '';
                }

                previousOverflow = document.body.style.overflow;
                document.body.style.overflow = 'hidden';
                viewer.hidden = false;
                viewer.classList.remove('hidden');
                viewer.querySelector('[data-viewer-close]').focus();
            });
        });

        viewer.querySelector('[data-viewer-close]').addEventListener('click', closeViewer);
        viewer.addEventListener('click', (event) => {
            if (event.target === viewer) {
                closeViewer();
            }
        });
        viewer.querySelector('[data-viewer-zoom-in]').addEventListener('click', () => {
            zoom = Math.min(3, zoom + 0.25);
            applyZoom();
        });
        viewer.querySelector('[data-viewer-zoom-out]').addEventListener('click', () => {
            zoom = Math.max(0.5, zoom - 0.25);
            applyZoom();
        });
        viewer.querySelector('[data-viewer-reset]').addEventListener('click', () => {
            zoom = 1;
            applyZoom();
        });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeViewer();
            }
        });
    })();
</script>
<?= $this->endSection() ?>