<?= $this->extend('layouts/admin') ?>

<?= $this->section('page_heading') ?>Struktur Organisasi<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
/** @var list<array<string, mixed>> $tree */
$deleteWarning = session()->getFlashdata('delete_warning');
$message = session()->getFlashdata('message');
$errorMessage = session()->getFlashdata('error');

$renderCard = function (array $node): void {
    $level = (int) ($node['level'] ?? 1);
    $nodeType = $node['node_type'] ?? 'pejabat';
    $isGroup = $nodeType === 'tim';
    $initial = strtoupper(substr(trim($node['jabatan'] ?? '?'), 0, 1));
    $isLeader = in_array($nodeType, ['kepala', 'penjab', 'katim', 'pejabat'], true);
?>
    <div class="org-node" data-level="<?= $level ?>" data-node-type="<?= esc($nodeType) ?>" x-data="{ menuOpen: false, staffOpen: false }">
        <div class="org-card">
            <?php if (!$isGroup): ?>
                <span class="org-card__avatar"><?= esc($initial) ?></span>
            <?php endif; ?>

            <span class="org-card__body"
                <?= $isGroup ? '@click="staffOpen = !staffOpen" style="cursor:pointer"' : '' ?>>
                <span class="org-card__role"><?= esc($node['jabatan']) ?></span>
                <?php if (!$isGroup): ?>
                    <span class="org-card__name"><?= esc($node['nama'] ?? '') ?></span>
                <?php endif; ?>
            </span>

            <?php if ($isGroup): ?>
                <span class="org-card__count"><?= (int) $node['total_anggota'] ?> Orang</span>
            <?php endif; ?>

            <button type="button" class="org-admin-kebab" @click.stop="menuOpen = !menuOpen" @click.outside="menuOpen = false" aria-label="Aksi node">⋮</button>

            <div x-show="menuOpen" x-cloak x-transition class="org-admin-menu" @click.stop>
                <?php if ($isLeader && $level < 3): ?>
                    <a href="<?= base_url('admin/organisasi/create?parent_id=' . $node['id']) ?>" data-org-modal="<?= $level === 1 ? 'Tambah Penjab' : 'Tambah Ka Tim' ?>" class="org-admin-menu__item"><?= $level === 1 ? '+ Penjab' : '+ Ka Tim' ?></a>
                <?php elseif ($isLeader && $level === 3): ?>
                    <a href="<?= base_url('admin/organisasi/create?parent_id=' . $node['id'] . '&node_type=tim') ?>" data-org-modal="Tambah Tim" class="org-admin-menu__item">+ Tim</a>
                <?php endif; ?>
                <?php if ($isGroup): ?>
                    <a href="<?= base_url('admin/organisasi/anggota/create/' . $node['id']) ?>" data-org-modal="Tambah Staf" class="org-admin-menu__item">+ Staf</a>
                <?php endif; ?>
                <a href="<?= base_url('admin/organisasi/edit/' . $node['id']) ?>" data-org-modal="Edit Posisi" class="org-admin-menu__item">Edit</a>
                <form action="<?= base_url('admin/organisasi/move/' . $node['id'] . '/up') ?>" method="post" class="org-admin-menu__form">
                    <?= csrf_field() ?>
                    <button type="submit" class="org-admin-menu__item">Naik</button>
                </form>
                <form action="<?= base_url('admin/organisasi/move/' . $node['id'] . '/down') ?>" method="post" class="org-admin-menu__form">
                    <?= csrf_field() ?>
                    <button type="submit" class="org-admin-menu__item">Turun</button>
                </form>
                <form action="<?= base_url('admin/organisasi/delete/' . $node['id']) ?>" method="post" class="org-admin-menu__form" data-delete-form data-position-name="<?= esc($node['jabatan'], 'attr') ?>">
                    <?= csrf_field() ?>
                    <button type="submit" class="org-admin-menu__item org-admin-menu__item--danger">Hapus</button>
                </form>
            </div>
        </div>

        <?php if ($isGroup): ?>
            <div x-show="staffOpen" x-cloak x-transition class="org-admin-staff">
                <p class="org-admin-staff__title">Anggota (<?= count($node['anggota']) ?>)</p>
                <?php foreach ($node['anggota'] as $member): ?>
                    <div class="org-admin-staff__row">
                        <div class="min-w-0">
                            <p class="org-admin-staff__name"><?= esc($member['nama']) ?></p>
                            <p class="org-admin-staff__role"><?= esc($member['jabatan']) ?></p>
                        </div>
                        <div class="org-admin-staff__actions">
                            <a href="<?= base_url('admin/organisasi/anggota/edit/' . $node['id'] . '/' . $member['id']) ?>" data-org-modal="Edit Staf">Edit</a>
                            <form action="<?= base_url('admin/organisasi/anggota/delete/' . $node['id'] . '/' . $member['id']) ?>" method="post" data-delete-form data-position-name="<?= esc($member['nama'], 'attr') ?>">
                                <?= csrf_field() ?>
                                <button type="submit">Hapus</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
                <a href="<?= base_url('admin/organisasi/anggota/create/' . $node['id']) ?>" data-org-modal="Tambah Staf" class="org-admin-staff__add">+ Tambah Staf</a>
            </div>
        <?php endif; ?>
    </div>
<?php
};

