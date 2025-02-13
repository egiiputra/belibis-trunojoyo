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
            <a href="<?= base_url(); ?>/user/ujian/<?= encrypt_url($data_kode_kelas); ?>">
                <button class="button w-32 mt-2 bg-theme-1 text-white"> Back to List </button>
            </a>
        </div>
    </div>
    <div class="pos intro-y grid grid-cols-12 gap-5 mt-5">
        <!-- BEGIN: Work -->
        <div class="col-span-12 lg:col-span-12">
            <div class="intro-y pr-1">
                <div class="box p-2">
                    <div class="pos__tabs nav-tabs justify-center flex"> <a data-toggle="tab" data-target="#ticket" href="javascript:;" class="flex-1 py-2 rounded-md text-center active">Detail</a> <a data-toggle="tab" data-target="#details" href="javascript:;" class="flex-1 py-2 rounded-md text-center">Student Work</a> </div>
                </div>
            </div>
            <div class="tab-content">
                <div class="tab-content__pane active" id="ticket">
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
                                    <img alt="gambar" class="rounded-full" src="<?= base_url('vendor/dist'); ?>/user/<?= $user->gambar; ?>">
                                </div>
                                <div class="ml-3 mr-auto">
                                    <a href="" class="font-medium"><?= $user->nama; ?></a>
                                    <div class="text-gray-600"><?= $user->email; ?></div>
                                </div>
                            </div>
                        </div>
                        <!-- Isi Tugas -->
                        <p class="text-justify whitespace-pre-line">
                        <div class="accordion">
                            <div class="accordion__pane border rounded border-gray-200 dark:border-dark-5 p-4"> <a href="javascript:;" class="accordion__pane__toggle font-medium block">Soal Ujian : Click To Open</a>
                                <div class="accordion__pane__content mt-3 text-gray-700 dark:text-gray-600 leading-relaxed">
                                    <?php
                                    $no = 1;
                                    foreach ($detail_ujian as $du) : ?>
                                        <br>
                                        <span style="font-size: 14px; font-weight: bold; margin-top: 10px;">NO. <?= $no++; ?></span><br>
                                        <?= $du->soal; ?>
                                        <ul class="mt-2">
                                            <li><?= $du->pg_a ?></li>
                                            <li><?= $du->pg_b ?></li>
                                            <li><?= $du->pg_c ?></li>
                                            <li><?= $du->pg_d ?></li>
                                            <li><?= $du->pg_e ?></li>
                                        </ul>
                                        <span class="mt-2" style="font-weight: bold;">JAWABAN : <?= $du->jawaban; ?></span>
                                        <br>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        </p>
                    </div>
                </div>
                <div class="tab-content__pane" id="details">
                    <div class="box p-5 mt-5">
                        <div class="grid grid-cols-12 gap-6 mt-5">
                            <!-- BEGIN: Data List -->
                            <div class="intro-y col-span-12 overflow-auto lg:overflow-visible" style="margin-top: -25px;">
                                <table class="table table-report -mt-2 whitespace-no-wrap">
                                    <thead>
                                        <tr>
                                            <th class="whitespace-no-wrap"></th>
                                            <th class="whitespace-no-wrap"></th>
                                            <th class="text-center whitespace-no-wrap"></th>
                                            <th class="text-center whitespace-no-wrap"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($belum_mengerjakan != null) : ?>
                                            <?php foreach ($belum_mengerjakan as $bm) : ?>
                                                <?php if ($bm->email != $user->email) : ?>
                                                    <?php if ($bm->kelas_kode == $data_kode_kelas) : ?>
                                                        <?php $siswa = $modelsiswa->getuser($bm->email) ?>
                                                        <tr class="intro-x">
                                                            <td>
                                                                <div class="flex item-center">
                                                                    <div class="w-12 h-12 flex-none image-fit">
                                                                        <img alt="gambar" class="rounded-full ml-2" src="<?= base_url(); ?>/vendor/dist/user/<?= $siswa->gambar; ?>">
                                                                    </div>
                                                                    <div class="ml-3 mr-auto">
                                                                        <a href="" class="font-medium ml-3"><?= $siswa->nama; ?></a>
                                                                        <div class="text-gray-600 ml-3"><?= $siswa->email; ?></div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="whitespace-no-wrap">
                                                                    <strong>Status Pengerjaan : </strong> <span class="button button--sm bg-theme-6 text-white">belum dikerjakan</span>
                                                                </div>
                                                            </td>
                                                            <td>

                                                            </td>
                                                            <td class="w-40">
                                                                <a href="javascript:void(0);" class="flex items-center justify-center text-theme-9">
                                                                    <i data-feather="eye" class="w-4 h-4 mr-2"></i>
                                                                    Show
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>

                                        <?php if ($waktu_siswa != null) : ?>
                                            <?php foreach ($waktu_siswa as $ws) : ?>
                                                <?php $siswa = $modelsiswa->getuser($ws->siswa); ?>
                                                <tr class="intro-x">
                                                    <td>
                                                        <div class="flex item-center">
                                                            <div class="w-12 h-12 flex-none image-fit">
                                                                <img alt="gambar" class="rounded-full ml-2" src="<?= base_url(); ?>/vendor/dist/user/<?= $siswa->gambar; ?>">
                                                            </div>
                                                            <div class="ml-3 mr-auto">
                                                                <a href="" class="font-medium ml-3"><?= $siswa->nama; ?></a>
                                                                <div class="text-gray-600 ml-3"><?= $siswa->email; ?></div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="whitespace-no-wrap">
                                                            <?php if ($ws->selesai == 0) : ?>
                                                                <strong>Status Pengerjaan : </strong> <span class="button button--sm bg-theme-12 text-white">Sedang Dikerjakan</span>
                                                            <?php else : ?>
                                                                <strong>Status Pengerjaan : </strong> <span class="button button--sm bg-theme-1 text-white">Selesai</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <?php if ($ws->selesai == 1) : ?>
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
                                                            <span class="button button--sm bg-theme-9 text-white">Benar : <?= count($jawaban_benar) ?></span>
                                                            <span class="button button--sm bg-theme-6 text-white ml-2">Salah : <?= count($jawaban_salah) ?></span>
                                                            <span class="button button--sm bg-theme-12 text-white ml-2">Tidak Dijawab : <?= count($tidak_dijawab) ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="w-40">
                                                        <?php if ($ws->selesai == 1) : ?>
                                                            <a href="<?= base_url(); ?>/su/pg_siswa?data=<?= encrypt_url($ujian->kode_ujian) . '&kelas=' . encrypt_url($data_kode_kelas) . '&siswa=' . encrypt_url($ws->siswa); ?>" class="flex items-center justify-center text-theme-9">
                                                                <i data-feather="eye" class="w-4 h-4 mr-2"></i>
                                                                Show
                                                            </a>
                                                        <?php else : ?>
                                                            <a href="javascript:void(0);" class="flex items-center justify-center text-theme-9">
                                                                <i data-feather="eye" class="w-4 h-4 mr-2"></i>
                                                                Show
                                                            </a>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- END: Data List -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Work -->
    </div>
</div>

<?= $this->endSection(); ?>