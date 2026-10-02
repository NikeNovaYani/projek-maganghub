<?= $this->extend('layouts/admin') ?>
<?= $this->section('title') ?>Pengaturan Web<?= $this->endSection() ?>
<?= $this->section('page_heading') ?>Pengaturan Web<?= $this->endSection() ?>
<?= $this->section('content') ?>
<?php
/** @var array<string, list<array<string, mixed>>> $grouped */

// Label yang ditampilkan untuk nama grup (nama grup di database tidak berubah)
$groupLabels = ['general' => 'Dashboard'];
$groupLabel = static fn(string $name): string => $groupLabels[strtolower($name)] ?? ucfirst($name);

$inputClass = 'w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 transition placeholder:text-slate-400 focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/30';
?>

<style>
    .hero-thumb-delete {
        position: absolute;
        top: .4rem;
        right: .4rem;
        z-index: 10;
        display: grid;
        place-items: center;
        width: 1.75rem;
        height: 1.75rem;
        padding: 0;
        font-size: 1.25rem;
        font-weight: 700;
        line-height: 1;
        color: #fff;
        cursor: pointer;
        background: #97d4dea2;
        border-radius: 9999px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .3);
        transition: transform .15s ease, background .15s ease;
    }

    .hero-thumb-delete:hover {
        background: #be123c;
        transform: scale(1.1);
    }
</style>

