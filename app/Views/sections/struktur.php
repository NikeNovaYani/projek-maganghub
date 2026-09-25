<?php

$renderCard = function (array $node): void {
    $initial = strtoupper(substr(trim($node['nama']), 0, 1));
    $level = (int) ($node['level'] ?? 1);
    $levelLabel = match ($level) {
        1 => 'Pimpinan Unit',
        2 => 'Penanggung Jawab',
        3 => 'Ketua Tim',
        default => 'Staf Pelaksana',
    };
?>
    <div class="org-node" data-level="<?= $level ?>" data-layout="<?= esc($node['layout_type'] ?? 'main') ?>">
        <button type="button"
            class="org-card group"
            @click="openDrawer(<?= (int) $node['id'] ?>)"
            aria-label="Lihat staf di bawah <?= esc($node['jabatan']) ?>">
            <span class="org-card__shine" aria-hidden="true"></span>
            <span class="org-card__avatar">
                <?php if (!empty($node['foto'])): ?>
                    <img src="<?= base_url('uploads/organisasi/' . rawurlencode($node['foto'])) ?>" alt="<?= esc($node['nama']) ?>">
                <?php else: ?>
                    <?= esc($initial) ?>
                <?php endif; ?>
            </span>
            <span class="org-card__body">
                <span class="org-card__role"><?= esc($node['jabatan']) ?></span>
                <?php if ($level < 4): ?>
                    <span class="org-card__name"><?= esc($node['nama']) ?></span>
                <?php endif; ?>
            </span>
            <?php if ($level === 4): ?>
                <span class="org-card__count"><?= (int) $node['total_staf'] ?> Orang</span>
            <?php endif; ?>
        </button>
    </div>
<?php
};

$root = $organisasiTree[0] ?? null;
$level2Nodes = $root['children'] ?? [];
$sideNodes = array_values(array_filter($level2Nodes, fn (array $node): bool => ($node['layout_type'] ?? 'main') === 'side'));
$administration = $sideNodes[0] ?? null;
$mainNodes = array_values(array_filter($level2Nodes, fn (array $node): bool => ($node['layout_type'] ?? 'main') !== 'side'));
$teamNodes = [];
foreach ($mainNodes as $mainNode) {
    $teamNodes = array_merge($teamNodes, $mainNode['children'] ?? []);
}
?>

