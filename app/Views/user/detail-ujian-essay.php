<?= $this->extend('layout/user/kompleks'); ?>
<?= $this->section('content'); ?>
<?= session()->getFlashdata('pesan'); ?>

<?php

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
            <a href="<?= base_url(); ?>/user/ujian/<?= encrypt_url($data_kode_kelas); ?>">
                <button class="button w-32 mt-2 bg-theme-1 text-white"> Back to List </button>
            </a>
        </div>
    </div>
    <div class="pos intro-y grid grid-cols-12 gap-5 mt-5">
        <!-- BEGIN: Work -->
        <div class="col-span-12 lg:col-span-12">
            <div class="box p-5">
                <div class="flex flex-col sm:flex-row items-center pb-5 border-b border-gray-200 dark:border-dark-5">
                    <h2 class="font-medium text-xl sm:text-1xl mr-auto">
                        <?= $ujian->nama_ujian; ?>
                    </h2>
                    <div class="w-full sm:w-auto flex items-center sm:ml-auto mt-3 sm:mt-0">
                        <button type="button" class="button bg-theme-1 text-white font-medium text-xl sm:text-1xl" id="waktu_live">00 : 00 : 00</button>
                    </div>
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
                            <img alt="gambar" class="rounded-full" src="<?= base_url(); ?>/vendor/dist/user/<?= $user->gambar; ?>">
                        </div>
                        <div class="ml-3 mr-auto">
                            <a href="" class="font-medium"><?= $user->nama; ?></a>
                            <div class="text-gray-600"><?= $user->email; ?></div>
                        </div>
                    </div>
                </div>
                <!-- Isi Tugas -->
                <?php if ($waktu_siswa->selesai == 1) : ?>
                    <div class="rounded-md flex items-center px-5 py-4 mb-2 bg-theme-18 text-theme-9">
                        <i data-feather="smile" class="w-6 h-6 mr-2"></i> Yahooo, Kamu sudah mengerjakan Ujian
                    </div>
                <?php endif; ?>
                <div class="mt-3">
                    <?php if ($waktu_siswa->selesai == 0) : ?>
                        <form action="<?= base_url(); ?>/user/submit_essay" method="POST">
                            <input type="hidden" name="ujian" value="<?= $ujian->kode_ujian; ?>">
                            <input type="hidden" name="siswa" value="<?= $user->id_user; ?>">
                            <input type="hidden" name="kelas" value="<?= $data_kode_kelas; ?>">
                            <div style="position: relative;">
                                <div id="waktu_habis" style="position: absolute; left: 0; top: 0; width: 100%; height: 100%; display: none; justify-content: center; align-items: center; background: rgba(255, 255, 255, 0.9); z-index: 1;">
                                    <p class="text-theme-6" style="font-size: 50px; text-align: center; margin-top: 100px;">Waktu Habis</p>
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
                                                <strong>
                                                    <h3>Soal No. <?= $n2++; ?></h3>
                                                </strong>
                                                <div class="mt-2">
                                                    <?= $du->nama_soal; ?>
                                                </div>
                                                <div class="mt-3">
                                                    <label class="flex flex-col sm:flex-row">
                                                        <strong>Jawaban Kamu</strong>
                                                        <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-gray-600">Required</span>
                                                    </label>
                                                    <textarea name="<?= $du->id_essay_detail; ?>" class="input w-full border mt-2"></textarea>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="border-t border-gray-200 dark:border-dark-5">
                                <button type="submit" class="button bg-theme-1 text-white mt-3">Kirim Jawaban</button>
                            </div>
                        </form>
                    <?php else : ?>
                        <div style="margin-top: 20px;">
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
                                        <?php $jawaban_saya = $EssaysiswaModel
                                            ->where('id_essay_detail', $du->id_essay_detail)
                                            ->where('siswa', $user->id_user)
                                            ->get()->getRowObject();
                                        ?>
                                        <?php //$jawaban_saya = $this->db->get_where('essay_siswa', ['id_essay_detail' => $du->id_essay_detail, 'siswa' => $user->id_user])->row(); 
                                        ?>
                                        <div id="step-<?= $n++; ?>" class="tab-pane" role="tabpanel" aria-labelledby="step-<?= $n3++; ?>">
                                            <strong>
                                                <h3>Soal No. <?= $n2++; ?></h3>
                                            </strong>
                                            <div class="mt-2">
                                                <?= $du->nama_soal; ?>
                                            </div>
                                            <div class="mt-5 border rounded p-3">
                                                <strong style="font-size: 14px;">Jawaban Saya</strong>
                                                <p style="white-space: pre-line;">
                                                    <?php if ($jawaban_saya->jawaban != '') {
                                                        echo $jawaban_saya->jawaban;
                                                    } else {
                                                        echo "TIDAK DIJAWAB";
                                                    } ?>
                                                </p>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="border-t border-gray-200 dark:border-dark-5 pt-3">
                                <span class="button button--sm bg-theme-9 text-white ml-2">
                                    <?php
                                    $db = \Config\Database::connect();
                                    $query = $db->query("SELECT SUM(nilai) as nilai FROM essay_siswa WHERE kode_ujian = '$ujian->kode_ujian' AND siswa = '$user->id_user'");
                                    $total_score = $query->getRowObject();

                                    ?>
                                    SCORE : <?= $total_score->nilai;  ?> / 100
                                </span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- END: Work -->
    </div>
</div>

<script>
    $('#smartwizard').smartWizard();
    <?php if ($waktu_siswa->selesai == 0) : ?>
        // Set the date we're counting down to
        var countDownDate = new Date("<?= $waktu_siswa->waktu_berakhir ?>").getTime();

        // Update the count down every 1 second
        var x = setInterval(function() {

            // Get today's date and time
            var now = new Date().getTime();

            // Find the distance between now and the count down date
            var distance = countDownDate - now;

            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Display the result in the element with id="demo"
            document.getElementById("waktu_live").innerHTML = hours + " : " +
                minutes + " : " + seconds;

            // If the count down is finished, write some text
            if (distance < 0) {
                clearInterval(x);
                document.getElementById("waktu_live").innerHTML = "00 : 00 : 00";
                document.getElementById("waktu_habis").style.display = "block";
            }
        }, 1000);
    <?php endif; ?>
</script>

<?= $this->endSection(); ?>