<?= $this->extend('layout/user/kompleks'); ?>
<?= $this->section('content'); ?>
<?= session()->getFlashdata('pesan'); ?>

<?php

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
                <p class="text-justify whitespace-pre-line">
                <div class="accordion">
                    <div class="accordion__pane border rounded border-gray-200 dark:border-dark-5 p-4">
                        <a href="javascript:;" class="accordion__pane__toggle font-medium block">
                            <?php if ($waktu_siswa->selesai == 0) : ?>
                                Soal Ujian : Click To Open
                            <?php else : ?>
                                Jawaban Saya : Click To Open
                            <?php endif; ?>
                        </a>
                        <br>
                        <div class="accordion__pane__content mt-3 text-gray-700 dark:text-gray-600 leading-relaxed">
                            <?php if ($waktu_siswa->selesai == 0) : ?>
                                <form action="<?= base_url(); ?>/user/submit_pg" method="POST">
                                    <input type="hidden" name="ujian" value="<?= $ujian->kode_ujian; ?>">
                                    <input type="hidden" name="siswa" value="<?= $user->id_user; ?>">
                                    <input type="hidden" name="kelas" value="<?= $data_kode_kelas; ?>">
                                    <div style="position: relative;">
                                        <div id="waktu_habis" style="position: absolute; left: 0; top: 0; width: 100%; height: 100%; display: none; justify-content: center; align-items: center; background: rgba(255, 255, 255, 0.9); z-index: 1;">
                                            <p class="text-theme-6" style="font-size: 50px; text-align: center; margin-top: 50%">Waktu Habis</p>
                                        </div>
                                        <?php
                                        $no = 1;
                                        foreach ($detail_ujian as $soal) : ?>
                                            <p>
                                                <span style="font-weight: bold;">NO. <?= $no++; ?>
                                                    <br>
                                                </span> <?= $soal->soal; ?>
                                            </p>
                                            <ul style="list-style: none; margin-top: 8px;">
                                                <li><input type="radio" name="<?= $soal->id_pg_detail; ?>" value="<?= substr($soal->pg_a, 0, 1); ?>"> <?= $soal->pg_a; ?>
                                                </li>
                                                <li><input type="radio" name="<?= $soal->id_pg_detail; ?>" value="<?= substr($soal->pg_b, 0, 1); ?>"> <?= $soal->pg_b; ?>
                                                </li>
                                                <li><input type="radio" name="<?= $soal->id_pg_detail; ?>" value="<?= substr($soal->pg_c, 0, 1); ?>"> <?= $soal->pg_c; ?>
                                                </li>
                                                <li><input type="radio" name="<?= $soal->id_pg_detail; ?>" value="<?= substr($soal->pg_d, 0, 1); ?>"> <?= $soal->pg_d; ?>
                                                </li>
                                                <li><input type="radio" name="<?= $soal->id_pg_detail; ?>" value="<?= substr($soal->pg_e, 0, 1); ?>"> <?= $soal->pg_e; ?>
                                                </li>
                                            </ul>
                                            <br>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="border-t border-gray-200 dark:border-dark-5">
                                        <button type="submit" class="button bg-theme-1 text-white mt-3">Kirim Jawaban</button>
                                    </div>
                                </form>
                            <?php else : ?>
                                <div style="margin-top: -20px;">
                                    <?php
                                    $no = 1;
                                    foreach ($detail_ujian as $soal) : ?>
                                        <?php $jawaban_siswa = $PgsiswaModel
                                            ->where('id_pg_detail', $soal->id_pg_detail)
                                            ->where('siswa', $user->id_user)
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
                                            <div class="mt-2">Jawaban Kamu : <strong><?= $jawaban_siswa->jawaban; ?></strong> <span class="button button--sm bg-theme-9 text-white ml-2">benar</span></div>
                                        <?php else : ?>
                                            <?php if ($jawaban_siswa->jawaban == NULL) : ?>
                                                <div class="mt-2">Jawaban Kamu :<span class="button button--sm bg-theme-12 text-white ml-2">tidak dijawab</span></div>
                                                <div class="mt-2 text-success">Jawaban Benar : <strong><?= $soal->jawaban; ?></strong></div>
                                            <?php else : ?>
                                                <div class="mt-2">Jawaban Kamu : <strong><?= $jawaban_siswa->jawaban; ?></strong> <span class="button button--sm bg-theme-6 text-white ml-2">salah</span></div>
                                                <div class="mt-2 text-success">Jawaban Benar : <strong><?= $soal->jawaban; ?></strong></div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <br>
                                    <?php endforeach; ?>
                                    <div class="border-t border-gray-200 dark:border-dark-5 pt-3">
                                        <?php $jawaban_salah = $PgsiswaModel
                                            ->where('kode_ujian', $ujian->kode_ujian)
                                            ->where('siswa', $user->id_user)
                                            ->where('benar', 0)
                                            ->get()->getResultObject();
                                        ?>
                                        <?php $jawaban_benar = $PgsiswaModel
                                            ->where('kode_ujian', $ujian->kode_ujian)
                                            ->where('siswa', $user->id_user)
                                            ->where('benar', 1)
                                            ->get()->getResultObject();
                                        ?>
                                        <?php $tidak_dijawab = $PgsiswaModel
                                            ->where('kode_ujian', $ujian->kode_ujian)
                                            ->where('siswa', $user->id_user)
                                            ->where('benar', 2)
                                            ->get()->getResultObject();
                                        ?>
                                        <span class="button button--sm bg-theme-9 text-white ml-2">Jawaban Benar : <?= count($jawaban_benar); ?></span>
                                        <span class="button button--sm bg-theme-6 text-white ml-2">Jawaban Salah : <?= count($jawaban_salah); ?></span>
                                        <span class="button button--sm bg-theme-12 text-white ml-2">Tidak Dijawab : <?= count($tidak_dijawab); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                </p>
            </div>
        </div>
        <!-- END: Work -->
    </div>
</div>
<script>
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