<?= $this->extend('layout/user/kompleks'); ?>
<?= $this->section('content'); ?>
<?= session()->getFlashdata('pesan'); ?>
<?php

use App\Models\UserModel;

$modelsiswa = new UserModel();

use App\Models\EssaydetailModel;

$EssaydetailModel = new EssaydetailModel();

use App\Models\EssaysiswaModel;

$EssaysiswaModel = new EssaysiswaModel();

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
                <form action="<?= base_url(); ?>/su/nilai_essay" method="post">
                    <input type="hidden" name="kode_ujian" value="<?= $ujian->kode_ujian ?>">
                    <input type="hidden" name="kode_kelas" value="<?= $ujian->kode_kelas ?>">
                    <input type="hidden" name="id_user" value="<?= $siswa->id_user ?>">
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
                                <?php $jawaban_siswa = $EssaysiswaModel
                                    ->where('id_essay_detail', $du->id_essay_detail)
                                    ->where('siswa', $siswa->id_user)
                                    ->get()->getRowObject();
                                ?>
                                <?php //$jawaban_siswa = $this->db->get_where('essay_siswa', ['id_essay_detail' => $du->id_essay_detail, 'siswa' => $siswa->id_user])->row(); 
                                ?>
                                <div id="step-<?= $n++; ?>" class="tab-pane" role="tabpanel" aria-labelledby="step-<?= $n3++; ?>">
                                    <h3>Soal No. <?= $n2++; ?></h3>
                                    <div class="mt-2">
                                        <?= $du->nama_soal; ?>
                                    </div>
                                    <div class="mt-5 border rounded p-3">
                                        <strong style="font-size: 18px;">Jawaban <?= $siswa->nama  ?></strong>
                                        <p class="mt-2" style="white-space: pre-line;"><?= ($jawaban_siswa->jawaban != '') ? $jawaban_siswa->jawaban : 'TIDAK DIJAWAB'; ?></p>
                                    </div>
                                    <div class="mt-5 border rounded p-3">
                                        <strong style="font-size: 15px;">Score</strong>
                                        <input type="number" name="<?= $jawaban_siswa->id_essay_siswa; ?>" class="input w-full rounded border" required>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="border-t border-gray-200 dark:border-dark-5 pt-4">
                        <button type="submit" class="button bg-theme-1 text-white ml-2">Kirim Nilai</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- END: Work -->
    </div>

</div>
<script>
    $('#smartwizard').smartWizard();
</script>

<?= $this->endSection(); ?>