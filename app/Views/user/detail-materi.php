<?= $this->extend('layout/user/kompleks'); ?>
<?= $this->section('content'); ?>
<?= session()->getFlashdata('pesan'); ?>

<div class="content">
    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <a href="<?= base_url(); ?>/user/materials/<?= encrypt_url($data_kode_kelas); ?>">
            <button class="button w-32 mr-2 mt-2 flex items-center justify-center bg-theme-1 text-white"> Back to List </button>
        </a>
    </div>
    <div class="pos intro-y grid grid-cols-12 gap-5">
        <div class="col-span-12 lg:col-span-12">
            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 lg:col-span-12">
                    <div class="box mt-5">
                        <div class="intro-y news p-5 box mt-8">
                            <!-- BEGIN: Blog Layout -->
                            <h2 class="intro-y font-medium text-xl sm:text-2xl">
                                <?= $materi->title; ?>
                            </h2>
                            <div class="intro-y text-gray-700 dark:text-gray-600 mt-3 text-xs sm:text-sm">
                                <?= date('M-l-d', $materi->date_created); ?>
                                <span class="mx-1">•</span>
                                <a class="text-theme-1 dark:text-theme-10" href="">
                                    <?= $materi->title; ?>
                                </a>
                                <?php if ($materi->date_updated != NULL) {
                                    echo "Updated on " . date('l-M-d', $materi->date_updated);
                                } ?>
                            </div>
                            <div class="intro-y flex text-xs sm:text-sm flex-col sm:flex-row items-center mt-5 pb-5 border-b border-gray-200 dark:border-dark-5">
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
                            <div class="intro-y text-justify leading-relaxed mt-5">
                                <p style="white-space: pre-line;"><?= $materi->description; ?></p>
                                <div class="intro-y grid grid-cols-12 gap-3 sm:gap-6 mt-5">
                                    <?php foreach ($file_stream as $file) : ?>
                                        <?php if ($file->kode_stream == $materi->materi_kode) : ?>
                                            <div class="intro-y col-span-6 sm:col-span-4 md:col-span-3 xxl:col-span-2">
                                                <div class="file box rounded-md px-5 pt-8 pb-5 px-3 sm:px-5 relative zoom-in">
                                                    <?php
                                                    $berkas = base_url() . '/assets/stream_file/' . $file->nama_file;
                                                    $ekstensi = pathinfo($berkas, PATHINFO_EXTENSION);
                                                    if ($ekstensi == 'jpg' || $ekstensi == 'JPG' || $ekstensi == 'jpeg' || $ekstensi == 'JPEG' || $ekstensi == 'png' || $ekstensi == 'PNG') : ?>
                                                        <a href="<?= base_url(); ?>/unduh/stream/<?= encrypt_url($file->nama_file); ?>" class="w-3/5 file__icon file__icon--image mx-auto">
                                                            <div class="file__icon--image__preview image-fit">
                                                                <img alt="img" src="<?= base_url(); ?>/assets/stream_file/<?= $file->nama_file; ?>">
                                                            </div>
                                                        </a>
                                                    <?php else : ?>
                                                        <a href="<?= base_url(); ?>/unduh/stream/<?= encrypt_url($file->nama_file); ?>" class="w-3/5 file__icon file__icon--file mx-auto">
                                                            <div class="file__icon__file-name"><?= $ekstensi; ?></div>
                                                        </a>
                                                    <?php endif; ?>
                                                    <a href="" class="block font-medium mt-4 text-center truncate"><?= $file->nama_file; ?></a>
                                                    <div class="text-gray-600 text-xs text-center"><?= Ukuran('./assets/stream_file/' . $file->nama_file); ?></div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <!-- END: Blog Layout -->
                            <!-- BEGIN: Comments -->
                            <div class="accordion">
                                <div class="accordion__pane pb-4">
                                    <a href="javascript:;" class="accordion__pane__toggle font-medium block">
                                        <div class="intro-y mt-5 pt-5 border-t border-gray-200 dark:border-dark-5">
                                            <div class="text-base sm:text-lg font-medium">Show Responses</div>
                                        </div>
                                    </a>
                                    <div class="accordion__pane__content mt-3 text-gray-700 dark:text-gray-600 leading-relaxed" style="display: none;">
                                        <div class="intro-y mt-5 pb-10">
                                            <div id="accordion_komen_streammateri">

                                            </div>
                                            <div class="news__input relative mt-5">
                                                <i data-feather="message-circle" class="w-5 h-5 absolute my-auto inset-y-0 ml-6 left-0 text-gray-600"></i>
                                                <input type="hidden" id="materi_code" value="<?= $materi->materi_kode; ?>">
                                                <textarea wrap="hard" id="isi_komen" class="input w-full bg-gray-200 pl-16 py-6 placeholder-theme-13 resize-none" rows="1" placeholder="Post a comment..."></textarea>
                                                <button id="kirim_komen_materi" class="button w-32 mr-2 mt-2 flex items-center justify-center bg-theme-14 text-theme-10"> <i data-feather="send" class="w-4 h-4 mr-2"></i> Send </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END: Comments -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: Content -->
<script>
    $('#kirim_komen_materi').click(function() {
        var kode_materi = $('#materi_code').val();
        var isi_komen = $('#isi_komen').val();
        $.ajax({
            type: 'POST',
            data: {
                kode_materi: kode_materi,
                isi_komen: isi_komen
            },
            url: '<?= base_url() ?>/stream/materi',
            dataType: 'JSON',
            success: function(response) {
                console.log(response.responseText);
            }
        });
        $('#isi_komen').val('');
    });

    var kode_materi = $('#materi_code').val();
    setInterval(() => {
        $.ajax({
            type: 'POST',
            data: {
                kode_materi: kode_materi
            },
            url: '<?= base_url() ?>/stream/getmateri',
            success: function(data) {
                document.getElementById("accordion_komen_streammateri").innerHTML = data;
            }
        });
    }, 2000);
</script>

<?= $this->endSection(); ?>