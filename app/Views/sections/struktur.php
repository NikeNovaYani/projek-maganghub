<!-- struktur publik -->

<?php

$renderCard = function (array $node): void {
    $initial = strtoupper(substr(trim($node['nama']), 0, 1));
    $level = (int) ($node['level'] ?? 1);
    $isGroup = ($node['node_type'] ?? '') === 'tim';
?>
    <div class="org-node" data-level="<?= $level ?>" data-node-type="<?= esc($node['node_type'] ?? 'pejabat') ?>" data-layout="<?= esc($node['layout_type'] ?? 'main') ?>">
        <button type="button"
            class="org-card group"
            @click="openDrawer(<?= (int) $node['id'] ?>)"
            aria-label="<?= $isGroup ? 'Lihat anggota tim ' . esc($node['jabatan']) : 'Lihat profil ' . esc($node['nama']) . ', ' . esc($node['jabatan']) ?>">
            <span class="org-card__shine" aria-hidden="true"></span>
            <?php if (!$isGroup): ?>
                <span class="org-card__avatar">
                    <?php if (!empty($node['foto'])): ?>
                        <img src="<?= base_url('uploads/organisasi/' . rawurlencode($node['foto'])) ?>" alt="<?= esc($node['nama']) ?>">
                    <?php else: ?>
                        <?= esc($initial) ?>
                    <?php endif; ?>
                </span>
            <?php endif; ?>
            <span class="org-card__body">
                <span class="org-card__role"><?= esc($node['jabatan']) ?></span>
                <?php if (!$isGroup): ?>
                    <span class="org-card__name"><?= esc($node['nama']) ?></span>
                <?php endif; ?>
            </span>
            <?php if ($isGroup): ?>
                <span class="org-card__count"><?= (int) $node['total_anggota'] ?> Orang</span>
            <?php endif; ?>
        </button>
    </div>
<?php
};

$renderTree = function (array $nodes, bool $isRoot = false, bool $administrationSpine = false, bool $verticalChildren = false) use (&$renderTree, $renderCard): void {
    if ($nodes === [] && !$administrationSpine) return;
?>
    <ul class="org-tree-list<?= $isRoot ? ' org-tree-root' : '' ?><?= $administrationSpine ? ' org-tree-admin-children' : '' ?><?= $verticalChildren ? ' org-tree-vertical' : '' ?>">
        <?php foreach ($nodes as $node): ?>
            <?php
            $children = $node['children'] ?? [];
            $nodeLevel = (int) ($node['level'] ?? 0);
            $nodeType = $node['node_type'] ?? '';
            $adminChildren = [];
            if ($nodeLevel === 1) {
                $adminChildren = array_filter($children, static fn(array $child): bool => ($child['node_type'] ?? '') === 'admin');
                $children = array_filter($children, static fn(array $child): bool => ($child['node_type'] ?? '') !== 'admin');
            }
            $verticalChildList = $nodeType === 'tim'
                || $nodeLevel === 4
                || ($children !== [] && array_reduce(
                    $children,
                    static fn(bool $vertical, array $child): bool => $vertical
                        || ($child['node_type'] ?? '') === 'tim'
                        || (int) ($child['level'] ?? 0) === 4,
                    false
                ));
            ?>
            <li>
                <?php $renderCard($node); ?>
                <?php foreach ($adminChildren as $adminNode): ?>
                    <div class="org-administration"><?php $renderCard($adminNode); ?></div>
                <?php endforeach; ?>
                <?php $renderTree($children, false, $adminChildren !== [], $verticalChildList); ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php
};
?>

