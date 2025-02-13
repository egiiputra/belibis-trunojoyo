<?= $this->extend('layout/user/dashboard'); ?>
<?= $this->section('content'); ?>
<?= session()->getFlashdata('pesan'); ?>
<!-- BEGIN: Content -->
<div class="content">
    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Home
        </h2>
        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
            <div class="pos-dropdown dropdown relative ml-auto sm:ml-0">
                <button class="dropdown-toggle button px-2 box text-white bg-theme-1">
                    <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-feather="plus-square"></i> </span>
                </button>
                <div class="pos-dropdown__dropdown-box dropdown-box mt-10 absolute top-0 right-0 z-20">
                    <div class="dropdown-box__content box dark:bg-dark-1 p-2">
                        <a href="javascript:;" onclick="getrandomcode()" data-toggle="modal" data-target="#modal-class-create" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md"> <i data-feather="monitor" class="w-4 h-4 mr-2"></i> <span class="truncate">Add New Class</span> </a>
                        <a href="javascript:;" data-toggle="modal" data-target="#modal-class-code" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md"> <i data-feather="key" class="w-4 h-4 mr-2"></i> <span class="truncate">Join Class</span> </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="intro-y grid grid-cols-12 gap-6 mt-5">
        <!-- BEGIN: Class Layout -->
        <?php foreach ($myclass as $class) : ?>
            <div class="intro-y col-span-12 md:col-span-6 xl:col-span-4 box">
                <div class="p-5">
                    <a href="<?= base_url(); ?>/user/classes/<?= encrypt_url($class->kode_kelas); ?>">
                        <div class="h-40 xxl:h-56 image-fit">
                            <img alt="img" class="rounded-md" src="<?= base_url(); ?>/vendor/dist/<?= $class->bg_class; ?>">
                        </div>
                        <span class="block font-medium text-base mt-5"><?= $class->mapel; ?></span>
                        <div class="text-gray-700 dark:text-gray-600 mt-2 flex"><?= $class->nama_kelas; ?>
                            <div class="text-gray-700 dark:text-gray-600 ml-auto">
                                <a href="javascript:void(0);" class="button button--sm bg-theme-1 text-white lihat-kode" data-toggle="modal" data-target="#modal-lihat-kode-kelas" data-kode_kelas="<?= $class->kode_kelas; ?>">
                                    lihat kode kelas
                                </a>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="px-5 pt-3 pb-5 border-t border-gray-200 dark:border-dark-5">
                    <div class="w-full flex text-gray-600 text-xs sm:text-sm">
                        <div class="mr-2">My Class</div>
                        <div class="text-center">
                            <a href="javascript:;" data-toggle="modal" data-target="#header-footer-modal-preview" data-kelas_saya="<?= $class->kode_kelas; ?>" class="text-theme-1 ml-5 btn-edit_kelas_saya">Edit Kelas</a>
                            <a href="<?= base_url(); ?>/su/hapus_kelas/<?= encrypt_url($class->kode_kelas); ?>" title="Hapus Kelas" class="text-theme-6 ml-5 hapus-kelas">Hapus Kelas</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php foreach ($classes as $class) : ?>
            <div class="intro-y col-span-12 md:col-span-6 xl:col-span-4 box">
                <div class="flex items-center border-b border-gray-200 dark:border-dark-5 px-5 py-4">
                    <div class="w-10 h-10 flex-none image-fit">
                        <img alt="img" class="rounded-full" src="<?= base_url(); ?>/vendor/dist/user/<?= $class->gambar_user; ?>">
                    </div>
                    <div class="ml-3 mr-auto">
                        <a href="" class="font-medium"><?= $class->nama_user; ?></a>
                        <div class="flex text-gray-600 truncate text-xs mt-1"><?= $class->email_user; ?></div>
                    </div>
                </div>
                <div class="p-5">
                    <a href="<?= base_url(); ?>/user/classes/<?= encrypt_url($class->kelas_kode); ?>">
                        <div class="h-40 xxl:h-56 image-fit">
                            <img alt="img" class="rounded-md" src="<?= base_url(); ?>/vendor/dist/<?= $class->bg_class; ?>">
                        </div>
                    </a>
                    <a href="<?= base_url(); ?>/user/classes/<?= encrypt_url($class->kelas_kode); ?>" class="block font-medium text-base mt-5 flex"><?= $class->mapel; ?></a>
                    <div class="text-gray-700 dark:text-gray-600 mt-2 flex"><?= $class->nama_kelas; ?>
                        <div class="text-gray-700 dark:text-gray-600 ml-auto copy-code">
                            <a href="javascript:void(0);" class="button button--sm bg-theme-1 text-white lihat-kode" data-toggle="modal" data-target="#modal-lihat-kode-kelas" data-kode_kelas="<?= $class->kelas_kode; ?>">
                                lihat kode kelas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <!-- END: Class Layout -->
    </div>
