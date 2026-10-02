<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Layanan IT<?= $this->endSection() ?>
<?= $this->section('page_heading') ?>Layanan IT<?= $this->endSection() ?>
<?= $this->section('page_description') ?>Kelola kategori layanan dan daftar layanan di dalamnya<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
/** @var list<array<string, mixed>> $kategori */
$message = session()->getFlashdata('message');
$errorMessage = session()->getFlashdata('error');
$totalItem = array_sum(array_map(static fn(array $k): int => count($k['items']), $kategori));
$normalize = static fn(string $text): string => mb_strtolower(trim($text));
?>
<div class="mx-auto max-w-70rem space-y-6">
    <?php if ($message): ?>
        <p role="status" class="rounded-lg border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-800"><?= esc($message) ?></p>
    <?php endif; ?>
    <?php if ($errorMessage): ?>
        <p role="alert" class="rounded-lg border border-rose-200 bg-rose-50 p-3 text-sm text-rose-800"><?= esc($errorMessage) ?></p>
    <?php endif; ?>

    <p class="text-sm text-slate-500"><?= count($kategori) ?> kategori &middot; <?= $totalItem ?> layanan</p>

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
        ?>
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <header class="flex items-start justify-between gap-4 border-b border-slate-100 bg-slate-50/70 px-5 py-4 sm:px-6">
                <div class="flex min-w-0 items-start gap-3">
                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-teal-700 text-sm font-bold text-white"><?= $index + 1 ?></span>
                    <div class="min-w-0">
                        <h2 class="font-heading text-base font-bold text-slate-900"><?= esc($kat['nama']) ?></h2>
                        <p class="mt-0.5 text-xs text-slate-400">slug: <?= esc($kat['slug']) ?></p>
                        <?php if (! empty($kat['deskripsi'])): ?>
                            <p class="mt-2 text-sm leading-relaxed text-slate-600"><?= esc($kat['deskripsi']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <span class="shrink-0 rounded-full bg-teal-50 px-3 py-1 text-xs font-bold text-teal-800"><?= count($kat['items']) ?> layanan</span>
            </header>

            <div class="px-5 py-3 sm:px-6">
                <?php if ($kat['items'] === []): ?>
                    <p class="py-3 text-sm text-slate-500">Belum ada layanan di kategori ini.</p>
                <?php else: ?>
                    <ul class="divide-y divide-slate-100">
                        <?php foreach ($kat['items'] as $itemIndex => $item): ?>
                            <?php $isDuplicate = ($titleCounts[$normalize((string) $item['judul'])] ?? 0) > 1; ?>
                            <li class="flex items-center gap-3 py-2.5">
                                <span class="w-6 shrink-0 text-xs font-semibold text-slate-400"><?= $itemIndex + 1 ?></span>
                                <span class="min-w-0 flex-1 text-sm font-semibold text-slate-800"><?= esc($item['judul']) ?></span>
                                <?php if ($isDuplicate): ?>
                                    <span class="shrink-0 rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-bold text-amber-800">Duplikat</span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </section>
    <?php endforeach; ?>
</div>
<?= $this->endSection() ?>