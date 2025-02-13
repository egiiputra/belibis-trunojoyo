<?= $this->extend('layout/user/kompleks'); ?>
<?= $this->section('content'); ?>
<?= session()->getFlashdata('pesan'); ?>
<?php

use App\Models\UserModel;

$this->UserModel = new UserModel();

?>

<div class="content">
    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            <?= $kelas->mapel; ?> <?= $kelas->nama_kelas; ?>
        </h2>
    </div>
    <div class="pos intro-y grid grid-cols-12 gap-5 mt-5">
        <div class="col-span-12 lg:col-span-12">
            <?php
            $accordion = 1;
            $id_kelas = 1;
            $stream_code = 1;
            $isi_komen = 1;
            $kirim_komen = 1;
            foreach ($data_stream as $stream) : ?>
                <?php $user_stream = $this->UserModel->getuser($stream->email); ?>
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="col-span-12 lg:col-span-12">
                        <div class="box mt-5">
                            <div class="intro-y news p-5 box mt-8">
                                <!-- BEGIN: Blog Layout -->
                                <div class="intro-y text-gray-700 dark:text-gray-600 mt-3 text-xs sm:text-sm"> <?= time_ago(date("Y-m-d H:i:s", $stream->date_created)); ?> <span class="mx-1">•</span> <a class="text-theme-1 dark:text-theme-10" href=""><?= $kelas->mapel; ?> <?= $kelas->nama_kelas; ?></a></div>
                                <div class="intro-y flex text-xs sm:text-sm flex-col sm:flex-row items-center mt-5 pb-5 border-b border-gray-200 dark:border-dark-5">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 flex-none image-fit">
                                            <img alt="gambar" class="rounded-full" src="<?= base_url('vendor/dist'); ?>/user/<?= $user_stream->gambar; ?>">
                                        </div>
                                        <div class="ml-3 mr-auto">
                                            <a href="" class="font-medium"><?= $user_stream->nama; ?></a>, Author
                                            <div class="text-gray-600"><?= $stream->email; ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="intro-y text-justify leading-relaxed mt-5">
                                    <p style="margin-top: 20px;">
                                        <?= $stream->text_stream; ?>
                                    </p>
                                    <div style="clear: both;">

                                    </div>
                                    <div class="intro-y grid grid-cols-12 gap-3 sm:gap-6 mt-5">
                                        <?php foreach ($file_stream as $file) : ?>
                                            <?php if ($file->kode_stream == $stream->stream_kode) : ?>
                                                <div class="intro-y col-span-6 sm:col-span-4 md:col-span-3 xxl:col-span-2">
                                                    <div class="file box rounded-md px-5 pt-8 pb-5 px-3 sm:px-5 relative zoom-in">
                                                        <?php
                                                        $berkas = base_url('assets/stream_file/') . $file->nama_file;
                                                        $ekstensi = pathinfo($berkas, PATHINFO_EXTENSION);
                                                        if ($ekstensi == 'jpg' || $ekstensi == 'JPG' || $ekstensi == 'jpeg' || $ekstensi == 'JPEG' || $ekstensi == 'png' || $ekstensi == 'PNG') : ?>
                                                            <a href="<?= base_url(); ?>/assets/stream_file/<?= $file->nama_file ?>" target="_blank" class="w-3/5 file__icon file__icon--image mx-auto">
                                                                <div class="file__icon--image__preview image-fit">
                                                                    <img alt="img" src="<?= base_url(); ?>/assets/stream_file/<?= $file->nama_file; ?>">
                                                                </div>
                                                            </a>
                                                        <?php else : ?>
                                                            <a href="<?= base_url(); ?>/assets/stream_file/<?= $file->nama_file ?>" target="_blank" class="w-3/5 file__icon file__icon--file mx-auto">
                                                                <div class="file__icon__file-name"><?= $ekstensi; ?></div>
                                                            </a>
                                                        <?php endif; ?>
                                                        <a href="<?= base_url(); ?>/assets/stream_file/<?= $file->nama_file ?>" target="_blank" class="block font-medium mt-4 text-center truncate"><?= $file->nama_file; ?> <i class="bi bi-box-arrow-up-right"></i></a>
                                                        <a href="<?= base_url(); ?>/unduh/stream/<?= encrypt_url($file->nama_file); ?>" class="block font-medium mt-4 text-center truncate"><i class="bi bi-download"></i> Download</a>
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
                                                <div id="accordion_komen_stream<?= $accordion++ ?>">

                                                </div>
                                                <div class="news__input relative mt-5">
                                                    <i data-feather="message-circle" class="w-5 h-5 absolute my-auto inset-y-0 ml-6 left-0 text-gray-600"></i>
                                                    <input type="hidden" id="id_kelas<?= $id_kelas++; ?>" value="<?= $stream->kelas_id; ?>">
                                                    <input type="hidden" id="stream_code<?= $stream_code++; ?>" value="<?= $stream->stream_kode; ?>">
                                                    <textarea wrap="hard" id="isi_komen<?= $isi_komen++; ?>" class="input w-full bg-gray-200 pl-16 py-6 placeholder-theme-13 resize-none" rows="1" placeholder="Post a comment..."></textarea>
                                                    <button id="kirim_komen<?= $kirim_komen++; ?>" class="button w-32 mr-2 mt-2 flex items-center justify-center bg-theme-14 text-theme-10"> <i data-feather="send" class="w-4 h-4 mr-2"></i> Send </button>
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
            <?php endforeach; ?>
        </div>
    </div>
