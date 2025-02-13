<?= $this->extend('layout/user/kompleks'); ?>
<?= $this->section('content'); ?>
<?= session()->getFlashdata('pesan'); ?>
<?php

use App\Models\UserModel;

$modelsiswa = new UserModel();

use App\Models\EssaydetailModel;

$EssaydetailModel = new EssaydetailModel();

?>

<!-- BEGIN: Content -->
<div class="content">
    <div class="intro-y mt-8">
        <h2 class="text-lg font-medium mr-auto">
            <?= $kelas->mapel . ' ' . $kelas->nama_kelas; ?>
        </h2>
        <div class="mt-2">
            <a href="<?= base_url(); ?>/user/ujian/<?= encrypt_url($data_kode_kelas) ?>">
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
                            <?php if ($ujian->waktu_jam == 0) : ?>
                                Waktu : <?= $ujian->waktu_menit ?> Menit
                            <?php else : ?>
                                Waktu : <?= $ujian->waktu_jam ?>Jam - <?= $ujian->waktu_menit ?> Menit
                            <?php endif; ?>
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
                        <div id="smartwizard">
                            <ul class="nav" style="display: none;">
                                <?php
                                $i = 1;
                                $i2 = 1;
                                foreach ($detail_ujian as $du) : ?>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#step-<?= $i++; ?>">
                                            <?= $i2++;  ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>

                            <div class="tab-content">
                                <?php
                                $n = 1;
                                $n2 = 1;
                                $n3 = 1;
                                foreach ($detail_ujian as $du) : ?>
                                    <div id="step-<?= $n++; ?>" class="tab-pane" role="tabpanel" aria-labelledby="step-<?= $n3++; ?>">
                                        <h3>Soal No. <?= $n2++; ?></h3>
                                        <div class="mt-2">
                                            <?= $du->nama_soal; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
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
                                        <?php foreach ($belum_mengerjakan as $bm) : ?>
                                            <?php if ($bm->email != $user->email) : ?>
                                                <?php if ($bm->kelas_kode == $data_kode_kelas) : ?>
                                                    <?php $siswa = $modelsiswa->getuser($bm->email); ?>
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
                                                        <?php
                                                        $db = \Config\Database::connect();
                                                        $query = $db->query("SELECT SUM(nilai) as nilai FROM essay_siswa WHERE kode_ujian = '$ujian->kode_ujian' AND siswa = '$siswa->id_user'");
                                                        $total_score = $query->getRowObject();
                                                        // $this->db->select_sum('nilai');
                                                        // $this->db->where('kode_ujian', $ujian->kode_ujian);
                                                        // $this->db->where('siswa', $siswa->id_user);
                                                        // $total_score = $this->db->get('essay_siswa')->row();

                                                        ?>
                                                        <span class="button button--sm bg-theme-9 text-white">Score : <?= $total_score->nilai; ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="w-40">
                                                    <?php if ($ws->selesai == 1) : ?>
                                                        <a href="<?= base_url(); ?>/su/essay_siswa?data=<?= encrypt_url($ujian->kode_ujian) . '&kelas=' . encrypt_url($data_kode_kelas) . '&siswa=' . encrypt_url($ws->siswa); ?>" class="flex items-center justify-center text-theme-9">
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
<script>
    $('#smartwizard').smartWizard();
</script>

<?= $this->endSection(); ?>