</div>

<div class="modal" id="header-footer-modal-preview">
    <div class="modal__content">
        <div class="flex items-center px-5 py-5 sm:py-3 border-b border-gray-200 dark:border-dark-5">
            <h2 class="font-medium text-base mr-auto">Edit Class</h2>
        </div>
        <div class="px-5 py-5">
            <form action="<?= base_url(); ?>/user/editclass" method="POST" class="validate-form" enctype="multipart/form-data">
                <div id="edit_kelas">
                    <div class="mt-3">
                        <input type="hidden" name="e_id_kelas" id="e_id_kelas" class="input w-full border mt-2" value="" required>
                        <label class="flex flex-col sm:flex-row"> Class Name
                            <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-gray-600">Required</span>
                        </label>
                        <input type="text" name="e_nama_kelas" id="e_nama_kelas" class="input w-full border mt-2" value="" required>
                    </div>
                    <div class="mt-3">
                        <label class="flex flex-col sm:flex-row"> Class Subject
                            <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-gray-600">Required</span>
                        </label>
                        <input type="text" name="e_mapel" id="e_mapel" class="input w-full border mt-2" value="" required>
                    </div>
                    <div class="mt-3">
                        <label class="flex flex-col sm:flex-row"> Class banner <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-gray-600">Optional</span> </label>
                        <div class="border border-gray-200 dark:border-dark-5 rounded-md p-5">
                            <div class="h-40 xxl:h-56 image-fit">
                                <img id="preview-gambar-edit" class="rounded-md" alt="img" src="<?= base_url(); ?>/vendor/dist/images/bg-class.png">
                            </div>
                            <div class="w-40 mx-auto cursor-pointer relative mt-5">
                                <button type="button" class="button w-full bg-theme-1 text-white">Change Photo</button>
                                <input type="hidden" name="gambar_lama" class="input border mt-2 flex w-full" value="">
                                <input type="file" accept="image/*" id="gambar_kelas" name="gambar_kelas" class="w-full h-full top-0 left-0 absolute opacity-0">
                            </div>
                        </div>
                    </div>
    
                    <!--<div class="mt-3">
                        <label class="flex flex-col sm:flex-row"> Class Banner </label>
                        <input type="file" name="gambar_kelas" class="input border mt-2 flex w-full">
                    </div>-->
                    <button type="submit" class="button bg-theme-1 text-white mt-5">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- BEGIN: LIHAT KELAS KODE -->
<div class="modal" id="modal-lihat-kode-kelas">
    <div class="modal__content">
        <div class="flex items-center px-5 py-5 sm:py-3 border-b border-gray-200 dark:border-dark-5">
            <h2 class="font-medium text-base mr-auto">CLASS CODE</h2>
        </div>
        <div class="px-5 py-5">
            <div>
                <label class="flex flex-col sm:flex-row"> Class Code</label>
                <input type="text" id="input_kode_kelas" class="input w-full border mt-2" readonly>
            </div>
            <button type="button" class="button bg-theme-1 text-white mt-5" onclick="myFunction()">Copy Code</button>
        </div>
    </div>
</div>
<!-- END: LIHAT KELAS KODE -->


<!-- BEGIN: Modal Class Code -->
<div class="modal" id="modal-class-code">
    <div class="modal__content">
        <div class="flex items-center px-5 py-5 sm:py-3 border-b border-gray-200 dark:border-dark-5">
            <h2 class="font-medium text-base mr-auto">Join Class</h2>
        </div>
        <div class="px-5 py-5">
            <form action="<?= base_url(); ?>/user/joinclass" method="POST" class="validate-form">
                <div>
                    <label class="flex flex-col sm:flex-row"> Class Code <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-gray-600">Required, 10 characters</span> </label>
                    <input type="text" name="kelas_kode" class="input w-full border mt-2" placeholder="Enter Your Class Code" minlength="10" maxlength="10" required autocomplete="off">
                </div>
                <button type="submit" class="button bg-theme-1 text-white mt-5">Join</button>
            </form>
        </div>
    </div>
</div>
<!-- END: Modal Class Code -->


