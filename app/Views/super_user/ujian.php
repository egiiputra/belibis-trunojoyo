<?= $this->extend('layout/user/kompleks'); ?>
<?= $this->section('content'); ?>
<?= session()->getFlashdata('pesan'); ?>

<div class="flex">
    <!-- BEGIN: Content -->
    <div class="content">
        <h2 class="intro-y text-lg font-medium mt-10">
            <?= $kelas->mapel; ?> <?= $kelas->nama_kelas; ?>
        </h2>
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-no-wrap items-center mt-2">
                <div class="dropdown relative">
                    <button class="dropdown-toggle button inline-block bg-theme-1 text-white">Add New Exam</button>
                    <div class="dropdown-box mt-10 absolute w-40 top-0 left-0 z-20">
                        <div class="dropdown-box__content box dark:bg-dark-1 p-2">
                            <a href="<?= base_url(); ?>/su/addpg/<?= encrypt_url($kelas->kode_kelas) ?>" class="block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">Pilihan Ganda</a>
                            <a href="<?= base_url(); ?>/su/addessay/<?= encrypt_url($kelas->kode_kelas) ?>" class="block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">Essay</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- BEGIN: Data List -->
            <div class="intro-y col-span-12 overflow-auto lg:overflow-visible" style="margin-top: -25px;">
                <table class="table table-report -mt-2 whitespace-no-wrap">
                    <thead>
                        <tr>
                            <th class="whitespace-no-wrap"></th>
                            <th class="whitespace-no-wrap"></th>
                            <th class="text-center whitespace-no-wrap"></th>
                            <th class="text-center whitespace-no-wrap"></th>
                            <th class="text-center whitespace-no-wrap"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($list_ujian as $ujian) : ?>
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
                                        Waktu : <?= $ujian->waktu_jam ?>Jam - <?= $ujian->waktu_menit ?> Menit
                                    <?php endif; ?>
                                </td>
                                <td class="w-40">
                                    <?php if ($ujian->jenis_ujian == 1) : ?>
                                        <a href="<?= base_url(); ?>/su/showpg?<?= 'data=' . encrypt_url($ujian->kode_ujian) . '&kelas=' . encrypt_url($data_kode_kelas); ?>" class="flex items-center justify-center text-theme-9"> <i data-feather="eye" class="w-4 h-4 mr-2"></i> Show </a>
                                    <?php else : ?>
                                        <a href="<?= base_url(); ?>/su/showessay?<?= 'data=' . encrypt_url($ujian->kode_ujian) . '&kelas=' . encrypt_url($data_kode_kelas); ?>" class="flex items-center justify-center text-theme-9"> <i data-feather="eye" class="w-4 h-4 mr-2"></i> Show </a>
                                    <?php endif; ?>
                                </td>
                                <td class="table-report__action w-56">
                                    <div class="flex justify-center items-center">
                                        <?php if ($ujian->jenis_ujian == 1) : ?>
                                            <a class="flex items-center text-theme-6 btn-hapus" id="btn-hapus" href="<?= base_url(); ?>/su/deleteujianpg?<?= 'ujian=' .  encrypt_url($ujian->kode_ujian) . '&kelas=' . encrypt_url($data_kode_kelas); ?>"> <i data-feather="trash-2" class="w-4 h-4 mr-1"></i> Delete </a>
                                        <?php else : ?>
                                            <a class="flex items-center text-theme-6 btn-hapus" id="btn-hapus" href="<?= base_url(); ?>/su/deleteujianessay?<?= 'ujian=' .  encrypt_url($ujian->kode_ujian) . '&kelas=' . encrypt_url($data_kode_kelas); ?>"> <i data-feather="trash-2" class="w-4 h-4 mr-1"></i> Delete </a>
                                        <?php endif; ?>
                                    </div>
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
        $('.btn-hapus').click(function(e) {
            const href = $(this).attr('href');
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    document.location.href = href
                }
            });
        });
    });
</script>

<?= $this->endSection(); ?>