<style>
    .org-tree-shell {
        --org-line: #08766f;
        --org-ink: #123d3d;
        position: relative;
    }

    .org-tree {
        display: flex;
        justify-content: center;
        min-width: max-content;
        padding: 1.5rem 1rem 2rem;
    }

    .org-node {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        min-width: 15rem;
    }

    .org-children {
        position: relative;
        display: flex;
        align-items: flex-start;
        gap: 1.25rem;
        margin-top: 2.25rem;
        padding-top: 1.25rem;
    }

    .org-children::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        width: 2px;
        height: 1.25rem;
        background: var(--org-line);
    }

    .org-children>.org-node::before {
        content: '';
        position: absolute;
        top: -1.25rem;
        left: 50%;
        width: 2px;
        height: 1.25rem;
        background: var(--org-line);
    }

    .org-children>.org-node:not(:only-child)::after {
        content: '';
        position: absolute;
        top: -1.25rem;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--org-line);
    }

    .org-children>.org-node:first-child::after {
        left: 50%;
    }

    .org-children>.org-node:last-child::after {
        right: 50%;
    }

    .org-card {
        position: relative;
        display: flex;
        align-items: center;
        gap: .75rem;
        width: 22rem;
        min-height: 6.5rem;
        padding: .9rem;
        overflow: hidden;
        text-align: left;
        background: rgba(255, 255, 255, 1);
        border: 1px solid rgba(15, 85, 87, .14);
        border-radius: 1.15rem;
        box-shadow: 0 8px 20px rgba(15, 85, 87, .07);
        transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
    }

    .org-node[data-level="1"]>.org-card {
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: .5rem;
        width: 19rem;
        height: 9rem;
        border: 2px solid #168b89;
        box-shadow: 0 16px 32px rgba(15, 85, 87, .16);
    }

    .org-node[data-level="1"] .org-card__body {
        align-items: center;
        /* supaya teks jabatan & nama ikut center, bukan rata kiri */
    }

    .org-node[data-level="1"] .org-card__role {
        font-size: 1.2rem;
        /* ukuran jabatan khusus Level 1 */
    }

    .org-node[data-level="1"] .org-card__name {
        font-size: 1.2rem;
        /* ukuran nama khusus Level 1 */
    }

    .org-node[data-level="4"]>.org-card {
        min-height: 5.5rem;
        padding: .7rem .9rem 1.45rem;
    }

    .org-node[data-level="4"] .org-card__body {
        width: 100%;
        align-items: center;
    }

    .org-node[data-level="4"] .org-card__role {
        color: #000000;
        font-size: 1rem;
        line-height: 1.25;
        text-align: center;
    }

    .org-node[data-level="3"]>.org-card {
        width: 22rem;
        min-height: 120px;
    }

    .org-node[data-level="3"] .org-card__role {
        display: block;
        overflow: visible;
        line-height: 1.3;
    }

    .org-card:hover,
    .org-card:focus-visible {
        z-index: 2;
        border-color: #2bc7c0;
        transform: translateY(-5px);
        box-shadow: 0 18px 32px rgba(15, 85, 87, .16);
        outline: none;
    }

    .org-card__shine {
        position: absolute;
        inset: 0 auto 0 -75%;
        width: 45%;
        background: linear-gradient(100deg, transparent, rgba(255, 255, 255, .65), transparent);
        transform: skewX(-18deg);
        transition: left .7s ease;
        pointer-events: none;
    }

    .org-card:hover .org-card__shine,
    .org-card:focus-visible .org-card__shine {
        left: 125%;
    }

    .org-card__avatar {
        display: grid;
        flex: 0 0 auto;
        place-items: center;
        width: 3.25rem;
        height: 3.25rem;
        overflow: hidden;
        color: #064a49;
        font-family: Outfit, sans-serif;
        font-size: 1.25rem;
        font-weight: 800;
        background: linear-gradient(135deg, #d6f8f5, #c7f68a);
        border-radius: .9rem;
    }

    .org-card__avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .org-card__body {
        display: flex;
        min-width: 0;
        flex-direction: column;
        gap: .15rem;
    }

    .org-card__eyebrow {
        color: #08766f;
        font-size: .6rem;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .org-card__name {
        overflow: hidden;
        color: var(--org-ink);
        font-family: sans-serif;
        font-size: .9rem;
        /* Nama ditampilkan berbeda dari jabatan. */
        font-weight: 500;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .org-card__role {
        display: -webkit-box;
        overflow: hidden;
        color: #000000;
        font-size: 1rem;
        /* Jabatan dibuat tebal*/
        font-weight: 700;
        line-height: 1.35;
        line-clamp: 2;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }

    .org-card__count {
        position: absolute;
        right: .65rem;
        bottom: .55rem;
        color: #08766f;
        font-size: .63rem;
        font-weight: 800;
    }

    .org-drawer-backdrop {
        position: absolute;
        inset: 0;
        background: rgba(3, 47, 50, .42);
        backdrop-filter: blur(8px);
    }

    .org-drawer-layer {
        position: fixed;
        z-index: 60;
        inset: 0rem 0 0;
    }

    body.org-drawer-open {
        overflow: hidden;
    }

    [x-cloak] {
        display: none !important;
    }

    .org-drawer {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        display: flex;
        min-height: 0;
        flex-direction: column;
        overflow: hidden;
        width: min(100%, 30rem);
        max-width: 30rem;
    }

    .org-main-layout {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0;
        padding: 1.5rem;
    }

    .org-main-spine {
        position: relative;
        width: min(100%, 70rem);
        /* Controls the vertical distance from Level 1 to the Level 2 branch. */
        height: 10rem;
    }

    .org-main-spine::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 50%;
        border-left: 2px solid var(--org-line);
    }

    .org-administration {
        position: absolute;
        z-index: 3;
        top: calc(6.5rem + 4rem + 3.5rem);
        left: 50%;
        transform: translate(8rem, -50%);
        /*jarak kotak adm ke garis */
    }

    .org-administration::before {
        content: '';
        position: absolute;
        top: 50%;
        right: 100%;
        width: 8rem;
        /* panjang garis horizon administrasi */
        border-top: 2px solid var(--org-line);
    }

    .org-level2-layout {
        position: relative;
        display: flex;
        justify-content: center;
        width: 100%;
        align-items: start;
        overflow-x: auto;
    }

    .org-level2-main {
        position: relative;
        display: flex;
        width: min(100%, 62rem);
        flex-direction: column;
        align-items: center;
        gap: 0;
    }

    .org-level2-branch {
        position: relative;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        /* jarak 2 kotak level 2 penjab */
        gap: 4rem;
        width: 100%;
    }

    .org-level2-branch::before {
        content: '';
        position: absolute;
        top: 50%;
        left: calc(50% - 11.5rem);
        right: calc(50% - 11.5rem);
        border-top: 2px solid var(--org-line);
        /*garis vertikal level 2*/
    }

    .org-level2-branch::after {
        content: '';
        position: absolute;
        top: 0;
        bottom: 50%;
        left: 50%;
        border-left: 2px solid var(--org-line);
    }

    .org-level2-branch .org-node {
        z-index: 1;
    }

    .org-level2-branch>.org-node::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 50%;
        height: 2rem;
        border-left: 2px solid var(--org-line);
        transform: translateX(-50%);
    }

    .org-connector-level2-3 {
        position: relative;
        height: 2rem;
        /*vertikal penghubung*/
    }

    .org-connector-level2-3::before {
        content: '';
        position: absolute;
        top: 1.25rem;
        bottom: 0;
        left: 50%;
        width: 0px;
        /*garis vertikal level 2 ke 3 */
        background: var(--org-line);
        transform: translateX(-50%);
    }


    .org-team-grid {
        position: relative;
        display: grid;
        grid-template-columns: repeat(3, minmax(19rem, 1fr));
        grid-template-rows: 1fr;
        grid-auto-columns: 19rem;
        grid-auto-flow: column;
        gap: 2.5rem;
        width: max-content;
        min-width: min(100%, 70rem);
        padding-top: 2rem;
    }

    .org-team-grid::before {
        content: '';
        position: absolute;
        top: 0;
        left: calc(16.6667% - .8333rem);
        right: calc(16.6667% - .8333rem);
        border-top: 2px solid var(--org-line);
    }

    .org-team-column {
        position: relative;
        display: flex;
        min-width: 0;
        flex-direction: column;
        align-items: flex-start;
        gap: 0rem;
        /*jarak level 3 ke level 4 */
    }

    .org-team-column::before {
        content: '';
        position: absolute;
        top: -2rem;
        left: 50%;
        height: 2rem;
        border-left: 2px solid var(--org-line);
        /* garis vertikal 3 kotak ka*/
    }

    .org-subteam-list {
        position: relative;
        display: flex;
        width: 100%;
        flex-direction: column;
        gap: 1rem;
        padding-left: 1rem;
        /* jarak semua kotak level 4*/
        margin-left: 2rem;
        /* jarak garis vertikal ke horizon level 4 */
        margin-top: 1rem;
        border-left: 2px solid var(--org-line);
    }

    .org-subteam-list::before {
        content: '';
        position: absolute;
        /* Tarik garis ke atas sejauh nilai margin-top (1.5rem) agar menempel ke bawah kotak Level 3 */
        top: -1rem;
        left: -2px;
        /* Menempel presisi di garis border-left */
        height: 1.5rem;
        /* Panjang garis sesuai jarak gap */
        border-left: 2px solid var(--org-line);
    }

    .org-subteam-list .org-node {
        position: relative;
        align-items: stretch;
    }

    .org-subteam-list .org-node::before {
        content: '';
        position: absolute;
        top: 50%;
        left: -1.1rem;
        width: 1.1rem;
        border-top: 2px solid var(--org-line);
        /*garis horizon level 4 */
        transform: translateY(-50%);
    }

    @media (max-width: 767px) {
        .org-main-layout {
            gap: 1rem;
            padding: .25rem;
        }

        .org-level2-layout,
        .org-team-grid {
            display: flex;
            width: 100%;
            flex-direction: column;
            gap: 1rem;
        }

        .org-level2-layout {
            overflow-x: visible;
        }

        .org-team-grid {
            min-width: 0;
        }

        .org-level2-main {
            display: flex;
            width: 100%;
            padding: 0;
            gap: 1rem;
        }

        .org-main-spine {
            width: 100%;
        }

        .org-administration {
            left: 50%;
            transform: translate(0, -50%);
        }

        .org-connector-level2-3 {
            display: none;
        }

        .org-level2-branch {
            flex-direction: column;
            align-items: stretch;
            gap: .65rem;
        }

        .org-level2-branch::before {
            display: none;
        }

        .org-level2-branch::after {
            display: none;
        }

        .org-level2-branch>.org-node::after {
            display: none;
        }

        .org-team-grid::before,
        .org-team-column::before {
            display: none;
        }

        .org-team-column {
            width: 100%;
        }

        .org-subteam-list {
            padding-left: 1rem;
        }

        .org-tree-shell {
            padding: 0 .25rem;
        }

        .org-tree {
            display: grid;
            min-width: 0;
            padding: 0;
        }

        .org-node {
            min-width: 0;
            width: 100%;
        }

        .org-children {
            display: grid;
            width: 100%;
            gap: .65rem;
            margin-top: .65rem;
            padding: .65rem 0 0 1rem;
            border-left: 2px solid var(--org-line);
        }

        .org-children::before,
        .org-children>.org-node::before,
        .org-children>.org-node::after {
            display: none;
        }

        .org-node[data-level="1"]>.org-card {
            flex-direction: column;
            /* dari "display: column;" */
            align-items: center;
            text-align: center;
            gap: .5rem;
            width: 22rem;
            min-height: 7rem;
            /* dari "height: 7rem;" — lihat catatan di bawah */
            border: 2px solid #168b89;
            box-shadow: 0 16px 32px rgba(15, 85, 87, .16);
            background: linear-gradient(135deg, #f5fce8, #fff);

        }

    }

    @media (prefers-reduced-motion: reduce) {

        .org-card,
        .org-card__shine {
            transition: none;
        }
    }
</style>

<style>
    .org-tree-shell {
        overflow-x: clip;
    }

    .org-tree-fit {
        position: relative;
        width: 100%;
    }

    .org-tree {
        position: absolute;
        top: 0;
        left: 50%;
        display: block;
        width: max-content;
        min-width: 0;
        padding: 0;
        transform: translateX(-50%);
        transform-origin: top center;
    }

    .org-tree-list,
    .org-tree-list ul {
        position: relative;
        display: flex;
        justify-content: center;
        min-width: max-content;
        padding: 1.5rem 0 0;
        margin: 0;
        list-style: none;
    }

    .org-tree>.org-tree-list {
        padding-top: 0;
    }

    .org-tree-list li {
        position: relative;
        padding: 1.5rem .75rem 0;
        list-style: none;
    }

    .org-tree>.org-tree-list>li {
        padding-top: 0;
    }

    .org-tree-list li::before,
    .org-tree-list li::after {
        position: absolute;
        top: 0;
        right: 50%;
        width: 50%;
        height: 1.5rem;
        border-top: 2px solid var(--org-line);
        content: '';
    }

    .org-tree-list li::after {
        right: auto;
        left: 50%;
        border-left: 2px solid var(--org-line);
    }

    .org-tree-list ul::before {
        position: absolute;
        top: 0;
        left: 50%;
        height: 1.5rem;
        border-left: 2px solid var(--org-line);
        content: '';
    }

    .org-tree-list.org-tree-admin-children {
        padding-top: calc(8rem + 3rem);
    }

    .org-tree-list.org-tree-admin-children::before {
        height: calc(8rem + 3rem);
    }

    .org-tree-list.org-tree-vertical {
        flex-direction: column;
        align-items: stretch;
        gap: .65rem;
        width: calc(100% - 3.5rem);
        margin-left: 3.5rem;
        /*menggeser kontainer trunk ke kanan */
        min-width: 0;
        margin-top: 1.5rem;
        padding: 0 0 0 1.25rem;
        border-left: 2px solid var(--org-line);
    }

    .org-tree-list.org-tree-vertical::before {
        top: -1.5rem;
        left: -2px;
        height: 1.5rem;
    }

    .org-tree-list.org-tree-vertical .org-node {
        align-items: flex-end;
        min-width: 0;
    }

    .org-tree-list.org-tree-vertical>li {
        padding: 0 0 .65rem 0;
        border-left: 0;
    }

    .org-tree-list.org-tree-vertical>li::before {
        top: calc(50% - .325rem);
        left: -1.25rem;
        right: 0;
        /* dari "width: 1.25rem" */
        width: auto;
        /* biar panjang garis dihitung otomatis, bukan angka tetap */
        height: 0;
        border-top: 2px solid var(--org-line);
        border-right: 0;
    }

    .org-tree-list.org-tree-vertical>li::after {
        display: none;
    }

    .org-tree-list li:first-child::before {
        border-top: 0;
    }

    .org-tree-list.org-tree-vertical>li:first-child::before {
        border-top: 2px solid var(--org-line);
    }

    .org-tree-list li:last-child::after {
        border-top: 0;
    }

    .org-tree-list li:only-child::before {
        display: none;
    }

    .org-tree-list li:only-child::after {
        right: auto;
        left: 50%;
        width: 0;
        border-top: 0;
        border-left: 2px solid var(--org-line);
    }

    .org-tree-root>li::before,
    .org-tree-root>li::after {
        display: none;
    }

    .org-node[data-node-type="tim"]>.org-card { /*ubah ukuran kotak tim */
        width: 16.5rem;
        min-height: 4.5rem;
        padding: .7rem .9rem 1.45rem;
    }

    .org-node[data-node-type="tim"] .org-card__body {
        width: 100%;
        align-items: center;
    }

    .org-node[data-node-type="tim"] .org-card__role {
        text-align: center;
    }

    @media (max-width: 767px) {
        .org-tree {
            position: relative;
            top: auto;
            left: auto;
            width: 100%;
            transform: none;
        }

        .org-tree-list,
        .org-tree-list ul {
            display: flex;
            width: 100%;
            min-width: 0;
            flex-direction: column;
            align-items: stretch;
            gap: .65rem;
            padding: .65rem 0 0 1rem;
        }

        .org-tree>.org-tree-list {
            padding: 0;
        }

        .org-tree-list li,
        .org-tree-list>li {
            width: 100%;
            padding: 0 0 0 1rem;
            border-left: 2px solid var(--org-line);
        }

        .org-tree-root>li {
            padding-left: 0;
            border-left: 0;
        }

        .org-tree-list li::before,
        .org-tree-list li::after,
        .org-tree-list ul::before {
            display: none;
        }

        .org-tree-root>li>.org-tree-admin-children {
            padding-top: 8rem;
        }

        .org-tree-root>li>.org-tree-admin-children::before {
            display: block;
            height: 8rem;
        }

        .org-administration>.org-node {
            width: calc(50% - 1.5rem);
            min-width: 0;
        }

        .org-administration .org-card {
            width: 100%;
        }

        .org-node[data-level="3"]>.org-card {
            width: 100%;
        }

        .org-node,
        .org-card,
        .org-node[data-node-type="tim"]>.org-card {
            width: 100%;
        }
    }
</style>

<section id="organisasi" class="border-y border-slate-200/80 bg-slate-50 py-20 sm:py-24" x-data="strukturDrawer()">
    <div class="mx-auto max-w-8xl px-4 sm:px-8">
        <div class="mx-auto mb-12 max-w-2xl text-center">
            <h2 class="mt-4 font-heading text-3xl font-extrabold text-slate-900 sm:text-4xl">Struktur Organisasi</h2>
            <p class="mt-3 text-sm leading-relaxed text-slate-600">memiliki total 52 staf dengan 17 jam shift</p>
        </div>

        <?php if (!empty($organisasiTree)): ?>
            <div class="org-tree-shell rounded-[1.5rem] border border-slate-200/70 bg-white/60 p-3 shadow-inner shadow-slate-900/5 sm:p-6">
                <div class="org-tree-fit">
                    <div class="org-tree"><?php $renderTree($organisasiTree, true); ?></div>
                </div>
            </div>
        <?php else: ?>
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center text-sm text-slate-500">Data struktur organisasi belum tersedia.</div>
        <?php endif; ?>
    </div>

    <template x-teleport="body">
    <div x-show="drawerOpen" x-cloak class="org-drawer-layer" role="dialog" aria-modal="true" aria-labelledby="struktur-drawer-title" @keydown.escape.window="drawerOpen && closeDrawer()">
        <div class="org-drawer-backdrop absolute inset-0" @click="closeDrawer()"></div>
        <aside class="org-drawer bg-white shadow-2xl" x-show="drawerOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">
            <div class="flex shrink-0 items-start justify-between border-b border-slate-200 px-5 py-5 sm:px-7">
                <div class="pr-4">
                    <p class="text-[11px] font-bold uppercase tracking-widest text-brand-teal-700" x-text="mode === 'staff' ? 'Tim' : 'Profil Pejabat'"></p>
                    <h3 id="struktur-drawer-title" class="mt-1 font-heading text-xl font-extrabold text-slate-900" x-text="parent.jabatan || 'Memuat...'">Memuat...</h3>
                </div>
                <button type="button" class="rounded-xl p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-900" @click="closeDrawer()" aria-label="Tutup rincian staf">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 6l12 12M6 18L18 6" />
                    </svg>
                </button>
            </div>
            <div class="min-h-0 flex-1 overflow-y-auto overscroll-contain px-5 py-5 sm:px-7">
                <div x-show="loading" class="space-y-3" aria-live="polite">
                    <div class="h-16 animate-pulse rounded-2xl bg-slate-100"></div>
                    <div class="h-16 animate-pulse rounded-2xl bg-slate-100"></div>
                </div>
                <div x-show="!loading && error" class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700" x-text="error"></div>
                <div x-show="!loading && !error && staff.length === 0" class="rounded-2xl border border-dashed border-slate-300 p-6 text-center text-sm text-slate-500">Belum ada anggota pada tim ini.</div>
                <div x-show="!loading && !error && staff.length > 0" class="space-y-3">
                    <div class="mb-4 flex items-center justify-between text-xs font-bold uppercase tracking-widest text-slate-500">
                        <span x-text="mode === 'staff' ? 'Anggota Tim' : 'Data diri'"></span>
                        <span x-show="mode === 'staff'" x-text="staff.length + ' Orang'"></span>
                    </div>
                    <template x-for="person in staff" :key="person.id">
                        <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50/70 p-3">
                            <template x-if="person.foto">
                                <img class="h-11 w-11 rounded-xl object-cover" :src="photoUrl(person.foto)" :alt="person.nama">
                            </template>
                            <template x-if="!person.foto">
                                <div class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-brand-mint-100 font-heading font-extrabold text-brand-teal-800" x-text="initial(person.nama)"></div>
                            </template>
                            <div class="min-w-0">
                                <p class="truncate text-sm font-bold text-slate-800" x-text="person.nama"></p>
                                <p class="mt-0.5 text-xs leading-snug text-slate-500" x-text="person.jabatan"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </aside>
    </div>
    </template>
</section>

<script>
    (() => {
        const shell = document.querySelector('.org-tree-shell');
        const fit = shell?.querySelector('.org-tree-fit');
        const tree = shell?.querySelector('.org-tree');
        const rootList = tree?.querySelector(':scope > .org-tree-root');

        if (!shell || !fit || !tree || !rootList) return;

        const fitTreeToShell = () => {
            tree.style.transform = window.matchMedia('(max-width: 767px)').matches ?
                'none' :
                'translateX(-50%)';
            fit.style.height = 'auto';

            const shellStyle = getComputedStyle(shell);
            const availableWidth = shell.clientWidth -
                parseFloat(shellStyle.paddingLeft) -
                parseFloat(shellStyle.paddingRight);
            const diagramWidth = tree.offsetWidth;
            const scale = diagramWidth > 0 ? Math.min(1, availableWidth / diagramWidth) : 1;

            tree.style.transform = window.matchMedia('(max-width: 767px)').matches ?
                `scale(${scale})` :
                `translateX(-50%) scale(${scale})`;
            fit.style.height = `${tree.offsetHeight * scale}px`;
        };

        // Sesuaikan skala diagram agar seluruh bagan muat di dalam pembungkus.
        const resizeObserver = new ResizeObserver(fitTreeToShell);
        resizeObserver.observe(shell);
        window.addEventListener('resize', fitTreeToShell, {
            passive: true
        });
        fitTreeToShell();
        document.fonts?.ready.then(fitTreeToShell);
    })();

    function strukturDrawer() {
        return {
            drawerOpen: false,
            loading: false,
            error: '',
            parent: {},
            staff: [],
            mode: 'profile',
            endpoint: <?= json_encode(base_url('struktur/staf'), JSON_UNESCAPED_SLASHES) ?>,
            openDrawer(id) {
                this.loadDrawer(this.endpoint + '/' + id);
            },
            loadDrawer(url) {
                this.drawerOpen = true;
                document.body.classList.add('org-drawer-open');
                this.loading = true;
                this.error = '';
                this.parent = {};
                this.staff = [];
                this.mode = 'profile';
                fetch(url, {
                        headers: {
                            Accept: 'application/json'
                        }
                    })
                    .then(response => response.ok ? response.json() : Promise.reject(new Error('Gagal mengambil data staf.')))
                    .then(payload => {
                        if (!payload.success) throw new Error(payload.message || 'Data staf tidak tersedia.');
                        this.parent = payload.parent;
                        this.staff = payload.staff || [];
                        this.mode = payload.mode || 'profile';
                    })
                    .catch(error => {
                        this.error = error.message;
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            },
            closeDrawer() {
                this.drawerOpen = false;
                document.body.classList.remove('org-drawer-open');
            },
            initial(name) {
                return (name || '?').trim().charAt(0).toUpperCase();
            },
            photoUrl(photo) {
                return <?= json_encode(rtrim(base_url('uploads/organisasi'), '/') . '/', JSON_UNESCAPED_SLASHES) ?> + encodeURIComponent(photo);
            }
        };
    }
</script>