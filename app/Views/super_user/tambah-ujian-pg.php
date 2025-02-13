<?= $this->extend('layout/user/kompleks'); ?>
<?= $this->section('content'); ?>
<?= session()->getFlashdata('pesan'); ?>

<form action="<?= base_url(); ?>/su/addpg_/<?= encrypt_url($kelas->kode_kelas); ?>" method="POST" class="validate-form">
    <div class="content">
        <div class="intro-y flex items-center mt-8">
            <h2 class="text-lg font-medium mr-auto">
                Add New Exam ( PILIHAN GANDA )
            </h2>
        </div>
        <button type="button" class="button bg-theme-1 text-white btn_tambah_soal border border-gray-200" style="position: fixed; right: -5px; top: 50%; z-index: 999;">Tambah Soal</button>
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="col-span-12 lg:col-span-1"></div>
            <div class="intro-y col-span-12 lg:col-span-10">
                <!-- BEGIN: Form Validation -->
                <div class="intro-y box">
                    <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200 dark:border-dark-5">
                        <h2 class="font-medium text-base mr-auto">
                            Form Exam
                        </h2>
                        <a href="javascript:;" data-toggle="modal" data-target="#header-footer-modal-preview" class="button bg-theme-1 text-white">Import Soal</a>
                    </div>
                    <div class="p-5">
                        <div class="preview">
                            <div>
                                <label class="flex flex-col sm:flex-row"> Title <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-gray-600">Required</span> </label>
                                <input type="text" name="nama_ujian" class="input w-full border mt-2" required>
                            </div>
                            <div class="mt-3">
                                <label class="flex flex-col sm:flex-row"> Waktu Ujian <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-gray-600">Required</span> </label>
                                <div class="grid grid-cols-12 gap-2">
                                    <input type="number" name="waktu_jam" placeholder="JAM" class="input w-full border mt-2 col-span-6">
                                    <input type="number" name="waktu_menit" placeholder="MENIT" class="input w-full border mt-2 col-span-6" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: Form Validation -->
            </div>
        </div>
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="col-span-12 lg:col-span-1"></div>
            <div class="intro-y col-span-12 lg:col-span-10">
                <!-- BEGIN: Form Validation -->
                <div class="intro-y box">
                    <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200 dark:border-dark-5">
                        <h2 class="font-medium text-base mr-auto">
                            Soal Ujian
                        </h2>
                    </div>
                    <div class="p-5" id="soal-pg">
                        <div class="isi-soal">
                            <label>Soal</label><br>
                            <textarea name="nama_soal[]" cols="30" rows="2" class="input w-full border summernote-1" required></textarea>
                            <div class="grid grid-cols-12 gap-2 mt-3">
                                <div class="relative col-span-12 lg:col-span-4 sm:col-span-12">
                                    <div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-gray-100 dark:bg-dark-1 dark:border-dark-4 border text-gray-600">A</div>
                                    <input type="text" name="pg_a[]" class="pl-12 input w-full border" placeholder="OPSI A" required>
                                </div>
                                <div class="relative col-span-12 lg:col-span-4 sm:col-span-12">
                                    <div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-gray-100 dark:bg-dark-1 dark:border-dark-4 border text-gray-600">B</div>
                                    <input type="text" name="pg_b[]" class="pl-12 input w-full border" placeholder="OPSI B" required>
                                </div>
                                <div class="relative col-span-12 lg:col-span-4 sm:col-span-12">
                                    <div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-gray-100 dark:bg-dark-1 dark:border-dark-4 border text-gray-600">C</div>
                                    <input type="text" name="pg_c[]" class="pl-12 input w-full border" placeholder="OPSI C" required>
                                </div>
                                <div class="relative col-span-12 lg:col-span-4 sm:col-span-12">
                                    <div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-gray-100 dark:bg-dark-1 dark:border-dark-4 border text-gray-600">D</div>
                                    <input type="text" name="pg_d[]" class="pl-12 input w-full border" placeholder="OPSI D" required>
                                </div>
                                <div class="relative col-span-12 lg:col-span-4 sm:col-span-12">
                                    <div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-gray-100 dark:bg-dark-1 dark:border-dark-4 border text-gray-600">E</div>
                                    <input type="text" name="pg_e[]" class="pl-12 input w-full border" placeholder="OPSI E" required>
                                </div>
                                <div class="relative col-span-12 lg:col-span-4 sm:col-span-12">
                                    <div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-gray-100 dark:bg-dark-1 dark:border-dark-4 border text-gray-600"><i data-feather="bookmark"></i></div>
                                    <input type="text" name="jawaban[]" class="pl-12 input w-full border" placeholder="Jawaban - CONTOH : A">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center p-5 border-t border-gray-200 dark:border-dark-5">
                        <button type="submit" class="button bg-theme-1 text-white">Post</button>
                        <a href="<?= base_url(); ?>/user/ujian/<?= encrypt_url($kelas->kode_kelas); ?>" class="button bg-theme-14 text-theme-10 ml-5">
                            cancel
                        </a>
                    </div>
                </div>
                <!-- END: Form Validation -->
            </div>
        </div>
    </div>
    <!-- END: Content -->