</div>
<!-- END: Content -->
<script>
    <?php
    $no = 1;
    $no2 = 1;
    $id_kelas = 1;
    $stream_code = 1;
    $isi_komen = 1;
    $isi_komen2 = 1;
    $kirim_komen = 1;
    foreach ($data_stream as $stream) : ?>

        function komen_stream<?= $no++ ?>() {
            $('#kirim_komen<?= $kirim_komen++; ?>').click(function() {
                var id_kelas = $('#id_kelas<?= $id_kelas++; ?>').val();
                var kode_stream = $('#stream_code<?= $stream_code++; ?>').val();
                var isi_komen = $('#isi_komen<?= $isi_komen++; ?>').val();
                $.ajax({
                    type: 'POST',
                    data: {
                        id_kelas: id_kelas,
                        kode_stream: kode_stream,
                        isi_komen: isi_komen
                    },
                    url: "<?= base_url() ?>/stream/classtream",
                    dataType: 'JSON',
                    success: function(response) {
                        console.log(response.responseText);
                    }
                });
                $('#isi_komen<?= $isi_komen2++; ?>').val('');
            });
        }
        komen_stream<?= $no2++; ?>();
    <?php endforeach; ?>
    <?php
    $accordion = 1;
    $no = 1;
    $no2 = 1;
    $id_kelas = 1;
    $stream_code = 1;
    $isi_komen = 1;
    $kirim_komen = 1;
    foreach ($data_stream as $stream) : ?>

        function get_komen_stream<?= $no++; ?>() {
            var id_kelas = $('#id_kelas<?= $id_kelas++; ?>').val();
            var kode_stream = $('#stream_code<?= $stream_code++; ?>').val();
            setInterval(() => {
                $.ajax({
                    type: 'POST',
                    data: {
                        id_kelas: id_kelas,
                        kode_stream: kode_stream
                    },
                    async: true,
                    url: "<?= base_url() ?>/stream/getclasstream",
                    success: function(data) {
                        document.getElementById("accordion_komen_stream<?= $accordion++ ?>").innerHTML = data;
                    }
                });
            }, 3000);
        }
        get_komen_stream<?= $no2++; ?>();

    <?php endforeach; ?>

    getrandomcode();

    function getrandomcode() {
        let chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        let classCodeLength = 10;
        let classCode = "";

        for (let i = 0; i < classCodeLength; i++) {
            let randomNumber = Math.floor(Math.random() * chars.length);
            classCode += chars.substring(randomNumber, randomNumber + 1);

        }
        document.getElementById("random_code").value = classCode

    }
</script>


<?= $this->endSection(); ?>