<!-- BEGIN: Modal create new class -->
<div class="modal" id="modal-class-create">
    <div class="modal__content">
        <div class="flex items-center px-5 py-5 sm:py-3 border-b border-gray-200 dark:border-dark-5">
            <h2 class="font-medium text-base mr-auto">Create Class</h2>
        </div>
        <div class="px-5 py-5">
            <form action="<?= base_url(); ?>/user/createclass" method="POST" class="validate-form" enctype="multipart/form-data">
                <div class="mt-3">
                    <label class="flex flex-col sm:flex-row"> Class Name <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-gray-600">Required</span> </label>
                    <input type="text" name="nama_kelas" class="input w-full border mt-2" placeholder="Enter Your Class Name" required autocomplete="off">
                </div>
                <div class="mt-3">
                    <label class="flex flex-col sm:flex-row"> Class Subject <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-gray-600">Required</span> </label>
                    <input type="text" name="mapel" class="input w-full border mt-2" placeholder="Enter Your Class Subject" required autocomplete="off">
                    <input type="hidden" name="kelas_kode" id="random_code" class="input w-full border mt-2" placeholder="Enter Your Class Code" required autocomplete="off">
                </div>
                <div class="mt-3">
                    <label class="flex flex-col sm:flex-row"> Class banner <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-gray-600">Optional</span> </label>
                    <div class="border border-gray-200 dark:border-dark-5 rounded-md p-5">
                        <div class="h-40 xxl:h-56 image-fit">
                            <img id="preview-gambar" class="rounded-md" alt="img" src="<?= base_url(); ?>/vendor/dist/images/bg-class.png">
                        </div>
                        <div class="w-40 mx-auto cursor-pointer relative mt-5">
                            <button type="button" class="button w-full bg-theme-1 text-white">Change Photo</button>
                            <input type="file" accept="image/*" id="gambar" name="gambar" class="w-full h-full top-0 left-0 absolute opacity-0">
                        </div>
                    </div>
                </div>
                <button type="submit" class="button bg-theme-1 text-white mt-5">Create</button>
            </form>
        </div>
    </div>
</div>
<!-- END: Modal create new class -->

<script>
// Select the file input and image preview elements
const fileInput = document.getElementById('gambar');
const imagePreview = document.getElementById('preview-gambar');

// Add an event listener to handle file input changes
fileInput.addEventListener('change', (event) => {
    const file = event.target.files[0]; // Get the selected file

    if (file) {
        // Check if the file is an image
        if (file.type.startsWith('image/')) {
            // Create a URL for the image and set it as the src of the preview
            const imageURL = URL.createObjectURL(file);
            imagePreview.src = imageURL;
        } else {
            alert('Please select an image file.');
        }
    }
});

// Select the file input and image preview elements
const editFileInput = document.getElementById('gambar_kelas');
const editImagePreview = document.getElementById('preview-gambar-edit');

// Add an event listener to handle file input changes
editFileInput.addEventListener('change', (event) => {
    const file = event.target.files[0]; // Get the selected file

    if (file) {
        // Check if the file is an image
        if (file.type.startsWith('image/')) {
            // Create a URL for the image and set it as the src of the preview
            const imageURL = URL.createObjectURL(file);
            editImagePreview.src = imageURL;
        } else {
            alert('Please select an image file.');
        }
    }
});


$('#e_nama_kelas').val("data[0].nama_kelas");
    $(document).ready(function() {
        $('.hapus-kelas').click(function(e) {
            const href = $(this).attr('href');
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "Data yang bersangkutan akan ikut terhapus!",
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

        // $('.btn-edit_kelas_saya').click(function() {
        //     const kelas_saya = $(this).data('kelas_saya');
        //     $.ajax({
        //         type: 'post',
        //         data: {
        //             kelas_saya: kelas_saya
        //         },
        //         // dataType: 'json',
        //         async: true,
        //         url: "<?= base_url() ?>/user/ajaxeditkelas",
        //         success: function(data) {
        //             console.log(data);
        //         }
        //     })
        // });

    });

    document.onkeydown = function(e) {
        if (e.ctrlKey &&
            (e.keyCode === 67 ||
                e.keyCode === 85 ||
                e.keyCode === 117)) {
            Swal.fire('Oops', 'ctrl+u,c disabled', 'error');
            return false;
        } else {
            return true;
        }
    };
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

    function myFunction() {
        /* Get the text field */
        var copyText = document.getElementById("input_kode_kelas");

        /* Select the text field */
        copyText.select();
        copyText.setSelectionRange(0, 99999); /* For mobile devices */

        /* Copy the text inside the text field */
        document.execCommand("copy");

        /* Alert the copied text */
        // alert("Copied the text: " + copyText.value);
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'kode kelas berhasil di copy : ' + copyText.value
        });
    }
</script>
<?= $this->endSection(); ?>
