
<?php foreach ($pengajar as $p): ?>
<div class="flex flex-row items-start p-5 border-b border-gray-200 dark:border-dark-5">
    <div class="dropdown-toggle w-8 h-8 rounded-full overflow-hidden bg-gray-100 rounded-full shadow-lg image-fit zoom-in scale-110 mr-4">
        <img alt="img" src="<?= base_url(); ?>/vendor/dist/user/<?= $p->gambar_user; ?>">
    </div>
    <span class="font-medium text-base mr-4">
        <?= $p->nama_user ?>
    </span>
    <span class="font-regular text-base mr-auto">
        <?= $p->email_user ?>
    </span>
</div>
<?php endforeach ?>