<div class="mx-auto max-w-8xl space-y-6">
    <?php if (session()->getFlashdata('success')): ?>
        <div role="status" class="flex items-center gap-3 rounded-xl border border-cyan-200 bg-cyan-50 p-4 text-sm font-semibold text-cyan-900">
            <svg class="h-5 w-5 shrink-0 text-cyan-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span><?= esc(session()->getFlashdata('success')) ?></span>
        </div>
    <?php endif; ?>

    <form id="settings-form" action="<?= base_url('admin/settings/save') ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
        <?= csrf_field() ?>

        <?php foreach ($grouped as $groupName => $items): ?>
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <header class="flex items-center gap-3 border-b border-slate-100 bg-cyan-50/60 px-5 py-4 sm:px-7">
                    <span class="grid h-9 w-9 place-items-center rounded-xl bg-cyan-700 text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                    </span>
                    <h2 class="font-heading text-base font-bold text-slate-900"><?= esc($groupLabel((string) $groupName)) ?></h2>
                </header>

                <div class="space-y-6 p-5 sm:p-7">
                    <?php foreach ($items as $item): ?>
                        <?php
                        $key = (string) $item['key'];
                        $fieldId = 'setting_' . $key;
                        $label = $key === 'hero_bg_images' ? 'Foto Background' : (string) $item['label'];
                        ?>
                        <div class="space-y-2">
                            <label for="<?= esc($fieldId, 'attr') ?>" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                <?= esc($label) ?>
                            </label>

                            <?php if ($key === 'site_tagline'): ?>
                                <?php
                                $taglineLines = implode("\n", array_map('trim', explode('|', (string) $item['value'])));
                                ?>
                                <textarea id="tagline_lines" rows="4" class="<?= $inputClass ?> leading-relaxed" placeholder="Tulis satu punchline per baris"><?= esc($taglineLines) ?></textarea>
                                <input type="hidden" id="tagline_value" name="<?= esc($key, 'attr') ?>" value="<?= esc($item['value'], 'attr') ?>">

                                <div class="flex items-start gap-2.5 rounded-xl border border-cyan-100 bg-cyan-50 p-3.5">
                                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-cyan-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-xs leading-normal text-slate-600">
                                        <span class="font-semibold text-cyan-900">Format Punchline Marquee:</span>
                                        tulis satu punchline per baris. Tekan <kbd class="rounded bg-white px-1.5 py-0.5 font-semibold text-cyan-800 shadow-sm">Enter</kbd> untuk menambah punchline berikutnya.
                                    </p>
                                </div>

                            <?php elseif ($key === 'hero_bg_images'): ?>
                                <?php
                                $heroImgs = array_values(array_filter(array_map('trim', explode(',', (string) $item['value']))));
                                ?>
                                <!-- Textbox dihapus, nilai lama tetap dikirim agar daftar foto tidak berubah -->
                                <input type="hidden" name="<?= esc($key, 'attr') ?>" value="<?= esc($item['value'], 'attr') ?>">

                                <label class="block cursor-pointer rounded-xl border-2 border-dashed border-cyan-200 bg-cyan-50/50 p-6 text-center transition hover:border-cyan-400 hover:bg-cyan-50">
                                    <svg class="mx-auto h-8 w-8 text-cyan-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="mt-2 block text-sm font-semibold text-cyan-900">Upload Background Dashboard</span>
                                    <input id="<?= esc($fieldId, 'attr') ?>" type="file" name="hero_bg_files[]" multiple accept="image/*" class="sr-only">
                                    <span id="hero-files-info" class="mt-2 block text-xs font-semibold text-cyan-800"></span>
                                </label>

                                <?php if ($heroImgs !== []): ?>
                                    <div class="space-y-2 pt-2">
                                        <p class="text-sm font-semibold text-slate-700">Foto Background Terpasang (<?= count($heroImgs) ?> foto)</p>
                                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                                            <?php foreach ($heroImgs as $img): ?>
                                                <?php $imgSrc = (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) ? $img : base_url($img); ?>
                                                <div class="group relative aspect-video overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                                    <img src="<?= esc($imgSrc, 'attr') ?>" alt="Hero background" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                                                    <button type="submit" name="delete_hero_photo" value="<?= esc($img, 'attr') ?>"
                                                        onclick="return confirm('Hapus foto ini dari background hero?')"
                                                        class="hero-thumb-delete" title="Hapus foto" aria-label="Hapus foto">&times;</button>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                            <?php elseif (($item['tipe'] ?? '') === 'textarea'): ?>
                                <textarea id="<?= esc($fieldId, 'attr') ?>" name="<?= esc($key, 'attr') ?>" rows="3" class="<?= $inputClass ?> leading-relaxed"><?= esc($item['value']) ?></textarea>

                            <?php else: ?>
                                <input type="<?= esc($item['tipe'] ?? 'text', 'attr') ?>" id="<?= esc($fieldId, 'attr') ?>" name="<?= esc($key, 'attr') ?>" value="<?= esc($item['value'], 'attr') ?>" class="<?= $inputClass ?>">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>

        <!-- Tombol simpan -->
        <div class="sticky bottom-4 z-20 flex justify-end">
            <div class="rounded-2xl border border-slate-200 bg-white/90 p-2 shadow-lg backdrop-blur">
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-cyan-700 px-6 py-3 text-sm font-semibold text-white transition hover:bg-cyan-800 focus:outline-none focus:ring-2 focus:ring-cyan-600 focus:ring-offset-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Simpan Pengaturan</span>
                </button>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    (() => {
        const form = document.getElementById('settings-form');

        // Punchline: satu baris = satu punchline, disimpan ke "site_tagline" dengan pemisah |
        // (format yang sama seperti sebelumnya, jadi halaman publik tidak perlu diubah)
        form.addEventListener('submit', () => {
            const lines = document.getElementById('tagline_lines');
            const value = document.getElementById('tagline_value');
            if (!lines || !value) return;
            value.value = lines.value
                .split(/\r?\n/)
                .map((line) => line.trim())
                .filter((line) => line !== '')
                .join('|');
        });

        // Info jumlah file hero yang dipilih
        const files = form.querySelector('input[name="hero_bg_files[]"]');
        const info = document.getElementById('hero-files-info');
        if (files && info) {
            files.addEventListener('change', () => {
                info.textContent = files.files.length ? files.files.length + ' file dipilih' : '';
            });
        }
    })();
</script>
<?= $this->endSection() ?>