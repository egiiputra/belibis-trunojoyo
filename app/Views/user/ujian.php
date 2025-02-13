<?= $this->extend('layout/user/kompleks'); ?>
<?= $this->section('content'); ?>
<?= session()->getFlashdata('pesan'); ?>
<?php

use App\Models\PgwaktusiswaModel;
use App\Models\EssaywaktusiswaModel;

$pgwaktusiswamodel = new PgwaktusiswaModel();
$essaywaktusiswamodel = new EssaywaktusiswaModel();

?>

<div class="flex">
    <!-- BEGIN: Content -->
    <div class="content">
        <h2 class="intro-y text-lg font-medium mt-10">
            <?= $kelas->mapel; ?> <?= $kelas->nama_kelas; ?>
        </h2>
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
                        <?php foreach ($list_ujian as $ujian) : ?>
                            <?php if ($ujian->jenis_ujian == 1) : ?>
                                <?php $cek_sudah_dikerjakan = $pgwaktusiswamodel
                                    ->where('kode_ujian', $ujian->kode_ujian)
                                    ->where('siswa', $user->email)
                                    ->get()->getRowObject();
                                ?>
                            <?php else : ?>
                                <?php $cek_sudah_dikerjakan = $essaywaktusiswamodel
                                    ->where('kode_ujian', $ujian->kode_ujian)
                                    ->where('siswa', $user->email)
                                    ->get()->getRowObject();
                                ?>
                            <?php endif; ?>
                            <tr class="intro-x">
                                <td>
                                    <div class="font-medium whitespace-no-wrap"><?= $ujian->nama_ujian; ?></div>
                                </td>
                                <td>
                                    <div class="whitespace-no-wrap">Posted : <?= date('d-l-M-Y h:i a', $ujian->tanggal_dibuat); ?></div>
                                </td>
                                <td class="text-left">
                                    <?php if ($ujian->waktu_jam == 0) : ?>
                                        Waktu : <?= $ujian->waktu_menit ?> Menit
                                    <?php else : ?>
                                        Waktu : <?= $ujian->waktu_jam ?> Jam - <?= $ujian->waktu_menit ?> Menit
                                    <?php endif; ?>
                                </td>
                                <td class="w-40">
                                    <?php if ($ujian->jenis_ujian == 1) : ?>
                                        <?php if ($cek_sudah_dikerjakan == null) : ?>
                                            <a href="<?= base_url(); ?>/user/showpg?data=<?= encrypt_url($ujian->kode_ujian) . '&kelas=' . encrypt_url($data_kode_kelas); ?>" class="flex items-center justify-center text-theme-6 btn-kerjakan">
                                                <i data-feather="clipboard" class="w-4 h-4 mr-2"></i> Kerjakan
                                            </a>
                                        <?php else : ?>
                                            <?php if ($cek_sudah_dikerjakan->selesai == 0) : ?>
                                                <a href="<?= base_url(); ?>/user/showpg?data=<?= encrypt_url($ujian->kode_ujian) . '&kelas=' . encrypt_url($data_kode_kelas); ?>" class="flex items-center justify-center text-theme-6"> <i data-feather="clipboard" class="w-4 h-4 mr-2"></i> Lanjut Kerjakan </a>
                                            <?php else : ?>
                                                <a href="<?= base_url(); ?>/user/showpg?data=<?= encrypt_url($ujian->kode_ujian) . '&kelas=' . encrypt_url($data_kode_kelas); ?>" class="flex items-center justify-center text-theme-9">
                                                    <i data-feather="eye" class="w-4 h-4 mr-2"></i> Lihat
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <?php if ($cek_sudah_dikerjakan == null) : ?>
                                            <a href="<?= base_url(); ?>/user/showessay?data=<?= encrypt_url($ujian->kode_ujian) . '&kelas=' . encrypt_url($data_kode_kelas); ?>" class="flex items-center justify-center text-theme-6 btn-kerjakan"> <i data-feather="clipboard" class="w-4 h-4 mr-2"></i> Kerjakan </a>
                                        <?php else : ?>
                                            <?php if ($cek_sudah_dikerjakan->selesai == 0) : ?>
                                                <a href="<?= base_url(); ?>/user/showessay?data=<?= encrypt_url($ujian->kode_ujian) . '&kelas=' . encrypt_url($data_kode_kelas); ?>" class="flex items-center justify-center text-theme-6"> <i data-feather="clipboard" class="w-4 h-4 mr-2"></i>Lanjut Kerjakan </a>
                                            <?php else : ?>
                                                <a href="<?= base_url(); ?>/user/showessay?data=<?= encrypt_url($ujian->kode_ujian) . '&kelas=' . encrypt_url($data_kode_kelas); ?>" class="flex items-center justify-center text-theme-9"> <i data-feather="eye" class="w-4 h-4 mr-2"></i> Lihat </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
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
<!-- END: Content -->

<script>
    $(document).ready(function() {
        $('.btn-kerjakan').click(function(e) {
            const href = $(this).attr('href');
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "kerjakan ujian sekarang?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Kerjakan Sekarang'
            }).then((result) => {
                if (result.value) {
                    document.location.href = href
                }
            });
        });
    });
</script>

<?= $this->endSection(); ?>