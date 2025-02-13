<?= $this->extend('layout/user/kompleks'); ?>
<?= $this->section('content'); ?>
<?= session()->getFlashdata('pesan'); ?>

<div class="content">
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Update Material
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="col-span-12 lg:col-span-1"></div>
        <div class="intro-y col-span-12 lg:col-span-10">
            <!-- BEGIN: Form Validation -->
            <div class="intro-y box">
                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200 dark:border-dark-5">
                    <h2 class="font-medium text-base mr-auto">
                        Form Material
                    </h2>
                </div>
                <div class="p-5" id="basic-datepicker">
                    <div class="preview">
                        <form action="<?= base_url(); ?>/su/updatematerial_/<?= encrypt_url($kelas->kode_kelas); ?>" method="POST" enctype="multipart/form-data" class="validate-form">
                            <div>
                                <label class="flex flex-col sm:flex-row"> Title <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-gray-600">Required</span> </label>
                                <input type="text" name="title" class="input w-full border mt-2" value="<?= $materi->title; ?>" required>
                            </div>
                            <div class="mt-3">
                                <label class="flex flex-col sm:flex-row"> Description</label>
                                <textarea class="input w-full border mt-2" name="description" wrap="hard" placeholder="Description ( Optional )"><?= $materi->description; ?></textarea>
                            </div>
                            <div class="mt-3">
                                <input type="hidden" name="materi_code" value="<?= $materi->materi_kode; ?>">
                                <input type="hidden" id="kelas_id" name="kelas_id" value="<?= $kelas->id_kelas; ?>">
                                <input type="file" name="materi_file[]" style="width: 265px;" class="input border mt-2 flex" multiple>
                            </div>
                            <div class="mt-3">
                                <label>Current File</label>
                                <div class="border-2 border-dashed dark:border-dark-5 rounded-md mt-3 p-4">
                                    <div class="intro-y grid grid-cols-12 gap-3 sm:gap-6">
                                        <?php foreach ($file_stream as $file) : ?>
                                            <?php if ($file->kode_stream == $materi->materi_kode) : ?>
                                                <div class="intro-y col-span-6 sm:col-span-4 md:col-span-3 xxl:col-span-2" id="current_file<?= $file->id; ?>">
                                                    <div class="file box rounded-md px-3 sm:px-5 pt-3 relative zoom-in">
                                                        <?php
                                                        $berkas = base_url() . 'assets/stream_file/' . $file->nama_file;
                                                        $ekstensi = pathinfo($berkas, PATHINFO_EXTENSION);
                                                        if ($ekstensi == 'jpg' || $ekstensi == 'JPG' || $ekstensi == 'jpeg' || $ekstensi == 'JPEG' || $ekstensi == 'png' || $ekstensi == 'PNG') : ?>
                                                            <div class="w-3/5 file__icon file__icon--image mx-auto">
                                                                <div class="file__icon--image__preview image-fit">
                                                                    <img alt="img" src="<?= base_url(); ?>/assets/stream_file/<?= $file->nama_file; ?>">
                                                                </div>
                                                            </div>
                                                        <?php else : ?>
                                                            <div class="w-3/5 file__icon file__icon--file mx-auto">
                                                                <div class="file__icon__file-name"><?= $ekstensi; ?></div>
                                                            </div>
                                                        <?php endif; ?>
                                                        <a href="" class="block font-medium text-center truncate"><?= $file->nama_file; ?></a>
                                                        <div class="text-gray-600 text-xs text-center"><?= Ukuran('./assets/stream_file/' . $file->nama_file); ?></div>
                                                    </div>
                                                    <div title="Remove this File?" style="cursor: pointer;" class="tooltip w-5 h-5 flex items-center justify-center absolute rounded-full text-white bg-theme-6 right-0 top-0 -mr-2 -mt-2 remove-file" data-id_file="<?= $file->id; ?>"> <i data-feather="x" class="w-4 h-4"></i> </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <a href="<?= base_url(); ?>/user/materials/<?= encrypt_url($kelas->kode_kelas); ?>" class="button bg-theme-14 text-theme-10 mt-5">
                                cancel
                            </a>
                            <button type="submit" class="button bg-theme-1 text-white mt-5 ml-5">Post</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- END: Form Validation -->
        </div>
    </div>
</div>
<!-- END: Content -->
<script>
    $('.remove-file').click(function() {
        const id_file = $(this).data('id_file');
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
                $.ajax({
                    type: 'post',
                    data: {
                        id_file: id_file
                    },
                    url: "<?= base_url() ?>/su/rfmaterial",
                    success: function(response) {
                        var current_file = "current_file" + id_file;
                        $('#' + current_file).remove();
                        Swal.fire(
                            'Berhasil',
                            'file telah dihapus',
                            'success'
                        );
                    }
                });
            }
        });
    });
</script>

<?= $this->endSection(); ?>