<?= $this->extend('layout/user/kompleks'); ?>
<?= $this->section('content'); ?>
<?= session()->getFlashdata('pesan'); ?>

<div class="content">
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Add New Assignment
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="col-span-12 lg:col-span-1"></div>
        <div class="intro-y col-span-12 lg:col-span-10">
            <!-- BEGIN: Form Validation -->
            <div class="intro-y box">
                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200 dark:border-dark-5">
                    <h2 class="font-medium text-base mr-auto">
                        Form Assignment
                    </h2>
                </div>
                <div class="p-5" id="basic-datepicker">
                    <div class="preview">
                        <form action="<?= base_url() . '/su/addassignment_/' . encrypt_url($kelas->kode_kelas); ?>" method="POST" enctype="multipart/form-data" class="validate-form">
                            <div>
                                <label class="flex flex-col sm:flex-row"> Title <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-gray-600">Required</span> </label>
                                <input type="text" name="title" class="input w-full border mt-2" required>
                            </div>
                            <div class="mt-3">
                                <label class="flex flex-col sm:flex-row"> Description</label>
                                <textarea class="input w-full border mt-2" name="description" wrap="hard" placeholder="Description ( Optional )"></textarea>
                            </div>
                            <div class="mt-3">
                                <input type="hidden" id="random_code" name="assignment_code">
                                <input type="hidden" id="kelas_kode" name="kelas_kode" value="<?= $kelas->kode_kelas; ?>">
                                <input type="file" name="assignment_file[]" style="width: 265px;" class="input border mt-2 flex" multiple>
                            </div>
                            <div class="mt-3">
                                <label class="flex flex-col sm:flex-row"> Due Date <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-gray-600">Required</span> </label>
                                <div class="grid grid-cols-12 gap-2">
                                    <input type="date" name="date" class="input w-full border mt-2 col-span-4" required>
                                    <input type="time" name="time" class="input w-full border mt-2 col-span-4" required>
                                </div>
                            </div>
                            <a href="<?= base_url() . '/user/assignment/' . encrypt_url($kelas->kode_kelas); ?>" class="button bg-theme-14 text-theme-10 mt-5">
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