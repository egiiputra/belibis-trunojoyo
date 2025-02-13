<?= $this->extend('layout/user/kompleks'); ?>
<?= $this->section('content'); ?>
<?= session()->getFlashdata('pesan'); ?>

<!-- BEGIN: Content -->
<div class="content">
    <div class="intro-y mt-8">
        <h2 class="text-lg font-medium mr-auto">
            <?= $kelas->mapel . ' ' . $kelas->nama_kelas; ?>
        </h2>
        <div class="mt-2">
            <a href="<?= base_url(); ?>/user/assignment/<?= encrypt_url($data_kode_kelas); ?>">
                <button class="button w-32 mt-2 bg-theme-1 text-white"> Back to List </button>
            </a>
        </div>
    </div>
    <div class="pos intro-y grid grid-cols-12 gap-5 mt-5">
        <!-- BEGIN: Work -->
        <div class="col-span-12 lg:col-span-12">
            <div class="intro-y pr-1">
                <div class="box p-2">
                    <div class="pos__tabs nav-tabs justify-center flex"> <a data-toggle="tab" data-target="#ticket" href="javascript:;" class="flex-1 py-2 rounded-md text-center <?= (session()->getFlashdata('tab-tugas') != 'true') ? 'active' : '' ?>">Detail</a> <a data-toggle="tab" data-target="#details" href="javascript:;" class="flex-1 py-2 rounded-md text-center <?= (session()->getFlashdata('tab-tugas') == 'true') ? 'active' : '' ?>">My Work</a> </div>
                </div>
            </div>
            <div class="tab-content">
                <div class="tab-content__pane <?= (session()->getFlashdata('tab-tugas') != 'true') ? 'active' : '' ?>" id="ticket">
                    <div class="box p-5 mt-5">
                        <h2 class="font-medium text-xl sm:text-1xl">
                            <?= $tugas->title; ?>
                        </h2>
                        <div class="text-gray-700 dark:text-gray-600 mt-3 text-xs sm:text-sm">
                            <?= date('d-l-M-Y h:i a', $tugas->date_created); ?>
                            <span class="mx-1">•</span>
                            <a class="text-theme-1 dark:text-theme-10" href="">
                                <?= $tugas->title; ?>
                            </a>
                            <?php if ($tugas->date_updated != NULL) {
                                echo "( Updated on " . date('d-l-M-Y h:i a', $tugas->date_updated) . " )";
                            } ?>
                        </div>
                        <div class="mt-2">
                            Due Date : <?= date('d-l-M-Y h:i a', strtotime($tugas->due_date)); ?>
                        </div>
                        <div class="flex text-xs sm:text-sm flex-col sm:flex-row items-center mt-5 pb-5 border-b border-gray-200 dark:border-dark-5">
                            <div class="flex items-center">
                                <div class="w-12 h-12 flex-none image-fit">
                                    <img alt="gambar" class="rounded-full" src="<?= base_url('vendor/dist'); ?>/user/<?= $tugas->gambar; ?>">
                                </div>
                                <div class="ml-3 mr-auto">
                                    <a href="" class="font-medium"><?= $tugas->nama; ?></a>
                                    <div class="text-gray-600"><?= $tugas->email; ?></div>
                                </div>
                            </div>
                        </div>
                        <!-- Isi Tugas -->
                        <p class="text-justify mt-5 whitespace-pre-line"><?= $tugas->description; ?></p>

                        <!-- File File -->
                        <div class="intro-y grid grid-cols-12 gap-3 sm:gap-6 mt-5">
                            <?php foreach ($file_stream as $file) : ?>
                                <?php if ($file->kode_stream == $tugas->kode_tugas) : ?>
                                    <div class="intro-y col-span-6 sm:col-span-4 md:col-span-3 xxl:col-span-2">
                                        <div class="file box rounded-md px-5 pt-8 pb-5 px-3 sm:px-5 relative zoom-in">
                                            <?php
                                            $berkas = base_url() . '/assets/stream_file/' . $file->nama_file;
                                            $ekstensi = pathinfo($berkas, PATHINFO_EXTENSION);
                                            if ($ekstensi == 'jpg' || $ekstensi == 'JPG' || $ekstensi == 'jpeg' || $ekstensi == 'JPEG' || $ekstensi == 'png' || $ekstensi == 'PNG') : ?>
                                                <a href="<?= base_url(); ?>/download/stream/<?= encrypt_url($file->nama_file); ?>" class="w-3/5 file__icon file__icon--image mx-auto">
                                                    <div class="file__icon--image__preview image-fit">
                                                        <img alt="img" src="<?= base_url(); ?>/assets/stream_file/<?= $file->nama_file; ?>">
                                                    </div>
                                                </a>
                                            <?php else : ?>
                                                <a href="<?= base_url(); ?>/unduh/stream/<?= encrypt_url($file->nama_file); ?>" class="w-3/5 file__icon file__icon--file mx-auto">
                                                    <div class="file__icon__file-name"><?= $ekstensi; ?></div>
                                                </a>
                                            <?php endif; ?>
                                            <a href="" class="block font-medium mt-4 text-center truncate"><?= $file->nama_file ?></a>
                                            <div class="text-gray-600 text-xs text-center"><?= Ukuran('./assets/stream_file/' . $file->nama_file); ?></div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>

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
                                        <div id="accordion_komen_stream_tugas">

                                        </div>
                                        <div class="news__input relative mt-5">
                                            <input type="hidden" id="tugas_code" value="<?= $tugas->kode_tugas; ?>">
                                            <i data-feather="message-circle" class="w-5 h-5 absolute my-auto inset-y-0 ml-6 left-0 text-gray-600" style="bottom: 50px;"></i>
                                            <textarea wrap="hard" id="isi_komen" class="input w-full bg-gray-200 pl-16 py-6 placeholder-theme-13 resize-none" rows="1" placeholder="Post a comment..."></textarea>
                                            <button id="kirim_komen_tugas" class="button w-32 mr-2 mt-2 flex items-center justify-center bg-theme-14 text-theme-10"> <i data-feather="send" class="w-4 h-4 mr-2"></i> Send </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END: Comments -->
                    </div>
                </div>
                <div class="tab-content__pane <?= (session()->getFlashdata('tab-tugas') == 'true') ? 'active' : '' ?>" id="details">
                    <div class="box p-5 mt-5">
                        <div class="grid grid-cols-12 gap-6 mt-5">
                            <!-- BEGIN: Data List -->
                            <div class="intro-y col-span-12 overflow-auto lg:overflow-visible" style="margin-top: -25px;">
                                <?php if ($tugas_saya != null) : ?>
                                    <h2 class="text-lg font-medium mr-auto">Your Answer</h2>
                                    <p>Score :
                                        <?php if ($tugas_saya->grade == null) : ?>
                                            <span class="text-theme-9"> Not Gradded yet</span>
                                        <?php else : ?>
                                            <span class="text-theme-9"> <?= $tugas_saya->grade; ?> / 100</span>
                                        <?php endif; ?>
                                        <br>
                                        Status :
                                        <?php if ($tugas_saya->is_late == 0) : ?>
                                            <span class="text-theme-9">Success</span>
                                        <?php else : ?>
                                            <span class="text-theme-6"> Turn in Late</span>
                                        <?php endif; ?>
                                    </p>
                                    <br>
                                    <p class="text-justify whitespace-pre">
                                        <?php
                                        $search = array(
                                            '&gt;',
                                            '&lt;',
                                            'width="640"'
                                        );

                                        $replace = array(
                                            '>',
                                            '<',
                                            'width="100%"'
                                        );
                                        ?>
                                        <?= str_replace($search, $replace, $tugas_saya->teks); ?>
                                    </p>
                                    <div class="intro-y grid grid-cols-12 gap-3 sm:gap-6 mt-5">
                                        <?php foreach ($file_stream as $file) : ?>
                                            <?php if ($file->kode_stream == $tugas_saya->kode_user_tugas) : ?>
                                                <div class="intro-y col-span-6 sm:col-span-4 md:col-span-3 xxl:col-span-2">
                                                    <div class="file box rounded-md px-5 pt-8 pb-5 px-3 sm:px-5 relative zoom-in">
                                                        <?php
                                                        $berkas = base_url() . '/assets/stream_file/' . $file->nama_file;
                                                        $ekstensi = pathinfo($berkas, PATHINFO_EXTENSION);
                                                        if ($ekstensi == 'jpg' || $ekstensi == 'JPG' || $ekstensi == 'jpeg' || $ekstensi == 'JPEG' || $ekstensi == 'png' || $ekstensi == 'PNG') : ?>
                                                            <a href="<?= base_url(); ?>/download/stream/<?= encrypt_url($file->nama_file); ?>" class="w-3/5 file__icon file__icon--image mx-auto">
                                                                <div class="file__icon--image__preview image-fit">
                                                                    <img alt="img" src="<?= base_url(); ?>/assets/stream_file/<?= $file->nama_file; ?>">
                                                                </div>
                                                            </a>
                                                        <?php else : ?>
                                                            <a href="<?= base_url(); ?>/download/stream/<?= encrypt_url($file->nama_file); ?>" class="w-3/5 file__icon file__icon--file mx-auto">
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
                                <?php else : ?>
                                    <form action="<?= base_url(); ?>/user/submit/<?= encrypt_url($tugas->kelas_kode); ?>" method="POST" enctype="multipart/form-data" class="validate-form">
                                        <div>
                                            <textarea class="input w-full border mt-2" id="summernote" name="teks"></textarea>
                                        </div>
                                        <div class="mt-3">
                                            <input type="hidden" id="random_code" name="kode_user_tugas">
                                            <input type="hidden" name="kode_tugas" value="<?= $tugas->kode_tugas; ?>">
                                            <input type="hidden" id="kode_kelas" name="kode_kelas" value="<?= $tugas->kelas_kode; ?>">
                                            <input type="file" name="my_assignment_file[]" style="width: 265px;" class="input border mt-2 flex" multiple>
                                        </div>
                                        <button type="submit" class="button bg-theme-1 text-white mt-5">Submit</button>
                                    </form>
                                <?php endif; ?>
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
    $(document).ready(function() {

        $('#summernote').summernote({
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'video']]
            ]
        });
        setInterval(() => {
            $('.note-modal-backdrop').css('display', 'none');
        }, 1000);

        $('#kirim_komen_tugas').click(function() {
            var kode_tugas = $('#tugas_code').val();
            var isi_komen = $('#isi_komen').val();
            $.ajax({
                type: 'POST',
                data: {
                    kode_tugas: kode_tugas,
                    isi_komen: isi_komen
                },
                url: '<?= base_url() ?>/stream/tugas',
                dataType: 'JSON',
                success: function(response) {
                    console.log(response.responseText);
                }
            });
            $('#isi_komen').val('');
        });

        var kode_tugas = $('#tugas_code').val();
        setInterval(() => {
            $.ajax({
                type: 'POST',
                data: {
                    kode_tugas: kode_tugas
                },
                url: '<?= base_url() ?>/stream/gettugas',
                success: function(data) {
                    document.getElementById("accordion_komen_stream_tugas").innerHTML = data;
                }
            });
        }, 1000);

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
    });
</script>


<?= $this->endSection(); ?>