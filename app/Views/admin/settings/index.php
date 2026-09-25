<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col flex-row sm:items-center justify-between gap-4 bg-slate-900/40 p-6 rounded-3xl border border-slate-800 backdrop-blur-md">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-brand-mint-400 bg-brand-emerald-950/80 px-3 py-1 rounded-full border border-brand-emerald-800/80">Konfigurasi Sistem</span>
                <h1 class="text-2xs sm:text-3xs font-black font-heading text-white mt-2">Pengaturan Website</h1>
                <p class="text-xs text-slate-400 mt-1">Kelola informasi instansi, running text marquee, link integrasi, dan media hero</p>
            </div>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="p-4 bg-brand-emerald-950/90 border border-brand-emerald-700/80 rounded-2xl text-brand-mint-300 text-xs font-semibold flex items-center space-x-3 shadow-lg">
                <svg class="w-5 h-5 text-brand-mint-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span><?=  session()->getFlashdata('success') ?></span>
            </div>
        <?php endif; ?>

        <!-- Main Form -->
        <form action="<?=  base_url('admin/settings/save') ?>" method="POST" enctype="multipart/form-data" class="space-y-8">
            <?=  csrf_field() ?>

            <?php foreach ($grouped as $groupName => $items): ?>
                <div class="bg-slate-900/60 rounded-3xl border border-slate-800/80 p-6 sm:p-8 space-y-6 backdrop-blur-sm">
                    <div class="border-b border-slate-800 pb-4 flex items-center justify-between">
                        <h2 class="text-lg font-bold font-heading text-white capitalize flex items-center space-x-2">
                            <span class="w-2 h-2 rounded-full bg-brand-mint-400"></span>
                            <span>Group: <?=  esc($groupName) ?></span>
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        <?php foreach ($items as $item): ?>
                            <div class="space-y-2">
                                <label for="setting_<?=  $item['key'] ?>" class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                                    <?=  esc($item['label']) ?>
                                    <span class="text-slate-500 text-[10px] font-mono font-normal ml-1">(key: <?=  esc($item['key']) ?>)</span>
                                </label>

                                <?php if ($item['key'] === 'site_tagline'): ?>
                                    <!-- Specialized Editor for Rotating Punchline Marquee -->
                                    <div class="space-y-3">
                                        <textarea id="setting_<?=  $item['key'] ?>"
                                                name="<?=  $item['key'] ?>"
                                                rows="3"
                                                class="w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-2xl text-white font-sans text-xs focus:ring-2 focus:ring-brand-emerald-500 focus:border-transparent transition leading-relaxed"><?=  esc($item['value']) ?></textarea>
                                        
                                        <div class="p-3.5 rounded-xl bg-slate-950/70 border border-slate-800 space-y-2">
                                            <div class="flex items-center space-x-2 text-brand-mint-300 font-semibold text-[11px]">
                                                <svg class="w-4 h-4 text-brand-mint-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                <span>Petunjuk Format Punchline Marquee:</span>
                                            </div>
                                            <p class="text-[11px] text-slate-400 leading-normal">Gunakan karakter <code>|</code> (garis tegak/pipe) untuk memsahkan setiap item kalimat punchline.</p>
                                        </div>
                                    </div>

                                <?php elseif ($item['key'] === 'hero_bg_images'): ?>
                                    <!-- Specialized Editor for Hero Background Images -->
                                    <div class="space-y-4">
                                        <textarea id="setting_<?=  $item['key'] ?>"
                                                name="<?=  $item['key'] ?>"
                                                rows="2"
                                                class="w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-2xl text-white font-mono text-xs focus:ring-2 focus:ring-brand-emerald-500 focus:border-transparent transition"
                                                placeholder="uploads/hero/slide1.jpg, uploads/hero/slide2.jpg"><?=  esc($item['value']) ?></textarea>
                                        
                                        <div class="p-4 rounded-2xl bg-slate-950/80 border border-slate-800 space-y-3">
                                            <div class="flex items-center space-x-2 text-brand-mint-300 font-semibold text-xs">
                                                <svg class="w-4 h-4 text-brand-emerald-400 shrink-0" fill="none" stroke="currrentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                <span>Upload Foto Hero Baru (Multiple Upload):</span>
                                            </div>
                                            <input type="file" name="hero_bg_files[]" multiple accept="image/*" class="block w-full text-xs text-slate-300 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand-emerald-700 file:text-white hover:file:bg-brand-emerald-600 cursor-pointer transition" />
                                            <p class="text-[11px] text-slate-400">File foto akan tersimpan di folder <code>public/uploads/hero/</code> secara otomatis saat tombol Simpan ditekan.</p>
                                        </div>

                                        <?php 
                                        $heroImgs = array_filter(array_map('trim', explode(',', $item['value'])));
                                        if (!empty($heroImgs)): 
                                        ?>
                                        <div class="space-y-2 pt-2">
                                            <label class="block text-xs font-semibold text-slate-300">Foto Background Terpasang (<?=  count($heroImgs) ?> foto):</label>
                                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                                <?php foreach ($heroImgs as $img): 
                                                    $imgSrc = (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) ? $img : base_url($img);
                                                ?>
                                                <div class="relative group rounded-xl overflow-hidden border border-slate-800 bg-slate-950 aspect-video flex items-center justify-center shadow-inner">
                                                     <img src="<?=  $imgSrc ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" alt="Hero BG">
                                                     <div class="absolute inset-0 bg-slate-950/80 opacity-0 group-hover:opacity-100 transition flex items-center justify-center p-2">
                                                         <button type="submit" name="delete_hero_photo" value="<?=  esc($img) ?>" onClick="return confirm('Hapus foto ini dari background hero?')" class="px-3 py-1.5 bg-red-600 hover:bg-red-500 text-white rounded-lg text-xs font-bold shadow transition flex items-center space-x-1">
                                                             <span>Hapus</span>
                                                         </button>
                                                     </div>
                                                 </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    </div>

                                <?php elseif ($item['tipe'] === 'textarea'): ?>
                                    <textarea id="setting_<?=  $item['key'] ?>"
                                                name="<?=  $item['key'] ?>"
                                                rows="3"
                                                class="w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-2xl text-white font-sans text-xs focus:ring-2 focus:ring-brand-emerald-500 focus:border-transparent transition leading-relaxed"><?=  esc($item['value']) ?></textarea>
                                
                                <?php else: ?>
                                    <input type="<?=  esc($item['tipe'] ?? 'text') ?>"
                                            id="setting_<?=  $item['key'] ?>"
                                            name="<?=  $item['key'] ?>"
                                            value="<?=  esc($item['value']) ?>"
                                            class="w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-2xl text-white font-sans text-xs focus:ring-2 focus:ring-brand-emerald-500 focus:border-transparent transition" />
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Submit Action -->
            <div class="sticky bottom-6 z-20 flex justify-end pt-4">
                <button type="submit" class="inline-flex items-center space-x-2 bg-gradient-to-r from-brand-emerald-600 to-brand-teal-600 hover:from-brand-emerald-500 hover:to-brand-teal-500 text-white font-bold text-xs px-8 py-3.5 rounded-2xl shadow-2xl hover:scale-105 active:scale-95 transition duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currrentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Simpan Pengaturan</span>
                </button>
            </div>
        </form>
    </div>

<?= $this->endSection() ?>