<style>
    .org-tree-shell {
        --org-line: rgba(8, 118, 111, .25);
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
        width: 1px;
        height: 1.25rem;
        background: var(--org-line);
    }

    .org-children>.org-node::before {
        content: '';
        position: absolute;
        top: -1.25rem;
        left: 50%;
        width: 1px;
        height: 1.25rem;
        background: var(--org-line);
    }

    .org-children>.org-node:not(:only-child)::after {
        content: '';
        position: absolute;
        top: -1.25rem;
        left: 0;
        right: 0;
        height: 1px;
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
        width: 15rem;
        min-height: 6.5rem;
        padding: .9rem;
        overflow: hidden;
        text-align: left;
        background: rgba(255, 255, 255, .96);
        border: 1px solid rgba(15, 85, 87, .14);
        border-radius: 1.15rem;
        box-shadow: 0 8px 20px rgba(15, 85, 87, .07);
        transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
    }

    .org-node[data-level="1"]>.org-card {
        width: 19rem;
        border: 2px solid #168b89;
        box-shadow: 0 16px 32px rgba(15, 85, 87, .16);
    }

    .org-node[data-level="4"]>.org-card {
        justify-content: space-between;
        min-height: 4.5rem;
        padding: .7rem .9rem 1.45rem;
    }

    .org-node[data-level="4"] .org-card__avatar {
        display: none;
    }

    .org-node[data-level="4"] .org-card__body {
        width: 100%;
        align-items: center;
    }

    .org-node[data-level="4"] .org-card__role {
        color: #000000;
        font-size: .86rem;
        font-weight: 500;
        line-height: 1.25;
        text-align: center;
    }

    .org-node[data-level="3"]>.org-card {
        width: 19rem;
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
        font-family: Outfit, sans-serif;
        font-size: .88rem;
        font-weight: 800;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .org-card__role {
        display: -webkit-box;
        overflow: hidden;
        color: #527171;
        font-size: .7rem;
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
        background: rgba(3, 47, 50, .42);
        backdrop-filter: blur(3px);
    }

    [x-cloak] {
        display: none !important;
    }

    .org-drawer {
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
        height: 8.5rem;
    }

    .org-main-spine::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 50%;
        border-left: 1px solid var(--org-line);
    }

    .org-administration {
        position: absolute;
        z-index: 3;
        top: 50%;
        left: 50%;
        transform: translate(2.5rem, -50%);
    }

    .org-administration::before {
        content: '';
        position: absolute;
        top: 50%;
        right: 100%;
        width: 2.5rem;
        border-top: 1px solid var(--org-line);
    }

    .org-level2-layout {
        position: relative;
        display: flex;
        justify-content: center;
        width: 100%;
        align-items: start;
    }

    .org-level2-main {
        position: relative;
        display: flex;
        width: min(100%, 62rem);
        flex-direction: column;
        align-items: center;
        gap: 2rem;
    }

    .org-level2-branch {
        position: relative;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        gap: 1rem;
        width: 100%;
    }

    .org-level2-branch::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        border-top: 1px solid var(--org-line);
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

    .org-level2-to-level3-line {
        position: relative;
        width: 100%;
        height: 2rem;
    }

    .org-level2-to-level3-line::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        height: 100%;
        border-left: 2px solid var(--org-line);
    }

    .org-team-grid {
        position: relative;
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 2.5rem;
        width: min(100%, 70rem);
        padding-top: 2rem;
    }

    .org-team-grid::before {
        content: '';
        position: absolute;
        top: 0;
        left: 16.66%;
        right: 16.66%;
        border-top: 1px solid var(--org-line);
    }

    .org-team-column {
        position: relative;
        display: flex;
        min-width: 0;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }

    .org-team-column::before {
        content: '';
        position: absolute;
        top: -2rem;
        left: 50%;
        height: 2rem;
        border-left: 1px solid var(--org-line);
    }

    .org-subteam-list {
        display: flex;
        width: 100%;
        flex-direction: column;
        gap: .7rem;
        padding-left: 1.1rem;
        border-left: 2px solid var(--org-line);
    }

    .org-subteam-list .org-node {
        align-items: stretch;
    }

    .org-subteam-list .org-node::before {
        content: '';
        position: absolute;
        top: 50%;
        left: -1.1rem;
        width: 1.1rem;
        border-top: 2px solid var(--org-line);
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

        .org-level2-main {
            display: flex;
            width: 100%;
            padding: 0;
        }

        .org-main-spine {
            width: 100%;
        }

        .org-administration {
            left: 50%;
            transform: translate(1rem, -50%);
        }

        .org-level2-to-level3-line {
            display: none;
        }

        .org-level2-branch {
            flex-direction: column;
            align-items: stretch;
        }

        .org-level2-branch::before {
            display: none;
        }

        .org-level2-branch::after {
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
            border-left: 1px solid var(--org-line);
        }

        .org-children::before,
        .org-children>.org-node::before,
        .org-children>.org-node::after {
            display: none;
        }

        .org-card,
        .org-node[data-level="1"]>.org-card {
            width: 100%;
            min-height: 5.5rem;
        }

        .org-node[data-level="1"]>.org-card {
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

<section id="organisasi" class="border-y border-slate-200/80 bg-slate-50 py-20 sm:py-24" x-data="strukturDrawer()">
    <div class="mx-auto max-w-7xl px-4 sm:px-8">
        <div class="mx-auto mb-12 max-w-2xl text-center">
            <h2 class="mt-4 font-heading text-3xl font-extrabold text-slate-900 sm:text-4xl">Struktur Organisasi</h2>
            <p class="mt-3 text-sm leading-relaxed text-slate-600">Pilih posisi untuk melihat staf yang berada langsung di bawah koordinasinya.</p>
        </div>

        <?php if (!empty($organisasiTree)): ?>
            <div class="org-tree-shell rounded-[1.5rem] border border-slate-200/70 bg-white/60 p-3 shadow-inner shadow-slate-900/5 sm:p-6">
                <div class="org-main-layout">
                    <?php if ($root): ?>
                        <?php $renderCard($root); ?>
                        <div class="org-main-spine">
                            <?php if ($administration): ?>
                                <div class="org-administration">
                                    <?php $renderCard($administration); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="org-level2-layout">
                            <div class="org-level2-main">
                                <div class="org-level2-branch">
                                    <?php foreach ($mainNodes as $mainNode): ?>
                                        <?php $renderCard($mainNode); ?>
                                    <?php endforeach; ?>
                                </div>
                                <div class="org-level2-to-level3-line" aria-hidden="true"></div>
                                <?php if (!empty($teamNodes)): ?>
                                    <div class="org-team-grid">
                                        <?php foreach ($teamNodes as $teamNode): ?>
                                            <div class="org-team-column">
                                                <?php $renderCard($teamNode); ?>
                                                <?php if (!empty($teamNode['children'])): ?>
                                                    <div class="org-subteam-list">
                                                        <?php foreach ($teamNode['children'] as $subteamNode): ?>
                                                            <?php $renderCard($subteamNode); ?>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center text-sm text-slate-500">Data struktur organisasi belum tersedia.</div>
        <?php endif; ?>
    </div>

    <div x-show="drawerOpen" x-cloak class="fixed inset-0 z-50" role="dialog" aria-modal="true" aria-labelledby="struktur-drawer-title">
        <div class="org-drawer-backdrop absolute inset-0" @click="closeDrawer()"></div>
        <aside class="org-drawer absolute right-0 top-0 flex h-full flex-col bg-white shadow-2xl" x-show="drawerOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">
            <div class="flex items-start justify-between border-b border-slate-200 px-5 py-5 sm:px-7">
                <div class="pr-4">
                    <p class="text-[11px] font-bold uppercase tracking-widest text-brand-teal-700">Rincian Tim</p>
                    <h3 id="struktur-drawer-title" class="mt-1 font-heading text-xl font-extrabold text-slate-900" x-text="parent.jabatan || 'Memuat...'">Memuat...</h3>
                    <p class="mt-1 text-sm text-slate-500" x-text="parent.nama"></p>
                </div>
                <button type="button" class="rounded-xl p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-900" @click="closeDrawer()" aria-label="Tutup rincian staf">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 6l12 12M6 18L18 6" />
                    </svg>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto px-5 py-5 sm:px-7">
                <div x-show="loading" class="space-y-3" aria-live="polite">
                    <div class="h-16 animate-pulse rounded-2xl bg-slate-100"></div>
                    <div class="h-16 animate-pulse rounded-2xl bg-slate-100"></div>
                </div>
                <div x-show="!loading && error" class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700" x-text="error"></div>
                <div x-show="!loading && !error && staff.length === 0" class="rounded-2xl border border-dashed border-slate-300 p-6 text-center text-sm text-slate-500">Belum ada staf langsung di bawah posisi ini.</div>
                <div x-show="!loading && !error && staff.length > 0" class="space-y-3">
                    <div class="mb-4 flex items-center justify-between text-xs font-bold uppercase tracking-widest text-slate-500">
                        <span x-text="mode === 'profile' ? 'Data diri' : 'Staf level 5'"></span><span x-show="mode !== 'profile'" x-text="staff.length + ' Orang'"></span>
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
</section>

<script>
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
                this.drawerOpen = true;
                this.loading = true;
                this.error = '';
                this.parent = {};
                this.staff = [];
                this.mode = 'profile';
                fetch(this.endpoint + '/' + id, {
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