$renderTree = function (array $nodes, bool $isRoot = false, bool $verticalChildren = false) use (&$renderTree, $renderCard): void {
    if ($nodes === []) return;
?>
    <ul class="org-tree-list<?= $isRoot ? ' org-tree-root' : '' ?><?= $verticalChildren ? ' org-tree-vertical' : '' ?>">
        <?php foreach ($nodes as $node): ?>
            <?php
            $children = $node['children'] ?? [];
            $nodeLevel = (int) ($node['level'] ?? 0);
            $nodeType = $node['node_type'] ?? '';
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
                <?php $renderTree($children, false, $verticalChildList); ?>
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

    .org-tree-list li {
        position: relative;
        padding: 1.5rem .75rem 0;
        list-style: none;
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

    .org-tree-list.org-tree-vertical {
        flex-direction: column;
        align-items: stretch;
        gap: .65rem;
        width: 16.5rem;
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
        width: auto;
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

    .org-node {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        min-width: 15rem;
    }

    .org-card {
        position: relative;
        display: flex;
        align-items: center;
        gap: .75rem;
        width: 20rem;
        min-height: 6rem;
        padding: .9rem 2.25rem .9rem .9rem;
        background: #fff;
        border: 1px solid rgba(15, 85, 87, .14);
        border-radius: 1.15rem;
        box-shadow: 0 8px 20px rgba(15, 85, 87, .07);
    }

    .org-node[data-level="1"]>.org-card {
        border: 2px solid #168b89;
    }

    .org-node[data-node-type="tim"]>.org-card {
        width: 15rem;
        min-height: 4.5rem;
        padding: .7rem 2.25rem .7rem .9rem;
    }

    .org-node[data-node-type="tim"] .org-card__body {
        width: 100%;
        align-items: center;
    }

    .org-node[data-node-type="tim"] .org-card__role {
        text-align: center;
    }

    .org-card__avatar {
        display: grid;
        flex: 0 0 auto;
        place-items: center;
        width: 2.75rem;
        height: 2.75rem;
        color: #064a49;
        font-weight: 800;
        background: linear-gradient(135deg, #d6f8f5, #c7f68a);
        border-radius: .75rem;
    }

    .org-card__body {
        display: flex;
        min-width: 0;
        flex-direction: column;
        gap: .15rem;
    }

    .org-card__role {
        overflow: hidden;
        color: #000;
        font-size: .9rem;
        font-weight: 700;
        line-height: 1.3;
    }

    .org-card__name {
        overflow: hidden;
        color: var(--org-ink);
        font-size: .82rem;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .org-card__count {
        font-size: .68rem;
        font-weight: 800;
        color: #08766f;
    }

    .org-admin-kebab {
        position: absolute;
        top: .5rem;
        right: .5rem;
        width: 1.6rem;
        height: 1.6rem;
        line-height: 1.6rem;
        text-align: center;
        border-radius: .5rem;
        color: #475569;
    }

    .org-admin-kebab:hover {
        background: #f1f5f9;
    }

    .org-admin-menu {
        position: absolute;
        top: 2.1rem;
        right: .5rem;
        z-index: 20;
        display: flex;
        flex-direction: column;
        min-width: 9rem;
        padding: .35rem;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: .75rem;
        box-shadow: 0 10px 24px rgba(15, 23, 42, .12);
    }

    .org-admin-menu__item,
    .org-admin-menu__form button {
        width: 100%;
        padding: .4rem .6rem;
        text-align: left;
        font-size: .78rem;
        font-weight: 600;
        color: #0f172a;
        border-radius: .5rem;
    }

    .org-admin-menu__item:hover,
    .org-admin-menu__form button:hover {
        background: #f1f5f9;
    }

    .org-admin-menu__item--danger {
        color: #be123c;
    }

    .org-admin-staff {
        width: 15rem;
        margin-top: .5rem;
        padding: .6rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: .9rem;
    }

    .org-admin-staff__title {
        margin-bottom: .4rem;
        font-size: .68rem;
        font-weight: 800;
        text-transform: uppercase;
        color: #64748b;
    }

    .org-admin-staff__row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .5rem;
        padding: .4rem 0;
        border-top: 1px solid #e2e8f0;
    }

    .org-admin-staff__row:first-of-type {
        border-top: 0;
    }

    .org-admin-staff__name {
        font-size: .78rem;
        font-weight: 700;
        color: #1e293b;
    }

    .org-admin-staff__role {
        font-size: .7rem;
        color: #64748b;
    }

    .org-admin-staff__actions {
        display: flex;
        gap: .5rem;
        font-size: .72rem;
        font-weight: 700;
    }

    .org-admin-staff__actions a {
        color: #0f766e;
    }

    .org-admin-staff__actions button {
        color: #be123c;
    }

    .org-admin-staff__add {
        display: block;
        margin-top: .5rem;
        font-size: .75rem;
        font-weight: 700;
        color: #0f766e;
        text-align: center;
    }

    .org-admin-zoom-shell {
        position: relative;
        overflow: hidden;
        min-height: 38rem;
        cursor: grab;
    }

    .org-admin-zoom-shell.is-dragging {
        cursor: grabbing;
    }

    .org-admin-zoom-stage {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        display: flex;
        justify-content: center;
        transform-origin: top left;
        transition: transform .5s ease-out;
    }

    .org-admin-zoom-stage.is-panning {
        transition: none;
        /* BARU — mati total pas lagi digeser, supaya tetap responsif */
    }

    .org-admin-zoom-controls {
        position: sticky;
        top: .75rem;
        left: .75rem;
        z-index: 30;
        display: inline-flex;
        align-items: center;
        gap: .25rem;
        margin: .75rem;
        padding: .25rem;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: .75rem;
        box-shadow: 0 6px 16px rgba(15, 23, 42, .08);
    }

    .org-admin-zoom-controls button {
        width: 2rem;
        height: 2rem;
        font-weight: 700;
        color: #0f172a;
        border-radius: .5rem;
    }

    .org-admin-zoom-controls button:hover {
        background: #f1f5f9;
    }

    .org-admin-zoom-controls span {
        padding: 0 .4rem;
        font-size: .75rem;
        font-weight: 700;
        color: #475569;
    }

    /* ===== Modal form organisasi ===== */
    .org-modal-backdrop {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        background: rgba(15, 23, 42, .45);
        -webkit-backdrop-filter: blur(6px);
        backdrop-filter: blur(6px);
    }

    /* modal crop foto harus berada di atas modal form */
    .org-modal-backdrop--crop {
        z-index: 10000;
        background: rgba(2, 6, 23, .6);
    }
</style>

<div class="space-y-5" x-data="orgAdminZoom()">
    <?php if ($message): ?><p role="status" class="rounded-lg border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-800"><?= esc($message) ?></p><?php endif; ?>
    <?php if ($errorMessage): ?><p role="alert" class="rounded-lg border border-rose-200 bg-rose-50 p-3 text-sm text-rose-800"><?= esc($errorMessage) ?></p><?php endif; ?>
    <?php if ($deleteWarning): ?><p role="alert" class="rounded-lg border border-amber-200 bg-amber-50 p-3 text-sm text-amber-800"><?= esc($deleteWarning) ?></p><?php endif; ?>

    <div class="org-tree-shell org-admin-zoom-shell rounded-xl border border-slate-200 bg-slate-50"
        :class="{ 'is-dragging': dragging }"
        @wheel.prevent="zoom($event.deltaY < 0 ? 0.1 : -0.1)"
        @mousedown="startDrag($event)"
        @mousemove.window="onDrag($event)"
        @mouseup.window="stopDrag()"
        @mouseleave="stopDrag()">
        <div class="org-admin-zoom-controls">
            <button type="button" @click="zoom(-0.1)" aria-label="Perkecil">−</button>
            <span x-text="Math.round(scale * 100) + '%'"></span>
            <button type="button" @click="zoom(0.1)" aria-label="Perbesar">+</button>
            <button type="button" @click="reset()" class="text-xs">Reset</button>
        </div>

        <?php if ($tree === []): ?>
            <p class="py-10 text-center text-sm text-slate-500">Belum ada node organisasi.</p>
        <?php else: ?>
            <div class="org-admin-zoom-stage p-4"
                :class="{ 'is-panning': dragging }"
                :style="`transform: translate(${panX}px, ${panY}px) scale(${scale})`">
                <?php $renderTree($tree, true); ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- ============ MODAL FORM (Edit / Tambah Posisi / Staf) ============ -->
<div x-data="orgModal()" x-cloak>
    <div x-show="open" x-transition.opacity
        @keydown.escape.window="cropOpen ? closeCrop() : (open && close())"
        @click.self="close()"
        class="org-modal-backdrop"
        role="dialog" aria-modal="true" aria-labelledby="org-modal-title">
        <div class="flex max-h-[90vh] w-full max-w-3xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                <h2 id="org-modal-title" class="font-heading text-base font-bold text-slate-900" x-text="title"></h2>
                <button type="button" @click="close()" class="grid h-9 w-9 place-items-center rounded-lg text-xl text-slate-500 hover:bg-slate-100" aria-label="Tutup">&times;</button>
            </div>
            <div class="overflow-y-auto" x-ref="scroller">
                <p x-show="loading" class="p-6 text-sm text-slate-500">Memuat form...</p>
                <div x-ref="body"></div>
            </div>
        </div>
    </div>

    <!-- Crop foto -->
    <div x-show="cropOpen" class="org-modal-backdrop org-modal-backdrop--crop" role="dialog" aria-modal="true" aria-labelledby="crop-title">
        <div class="w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                <h2 id="crop-title" class="font-heading text-base font-bold text-slate-900">Potong Foto Profil</h2>
                <button type="button" @click="closeCrop()" class="grid h-9 w-9 place-items-center rounded-lg text-xl text-slate-500 hover:bg-slate-100" aria-label="Tutup">&times;</button>
            </div>
            <div class="max-h-[65vh] overflow-hidden bg-slate-950 p-3">
                <img x-ref="cropImage" alt="Foto yang akan dipotong" class="block max-h-[60vh] max-w-full">
            </div>
            <div class="flex justify-end gap-3 px-5 py-4">
                <button type="button" @click="closeCrop()" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</button>
                <button type="button" @click="applyCrop()" class="rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-teal-800">Gunakan Foto</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
<script>
    function orgAdminZoom() {
        return {
            scale: 1,
            panX: 0,
            panY: 0,
            dragging: false,
            startX: 0,
            startY: 0,
            startPanX: 0,
            startPanY: 0,
            zoom(delta) {
                this.scale = Math.min(1.5, Math.max(0.5, +(this.scale + delta).toFixed(2)));
            },
            reset() {
                this.scale = 1;
                this.panX = 0;
                this.panY = 0;
            },
            startDrag(e) {
                // Jangan mulai drag kalau yang diklik tombol/link/form (biar tetap bisa diklik normal)
                if (e.target.closest('.org-card, .org-admin-menu, .org-admin-staff, button, a, form')) return;
                this.dragging = true;
                this.startX = e.clientX;
                this.startY = e.clientY;
                this.startPanX = this.panX;
                this.startPanY = this.panY;
            },
            onDrag(e) {
                if (!this.dragging) return;
                this.panX = this.startPanX + (e.clientX - this.startX);
                this.panY = this.startPanY + (e.clientY - this.startY);
            },
            stopDrag() {
                this.dragging = false;
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

    // ================= MODAL FORM ORGANISASI =================
    function orgModal() {
        // Objek DOM / Cropper disimpan di luar state Alpine supaya tidak dibungkus Proxy
        let cropper = null;
        let cropSrc = null;
        let previewUrl = null;
        let photoInput = null;
        let previewEl = null;
        let placeholderEl = null;

        const FORM_SELECTOR = 'form[enctype="multipart/form-data"]';

        return {
            open: false,
            loading: false,
            saving: false,
            stale: false, // true = sudah pernah submit & gagal -> token CSRF di halaman perlu disegarkan saat modal ditutup
            title: '',
            cropOpen: false,

            init() {
                // capture = true, karena menu ⋮ memakai @click.stop yang menghentikan bubbling ke document
                document.addEventListener('click', (e) => {
                    const link = e.target.closest('a[data-org-modal]');
                    if (!link) return;
                    e.preventDefault();
                    this.show(link.href, link.dataset.orgModal);
                }, true);

                // Toast sukses setelah halaman reload
                const flash = sessionStorage.getItem('orgFlash');
                if (flash) {
                    sessionStorage.removeItem('orgFlash');
                    this.toast(flash);
                }
            },

            toast(msg) {
                if (typeof Swal === 'undefined') return;
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: msg,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
            },

            async show(url, title = '') {
                this.title = title;
                this.open = true;
                this.loading = true;
                this.stale = false;
                this.$refs.body.innerHTML = '';
                try {
                    const res = await fetch(url, {
                        credentials: 'same-origin'
                    });
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    this.render(await res.text());
                } catch (err) {
                    this.showError(`Gagal memuat form (${err.message}). Muat ulang halaman lalu coba lagi.`);
                } finally {
                    this.loading = false;
                }
            },

            // Ambil <form> (dan kotak error) dari HTML halaman form lama, lalu tampilkan di modal
            render(html) {
                const body = this.$refs.body;
                const doc = new DOMParser().parseFromString(html, 'text/html');
                const form = doc.querySelector(FORM_SELECTOR);
                if (!form) {
                    this.showError('Form tidak ditemukan. Sesi mungkin habis, muat ulang halaman atau login ulang.');
                    return;
                }

                const alertBox = form.parentElement.querySelector(':scope > [role="alert"]');
                const f = document.importNode(form, true);
                f.className = 'space-y-5 p-5 sm:p-6';

                body.innerHTML = '';
                if (alertBox) body.appendChild(document.importNode(alertBox, true));
                body.appendChild(f);
                this.$refs.scroller.scrollTop = 0;

                // tombol "Batal" (link) menutup modal
                f.querySelectorAll('a').forEach((a) =>
                    a.addEventListener('click', (e) => {
                        e.preventDefault();
                        this.close();
                    }));

                f.addEventListener('submit', (e) => {
                    e.preventDefault();
                    this.submit(f);
                });

                this.initNodeFields(f);
                this.initPhoto(f);
            },

            showError(msg) {
                const body = this.$refs.body;
                const box = document.createElement('div');
                box.setAttribute('role', 'alert');
                box.className = 'm-5 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800';
                box.textContent = msg;
                body.prepend(box);
                this.$refs.scroller.scrollTop = 0;
            },

            // Logika yang sama dengan script di form.php lama
            initNodeFields(form) {
                const type = form.querySelector('#node_type');
                if (!type) return; // member_form tidak punya field ini
                const nameField = form.querySelector('#name-field');
                const nameInput = form.querySelector('#nama');
                const parent = form.querySelector('#parent_id');
                const update = () => {
                    const isGroup = type.value === 'tim';
                    nameField.hidden = isGroup;
                    nameInput.required = !isGroup;
                    parent.required = type.value !== 'pejabat' || parent.value !== '';
                };
                type.addEventListener('change', update);
                parent.addEventListener('change', update);
                update();
            },

            // Cropper hanya untuk form posisi (yang punya #photo-preview), sama seperti kode lama
            initPhoto(form) {
                const input = form.querySelector('#foto');
                const preview = form.querySelector('#photo-preview');
                if (!input || !preview) return;
                const placeholder = form.querySelector('#photo-placeholder');

                input.addEventListener('change', () => {
                    const file = input.files[0];
                    if (!file) return;
                    input.value = '';
                    photoInput = input;
                    previewEl = preview;
                    placeholderEl = placeholder;

                    if (cropSrc) URL.revokeObjectURL(cropSrc);
                    cropSrc = URL.createObjectURL(file);
                    this.cropOpen = true;

                    this.$nextTick(() => {
                        const img = this.$refs.cropImage;
                        img.onload = () => {
                            if (cropper) cropper.destroy();
                            cropper = new Cropper(img, {
                                aspectRatio: 1,
                                viewMode: 1,
                                autoCropArea: 1,
                                responsive: true,
                                background: false,
                            });
                        };
                        img.src = cropSrc;
                    });
                });
            },

            applyCrop() {
                if (!cropper) return;
                cropper.getCroppedCanvas({
                    width: 512,
                    height: 512,
                    imageSmoothingQuality: 'high',
                }).toBlob((blob) => {
                    if (!blob) return;
                    const file = new File([blob], 'profil.jpg', {
                        type: 'image/jpeg'
                    });
                    const transfer = new DataTransfer();
                    transfer.items.add(file);
                    photoInput.files = transfer.files;

                    if (previewUrl) URL.revokeObjectURL(previewUrl);
                    previewUrl = URL.createObjectURL(blob);
                    previewEl.src = previewUrl;
                    previewEl.classList.remove('hidden');
                    if (placeholderEl) placeholderEl.classList.add('hidden');
                    this.closeCrop();
                }, 'image/jpeg', 0.86);
            },

            closeCrop() {
                this.cropOpen = false;
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
                if (cropSrc) {
                    URL.revokeObjectURL(cropSrc);
                    cropSrc = null;
                }
            },

            // Simpan lewat fetch. Dibuat meniru navigasi browser biasa (tanpa header AJAX)
            // supaya redirect()->back() / withInput() di controller tetap bekerja seperti semula.
            async submit(form) {
                if (this.saving) return;
                this.saving = true;
                this.stale = true;
                const btn = form.querySelector('button[type="submit"]');
                const btnText = btn ? btn.textContent : '';
                if (btn) {
                    btn.disabled = true;
                    btn.textContent = 'Menyimpan...';
                }

                try {
                    const res = await fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        credentials: 'same-origin',
                    });
                    if (!res.ok) throw new Error('HTTP ' + res.status);

                    const html = await res.text();
                    const doc = new DOMParser().parseFromString(html, 'text/html');

                    // 1) Validasi gagal -> controller mengembalikan halaman form berisi error + old input
                    if (doc.querySelector(FORM_SELECTOR)) {
                        this.render(html);
                        return;
                    }

                    // 2) Kembali ke index
                    if (doc.querySelector('.org-tree-shell')) {
                        const status = doc.querySelector('[role="status"]');
                        const alertEl = doc.querySelector('.space-y-5 > [role="alert"]');
                        if (!status && alertEl) {
                            this.showError(alertEl.textContent.trim());
                            return;
                        }
                        sessionStorage.setItem('orgFlash', status ? status.textContent.trim() : 'Data berhasil disimpan.');
                        this.stale = false;
                        window.location.reload();
                        return;
                    }

                    // 3) Halaman tak dikenal (mis. login)
                    throw new Error('respons tidak dikenali, sesi mungkin habis');
                } catch (err) {
                    this.showError(`Gagal menyimpan (${err.message}). Muat ulang halaman lalu coba lagi.`);
                } finally {
                    this.saving = false;
                    const current = this.$refs.body.querySelector('button[type="submit"]');
                    if (current) {
                        current.disabled = false;
                        if (btnText) current.textContent = btnText;
                    }
                }
            },

            close() {
                if (this.cropOpen) return;
                this.open = false;
                this.$refs.body.innerHTML = '';
                // setelah submit gagal, token CSRF form lain di halaman (naik/turun/hapus) sudah berganti
                if (this.stale) window.location.reload();
            },
        };
    }
</script>
<?= $this->endSection() ?>