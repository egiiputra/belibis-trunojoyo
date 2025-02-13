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
                <a href="<?= base_url(); ?>/su/addmaterials/<?= encrypt_url($kelas->kode_kelas); ?>">
                    <button class="button text-white bg-theme-1 shadow-md mr-2">Add New Materials</button>
                </a>
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
                        <?php foreach ($list_materi as $materi) : ?>
                            <tr class="intro-x">
                                <td class="w-40">
                                    <div class="flex">
                                        <div class="w-10 h-10 image-fit">
                                            <i data-feather="file-text" class="ml-2 mt-2"></i>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="font-medium whitespace-no-wrap"><?= $materi->title; ?></div>
                                </td>
                                <td class="text-center">Posted <?= date('d-l-M-Y h:i a', $materi->date_created); ?></td>
                                <td class="w-40">
                                    <a href="<?= base_url(); ?>/su/showmaterial/<?= '?data=' . encrypt_url($materi->id_materi) . '&code=' . encrypt_url($data_kode_kelas); ?>" class="flex items-center justify-center text-theme-9"> <i data-feather="eye" class="w-4 h-4 mr-2"></i> Show </a>
                                </td>
                                <td class="table-report__action w-56">
                                    <div class="flex justify-center items-center">
                                        <a class="flex items-center mr-3" href="<?= base_url(); ?>/su/updatematerial/<?= '?data=' . encrypt_url($materi->id_materi) . '&code=' . encrypt_url($data_kode_kelas); ?>"> <i data-feather="check-square" class="w-4 h-4 mr-1"></i> Edit </a>
                                        <a class="flex items-center text-theme-6 btn-hapus" id="btn-hapus" href="<?= base_url() . '/su/deletematerial?data=' . encrypt_url($materi->id_materi) . '&code=' . encrypt_url($data_kode_kelas); ?>"> <i data-feather="trash-2" class="w-4 h-4 mr-1"></i> Delete </a>
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