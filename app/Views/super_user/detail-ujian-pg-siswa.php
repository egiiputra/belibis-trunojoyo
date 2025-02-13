<?= $this->extend('layout/user/kompleks'); ?>
<?= $this->section('content'); ?>
<?= session()->getFlashdata('pesan'); ?>
<?php

use App\Models\UserModel;

$modelsiswa = new UserModel();

use App\Models\PgsiswaModel;

$PgsiswaModel = new PgsiswaModel();

?>

<!-- BEGIN: Content -->
<div class="content">
    <div class="intro-y mt-8">
        <h2 class="text-lg font-medium mr-auto">
            <?= $kelas->mapel . ' ' . $kelas->nama_kelas; ?>
        </h2>
        <div class="mt-2">
            <button class="button w-32 mt-2 bg-theme-1 text-white" onclick="history.back();"> Back to Detail </button>
        </div>
    </div>
    <div class="pos intro-y grid grid-cols-12 gap-5 mt-5">
        <!-- BEGIN: Work -->
        <div class="col-span-12 lg:col-span-12">
            <div class="box p-5 mt-5">
                <h2 class="font-medium text-xl sm:text-1xl">
                    <?= $ujian->nama_ujian; ?>
                </h2>
                <div class="text-gray-700 dark:text-gray-600 mt-3 text-xs sm:text-sm">
                    <?= date('d-l-M-Y h:i a', $ujian->tanggal_dibuat); ?>
                    <span class="mx-1">•</span>
                    <a class="text-theme-1 dark:text-theme-10" href="">
                        <?= $ujian->nama_ujian; ?>
                    </a>
                </div>
                <div class="mt-2">
                    Soal : <?= count($detail_ujian); ?> SOAL
                </div>
                <div class="mt-2">
                    Time : <?= ($ujian->waktu_jam == 0) ? '' : "$ujian->waktu_jam JAM - "; ?> <?= ($ujian->waktu_menit == 0) ? '' : "$ujian->waktu_menit MENIT"; ?>
                </div>
                <div class="flex text-xs sm:text-sm flex-col sm:flex-row items-center mt-5 pb-5 border-b border-gray-200 dark:border-dark-5">
                    <div class="flex items-center">
                        <div class="w-12 h-12 flex-none image-fit">
                            <img alt="gambar" class="rounded-full" src="<?= base_url(); ?>/vendor/dist/user/<?= $siswa->gambar; ?>">
                        </div>
                        <div class="ml-3 mr-auto">
                            <a href="" class="font-medium"><?= $siswa->nama; ?></a>
                            <div class="text-gray-600"><?= $siswa->email; ?></div>
                        </div>
                    </div>
                </div>
                <!-- Isi Tugas -->
                <p class="text-justify whitespace-pre-line">
                <div class="accordion">
                    <div class="accordion__pane border rounded border-gray-200 dark:border-dark-5 p-4">
                        <a href="javascript:;" class="accordion__pane__toggle font-medium block"> Jawaban <?= $siswa->nama; ?> : Click To Open
                        </a>
                        <div class="accordion__pane__content mt-3 text-gray-700 dark:text-gray-600 leading-relaxed">
                            <div style="margin-top: 20px;">
                                <?php
                                $no = 1;
                                foreach ($detail_ujian as $soal) : ?>
                                    <?php $jawaban_siswa = $PgsiswaModel
                                        ->where('id_pg_detail', $soal->id_pg_detail)
                                        ->where('siswa', $siswa->id_user)
                                        ->get()->getRowObject();
                                    ?>
                                    <p>
                                        <span style="font-weight: bold;">NO. <?= $no++; ?>
                                            <br>
                                        </span>
                                        <?= $soal->soal; ?>
                                    </p>
                                    <ul style="list-style: none; margin-top: 8px;">
                                        <li><?= $soal->pg_a; ?></li>
                                        <li><?= $soal->pg_b; ?></li>
                                        <li><?= $soal->pg_c; ?></li>
                                        <li><?= $soal->pg_d; ?></li>
                                        <li><?= $soal->pg_e; ?></li>
                                    </ul>
                                    <?php if ($soal->jawaban == $jawaban_siswa->jawaban) : ?>
                                        <div class="mt-2">Jawaban <?= $siswa->nama ?> : <strong><?= $jawaban_siswa->jawaban; ?></strong> <span class="button button--sm bg-theme-9 text-white ml-2">benar</span></div>
                                    <?php else : ?>
                                        <?php if ($jawaban_siswa->jawaban == NULL) : ?>
                                            <div class="mt-2">Jawaban <?= $siswa->nama ?> :<span class="button button--sm bg-theme-12 text-white ml-2">tidak dijawab</span></div>
                                            <div class="mt-2 text-success">Jawaban Benar : <strong><?= $soal->jawaban; ?></strong></div>
                                        <?php else : ?>
                                            <div class="mt-2">Jawaban <?= $siswa->nama ?> : <strong><?= $jawaban_siswa->jawaban; ?></strong> <span class="button button--sm bg-theme-6 text-white ml-2">salah</span></div>
                                            <div class="mt-2 text-success">Jawaban Benar : <strong><?= $soal->jawaban; ?></strong></div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <br>
                                <?php endforeach; ?>
                                <div class="border-t border-gray-200 dark:border-dark-5 pt-3">
                                    <?php $jawaban_salah = $PgsiswaModel
                                        ->where('kode_ujian', $ujian->kode_ujian)
                                        ->where('siswa', $siswa->id_user)
                                        ->where('benar', 0)
                                        ->get()->getResultObject();
                                    ?>
                                    <?php $jawaban_benar = $PgsiswaModel
                                        ->where('kode_ujian', $ujian->kode_ujian)
                                        ->where('siswa', $siswa->id_user)
                                        ->where('benar', 1)
                                        ->get()->getResultObject();
                                    ?>
                                    <?php $tidak_dijawab = $PgsiswaModel
                                        ->where('kode_ujian', $ujian->kode_ujian)
                                        ->where('siswa', $siswa->id_user)
                                        ->where('benar', 2)
                                        ->get()->getResultObject();
                                    ?>
                                    <span class="button button--sm bg-theme-9 text-white ml-2" style="white-space: nowrap;">Jawaban Benar : <?= count($jawaban_benar); ?></span><br><br>
                                    <span class="button button--sm bg-theme-6 text-white ml-2" style="white-space: nowrap;">Jawaban Salah : <?= count($jawaban_salah); ?></span><br><br>
                                    <span class="button button--sm bg-theme-12 text-white ml-2" style="white-space: nowrap;">Tidak Di jawab : <?= count($tidak_dijawab); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </p>
            </div>
        </div>
        <!-- END: Work -->
    </div>
</div>

<?= $this->endSection(); ?>