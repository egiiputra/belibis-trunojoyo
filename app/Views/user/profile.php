<?= $this->extend('layout/user/dashboard'); ?>
<?= $this->section('content'); ?>
<?= session()->getFlashdata('pesan'); ?>
<div class="content">
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            My Profile
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-6">
        <!-- BEGIN: Profile Menu -->
        <div class="col-span-12 lg:col-span-4 xxl:col-span-3 flex lg:block flex-col-reverse">
            <div class="intro-y box mt-5">
                <div class="relative flex items-center p-5">
                    <div class="w-12 h-12 image-fit">
                        <img alt="img" class="rounded-full" src="<?= base_url(); ?>/vendor/dist/user/<?= $user->gambar; ?>">
                    </div>
                    <div class="ml-4 mr-auto">
                        <div class="font-medium text-base"><?= $user->nama; ?></div>
                    </div>
                </div>
                <div class="p-5 border-t border-gray-200 dark:border-dark-5">
                    <a class="flex items-center text-theme-1 dark:text-theme-10 mt-1" href=""> <i data-feather="watch" class="w-4 h-4 mr-2"></i> Created Since </a>
                    <a class="flex items-center mt-2 ml-5" href=""> <?= date('d-l-M-Y h:i a', $user->date_created); ?></a>
                    <a class="flex items-center text-theme-1 dark:text-theme-10 mt-5" href=""> <i data-feather="settings" class="w-4 h-4 mr-2"></i> Registration Code </a>
                    <a class="flex items-center mt-2 ml-5" href=""> <?= $user->no_regis; ?></a>
                    <a class="flex items-center text-theme-1 dark:text-theme-10 mt-5" href=""> <i data-feather="mail" class="w-4 h-4 mr-2"></i> Email </a>
                    <a class="flex items-center mt-2 ml-5" href=""> <?= $user->email; ?></a>
                </div>
            </div>
        </div>
        <!-- END: Profile Menu -->
        <div class="col-span-12 lg:col-span-8 xxl:col-span-9">
            <!-- BEGIN: Display Information -->
            <div class="intro-y box lg:mt-5">
                <div class="flex items-center p-5 border-b border-gray-200 dark:border-dark-5">
                    <h2 class="font-medium text-base mr-auto">
                        Edit Information
                    </h2>
                </div>
                <div class="p-5">
                    <form action="<?= base_url(); ?>/user/editprofile" method="POST" enctype="multipart/form-data" class="validate-form">
                        <div class="grid grid-cols-12 gap-5">
                            <div class="col-span-12 xl:col-span-4">
                                <div class="border border-gray-200 dark:border-dark-5 rounded-md p-5">
                                    <div class="w-40 h-40 relative image-fit cursor-pointer mx-auto">
                                        <img id='preview-gambar' class="rounded-md" alt="img" src="<?= base_url(); ?>/vendor/dist/user/<?= $user->gambar; ?>">
                                    </div>
                                    <div class="w-40 mx-auto cursor-pointer relative mt-5">
                                        <input type="hidden" name="gambar_lama" value="<?= $user->gambar; ?>">
                                        <button type="button" class="button w-full bg-theme-1 text-white">Change Photo</button>
                                        <input type="file" accept="image/*" id="gambar" name="gambar" class="w-full h-full top-0 left-0 absolute opacity-0">
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 xl:col-span-8">
                                <div>
                                    <label>Name</label>
                                    <input type="text" name="nama" class="input w-full border mt-2" placeholder="Input text" value="<?= $user->nama; ?>" required>
                                </div>
                                <div class="mt-3">
                                    <label>Email</label>
                                    <input type="text" name="email" class="input w-full border bg-gray-100 cursor-not-allowed mt-2" placeholder="Input text" value="<?= $user->email; ?>" disabled>
                                </div>
                                <div class="mt-3">
                                    <label>Registration Code</label>
                                    <input type="text" name="no_regis" class="input w-full border bg-gray-100 cursor-not-allowed mt-2" placeholder="Input text" value="<?= $user->no_regis; ?>" readonly>
                                </div>
                                <button type="submit" class="button w-20 bg-theme-1 text-white mt-3">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- END: Display Information -->
            <!-- BEGIN: Personal Information -->
            <div class="intro-y box mt-5">
                <div class="flex items-center p-5 border-b border-gray-200 dark:border-dark-5">
                    <h2 class="font-medium text-base mr-auto">
                        Change Password
                    </h2>
                </div>
                <div class="p-5">
                    <form action="<?= base_url(); ?>/user/cpassword" method="POST">
                        <div class="grid grid-cols-12 gap-5">
                            <div class="col-span-12 xl:col-span-6">
                                <div>
                                    <label>Current Password</label>
                                    <input type="password" name="cpassword" class="input w-full border mt-2" required>
                                </div>
                            </div>
                            <div class="col-span-12 xl:col-span-6">
                                <div>
                                    <label>New Password</label>
                                    <input type="password" name="npassword" class="input w-full border mt-2" required>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end mt-4">
                            <button type="submit" class="button w-20 bg-theme-1 text-white ml-auto">Save</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- END: Personal Information -->
        </div>
    </div>
</div>
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
</script>
<?php

$errors = trim(service('Validation')->getError('gambar'));
if ($errors != "") {
    echo "
      <script>
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: '$errors',
      });
        </script>
    ";
}

?>
<?= $this->include('security'); ?>
<?= $this->endSection(); ?>