</form>

<div class="modal" id="header-footer-modal-preview">
    <div class="modal__content">
        <div class="flex items-center px-5 py-5 sm:py-3 border-b border-gray-200 dark:border-dark-5">
            <h2 class="font-medium text-base mr-auto">Import Excel</h2>
            <a href="<?= base_url(); ?>/unduh/excel_pg" class=" button bg-theme-1 text-white">Download Template</a>
        </div>
        <div class="px-5 py-5">
            <form action="<?= base_url(); ?>/su/pg_excel/<?= encrypt_url($kelas->kode_kelas); ?>" method="POST" class="validate-form" enctype="multipart/form-data">
                <div class="mt-3">
                    <label class="flex flex-col sm:flex-row"> Title
                        <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-gray-600">Required</span>
                    </label>
                    <input type="text" name="e_nama_ujian" class="input w-full border mt-2" required autocomplete="off">
                </div>
                <div class="mt-3">
                    <label class="flex flex-col sm:flex-row"> Waktu Ujian
                        <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-gray-600">Required</span>
                    </label>
                    <div class="grid grid-cols-12 gap-2">
                        <input type="number" name="e_waktu_jam" placeholder="JAM" class="input w-full border mt-2 col-span-6">
                        <input type="number" name="e_waktu_menit" placeholder="MENIT" class="input w-full border mt-2 col-span-6" required>
                    </div>
                </div>
                <div class="mt-3">
                    <label class="flex flex-col sm:flex-row"> File Excel </label>
                    <input type="file" name="file_excel" class="input border mt-2 flex w-full">
                </div>
                <button type="submit" class="button bg-theme-1 text-white mt-5">Submit</button>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        $('.summernote-1').summernote({
            toolbar: [
                ["style", ["bold", "italic", "underline", "clear"]],
                ["fontname", ["fontname"]],
                ["fontsize", ["fontsize"]],
                ["color", ["color"]],
                ["para", ["ul", "ol", "paragraph"]],
                ["height", ["height"]],
                ["insert", ["picture", "imageList", "video", "hr"]],
            ],
            callbacks: {
                onImageUpload: function(files, which_sum = this) {
                    for (let i = 0; i < files.length; i++) {
                        $.upload(files[i], which_sum);
                    }
                },
                onMediaDelete: function(target) {
                    $.delete(target[0].src);
                }
            },
            dialogsInBody: true,
            imageList: {
                endpoint: "<?= base_url('su/listGambar') ?>",
                fullUrlPrefix: "<?= base_url('assets/stream_file') ?>/",
                thumbUrlPrefix: "<?= base_url('assets/stream_file') ?>/"
            }
        });

        $('.btn_tambah_soal').click(function() {
            const pg = `
            <div class="isi-soal mt-5">
            <hr>
            <br>
                <label>Soal</label><br>
                <textarea name="nama_soal[]" cols="30" rows="2" class="input w-full border mt-3 summernote-1" required></textarea>
                <div class="grid grid-cols-12 gap-2 mt-3">
                    <div class="relative col-span-12 lg:col-span-4 sm:col-span-12">
                         <div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-gray-100 dark:bg-dark-1 dark:border-dark-4 border text-gray-600">A</div>
                         <input type="text" name="pg_a[]" class="pl-12 input w-full border" placeholder="OPSI A" required>
                     </div>
                     <div class="relative col-span-12 lg:col-span-4 sm:col-span-12">
                         <div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-gray-100 dark:bg-dark-1 dark:border-dark-4 border text-gray-600">B</div>
                         <input type="text" name="pg_b[]" class="pl-12 input w-full border" placeholder="OPSI B" required>
                     </div>
                     <div class="relative col-span-12 lg:col-span-4 sm:col-span-12">
                         <div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-gray-100 dark:bg-dark-1 dark:border-dark-4 border text-gray-600">C</div>
                         <input type="text" name="pg_c[]" class="pl-12 input w-full border" placeholder="OPSI C" required>
                     </div>
                     <div class="relative col-span-12 lg:col-span-4 sm:col-span-12">
                         <div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-gray-100 dark:bg-dark-1 dark:border-dark-4 border text-gray-600">D</div>
                         <input type="text" name="pg_d[]" class="pl-12 input w-full border" placeholder="OPSI D" required>
                     </div>
                     <div class="relative col-span-12 lg:col-span-4 sm:col-span-12">
                         <div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-gray-100 dark:bg-dark-1 dark:border-dark-4 border text-gray-600">E</div>
                         <input type="text" name="pg_e[]" class="pl-12 input w-full border" placeholder="OPSI E" required>
                     </div>
                     <div class="relative col-span-12 lg:col-span-4 sm:col-span-12">
                         <div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-gray-100 dark:bg-dark-1 dark:border-dark-4 border text-gray-600"><i data-feather="bookmark"></i></div>
                         <input type="text" name="jawaban[]" class="pl-12 input w-full border" placeholder="Jawaban - CONTOH : A" required>
                     </div>
                </div>
                <div class="mt-3">
                    <button type="button" class="button button-hapus bg-theme-6 text-white mt-5">Hapus</button>
                </div>
            </div>

            `;
            $('#soal-pg').append(pg);

            $('.summernote-1').summernote({
                toolbar: [
                    ["style", ["bold", "italic", "underline", "clear"]],
                    ["fontname", ["fontname"]],
                    ["fontsize", ["fontsize"]],
                    ["color", ["color"]],
                    ["para", ["ul", "ol", "paragraph"]],
                    ["height", ["height"]],
                    ["insert", ["picture", "imageList", "video", "hr"]],
                ],
                callbacks: {
                    onImageUpload: function(files, which_sum = this) {
                        for (let i = 0; i < files.length; i++) {
                            $.upload(files[i], which_sum);
                        }
                    },
                    onMediaDelete: function(target) {
                        $.delete(target[0].src);
                    }
                },
                dialogsInBody: true,
                imageList: {
                    endpoint: "<?= base_url('su/listGambar') ?>",
                    fullUrlPrefix: "<?= base_url('assets/stream_file') ?>/",
                    thumbUrlPrefix: "<?= base_url('assets/stream_file') ?>/"
                }
            });
        });

        $('#soal-pg').on('click', '.isi-soal .button-hapus', function() {
            $(this).parents('.isi-soal').remove();
        });

        setInterval(() => {
            $('.note-modal-backdrop').css('display', 'none');
        }, 1000);

        $.upload = function(file, which_sum) {
            let out = new FormData();
            out.append('file', file, file.name);
            $.ajax({
                method: 'POST',
                url: '<?= base_url('su/upload_image') ?>',
                contentType: false,
                cache: false,
                processData: false,
                data: out,
                success: function(img) {
                    $(which_sum).summernote('insertImage', img);
                    alert(which_sum);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error(textStatus + " " + errorThrown);
                }
            });
        };

        $.delete = function(src) {
            $.ajax({
                method: 'POST',
                url: '<?= base_url('su/delete_image') ?>',
                cache: false,
                data: {
                    src: src
                },
                success: function(response) {
                    console.log(response);
                }

            });
        };
    })
</script>

<?= $this->endSection(); ?>