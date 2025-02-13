<?= $this->extend('layout/user/kompleks'); ?>
<?= $this->section('content'); ?>
<?= session()->getFlashdata('pesan'); ?>

<!-- BEGIN: Content -->
<div class="content">
    <div class="intro-y mt-8">
        <h2 class="text-lg font-medium mr-auto">
            <?= $kelas->mapel . ' ' . $kelas->nama_kelas; ?>
        </h2>
        <div class="mt-2">
            <a href="<?= base_url(); ?>/su/showassignment?data=<?= encrypt_url($tugas->id_tugas) . '&code=' . encrypt_url($data_kode_kelas); ?>">
                <button class="button w-32 mt-2 bg-theme-1 text-white"> Back to Detail </button>
            </a>
        </div>
    </div>
    <div class="pos intro-y grid grid-cols-12 gap-5 mt-5">
        <!-- BEGIN: Work -->
        <div class="col-span-12 lg:col-span-12">
            <div class="box p-5 mt-5">
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <!-- BEGIN: Data List -->
                    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible" style="margin-top: -25px;">
                        <h2 class="text-lg font-medium mr-auto"><?= $tugas_siswa->nama; ?></h2>
                        <p>Score :
                            <?php if ($tugas_siswa->grade == null) : ?>
                                <span class="text-theme-9"> Not Gradded yet</span>
                            <?php else : ?>
                                <span class="text-theme-9"> <?= $tugas_siswa->grade; ?> / 100</span>
                            <?php endif; ?>
                            <br>
                            Status :
                            <?php if ($tugas_siswa->is_late == 0) : ?>
                                <span class="text-theme-9">Success</span>
                            <?php else : ?>
                                <span class="text-theme-6"> Turn in Late</span>
                            <?php endif; ?>
                        </p>
                        <br>
                        <p class="text-justify whitespace-pre">
                            <?php
                            $search = array(
                                '&gt;',
                                '&lt;',
                                'width="640"'
                            );

                            $replace = array(
                                '>',
                                '<',
                                'width="100%"'
                            );
                            ?>
                            <?= str_replace($search, $replace, $tugas_siswa->teks); ?>
                        </p>
                        <div class="intro-y grid grid-cols-12 gap-3 sm:gap-6 mt-5">
                            <?php foreach ($file_stream as $file) : ?>
                                <?php if ($file->kode_stream == $tugas_siswa->kode_user_tugas) : ?>
                                    <div class="intro-y col-span-6 sm:col-span-4 md:col-span-3 xxl:col-span-2">
                                        <div class="file box rounded-md px-5 pt-8 pb-5 px-3 sm:px-5 relative zoom-in">
                                            <?php
                                            $berkas = base_url('') . '/assets/stream_file/' . $file->nama_file;
                                            $ekstensi = pathinfo($berkas, PATHINFO_EXTENSION);
                                            if ($ekstensi == 'jpg' || $ekstensi == 'JPG' || $ekstensi == 'jpeg' || $ekstensi == 'JPEG' || $ekstensi == 'png' || $ekstensi == 'PNG') : ?>
                                                <a href="<?= base_url(); ?>/download/stream/<?= encrypt_url($file->nama_file); ?>" class="w-3/5 file__icon file__icon--image mx-auto">
                                                    <div class="file__icon--image__preview image-fit">
                                                        <img alt="img" src="<?= base_url(); ?>/assets/stream_file/<?= $file->nama_file; ?>">
                                                    </div>
                                                </a>
                                            <?php else : ?>
                                                <a href="<?= base_url(); ?>/download/stream/<?= encrypt_url($file->nama_file); ?>" class="w-3/5 file__icon file__icon--file mx-auto">
                                                    <div class="file__icon__file-name"><?= $ekstensi; ?></div>
                                                </a>
                                            <?php endif; ?>
                                            <a href="" class="block font-medium mt-4 text-center truncate"><?= $file->nama_file; ?></a>
                                            <div class="text-gray-600 text-xs text-center"><?= Ukuran('./assets/stream_file/' . $file->nama_file); ?></div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <form action="<?= base_url(); ?>/su/graded" method="POST" class="validate-form mt-5">
                            <div class="grid grid-cols-12 gap-2">
                                <input type="number" class="input w-full border col-span-4" name="value" placeholder="Grade 0 / 100" max="100" min="0" required maxlength="3">
                                <input type="hidden" name="data_kode_kelas" value="<?= encrypt_url($data_kode_kelas); ?>">
                                <input type="hidden" name="id_user_tugas" value="<?= $tugas_siswa->id_user_tugas; ?>">
                                <input type="hidden" name="id_tugas" value="<?= $tugas->id_tugas; ?>">
                                <input type="hidden" name="email" value="<?= $tugas_siswa->email; ?>">
                                <button type="submit" class="button bg-theme-1 text-white">Submit</button>
                            </div>
                        </form>
                    </div>
                    <!-- END: Data List -->
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>