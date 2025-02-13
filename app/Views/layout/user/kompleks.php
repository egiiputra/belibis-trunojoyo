<!DOCTYPE html>
<html lang="en">
<!-- BEGIN: Head -->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BELIBIS</title>
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url(); ?>/vendor/dist/images/rb.png">
    <!-- BEGIN: CSS Assets-->
    <link rel="stylesheet" href="<?= base_url(); ?>/vendor/dist/css/app.css" />
    <!-- END: CSS Assets-->
    <!-- BEGIN: JS Assets-->
    <!-- Sweetalert -->
    <script src="<?= base_url(); ?>/assets/swal/sweetalert2.all.js"></script>
    <script src="<?= base_url(); ?>/vendor/dist/js/jquery-3.3.1.min.js"></script>
    <script src="<?= base_url(); ?>/assets/ckeditor/ckeditor.js" type="text/javascript"></script>
    <script src="<?= base_url(); ?>/assets/clipboard/clipboard.min.js" type="text/javascript"></script>
    <!-- CSS -->
    <link href="<?= base_url(); ?>/assets/smart-wizard/smart_wizard_all.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- JavaScript -->
    <script src="<?= base_url(); ?>/assets/smart-wizard/jquery.smartWizard.min.js" type="text/javascript"></script>

    <!-- include summernote css/js -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>/assets/summernote/summernote-image-list.min.css" rel="stylesheet">

</head>
<!-- END: Head -->

<body class="app">
    <!-- BEGIN: Mobile Menu -->
    <div class="mobile-menu md:hidden">
        <div class="mobile-menu-bar">
            <a href="" class="flex mr-auto">
                <img alt="img" style="width: 40px;" src="<?= base_url(); ?>/vendor/dist/images/rb.png">
            </a>
            <a href="javascript:;" id="mobile-menu-toggler"> <i data-feather="bar-chart-2" class="w-8 h-8 text-white transform -rotate-90"></i> </a>
        </div>
        <ul class="border-t border-theme-24 py-5 hidden">
            <li>
                <a href="<?= base_url(); ?>/user" class="menu <?= $dashboard; ?>">
                    <div class="menu__icon"> <i data-feather="home"></i> </div>
                    <div class="menu__title"> Dashboard </div>
                </a>
            </li>
            <li>
                <a href="<?= base_url(); ?>/user/profile" class="menu <?= $profile; ?>">
                    <div class="menu__icon"> <i data-feather="user"></i> </div>
                    <div class="menu__title"> Profile </div>
                </a>
            </li>
            <li>
                <a href="javascript:;" class="menu <?= $clases; ?>">
                    <div class="menu__icon"> <i data-feather="monitor"></i></div>
                    <div class="menu__title"> Classes <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down menu__sub-icon">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg> </div>
                </a>
                <ul class="" style="display: none;">
                    <?php foreach ($myclass as $class) : ?>
                        <li>
                            <a href="<?= base_url(); ?>/user/classes/<?= encrypt_url($class->kode_kelas); ?>" class="menu">
                                <div class="menu__icon"> <i data-feather="activity"></i></div>
                                <div class="menu__title"> <?= $class->mapel ?> <?= $class->nama_kelas; ?></div>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    <?php foreach ($classes as $class) : ?>
                        <li>
                            <a href="<?= base_url(); ?>/user/classes/<?= encrypt_url($class->kelas_kode); ?>" class="menu">
                                <div class="menu__icon"> <i data-feather="activity"></i></div>
                                <div class="menu__title"> <?= $class->mapel ?> <?= $class->nama_kelas; ?></div>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>
            <li>
                <a href="<?= base_url(); ?>/user/materials/<?= encrypt_url($data_kode_kelas); ?>" class="menu <?= $dashboard; ?>">
                    <div class="menu__icon"> <i data-feather="file-text"></i> </div>
                    <div class="menu__title"> Materials </div>
                </a>
            </li>
            <li>
                <a href="<?= base_url(); ?>/user/assignment/<?= encrypt_url($data_kode_kelas); ?>" class="menu <?= $assignments; ?>">
                    <div class="menu__icon"> <i data-feather="wifi"></i> </div>
                    <div class="menu__title"> Assignment </div>
                </a>
            </li>
            <li>
                <a href="<?= base_url(); ?>/user/ujian/<?= encrypt_url($data_kode_kelas); ?>" class="menu <?= $exam; ?>">
                    <div class="menu__icon"> <i data-feather="clock"></i> </div>
                    <div class="menu__title"> Exam </div>
                </a>
            </li>
            <li>
                <a href="<?= base_url(); ?>/user/anggota/<?= encrypt_url($data_kode_kelas); ?>" class="menu">
                    <div class="menu__icon"> <i data-feather="users"></i> </div>
                    <div class="menu__title"> Anggota </div>
                </a>
            </li>
        </ul>
    </div>
    <!-- END: Mobile Menu -->
    <!-- BEGIN: Top Bar -->
    <div class="border-b border-theme-24 -mt-10 md:-mt-5 -mx-3 sm:-mx-8 px-3 sm:px-8 pt-3 md:pt-0 mb-10">
        <div class="top-bar-boxed flex items-center">
            <!-- BEGIN: Logo -->
            <a href="" class="-intro-x hidden md:flex">
                <img alt="Ruang Belajar" style="width: 50px;" src="<?= base_url(); ?>/vendor/dist/images/rb.png">
                <span class="text-white text-lg ml-3 mt-2"> Ruang<span class="font-medium">Belajar</span> </span>
            </a>
            <!-- END: Logo -->
            <!-- BEGIN: Breadcrumb -->
            <div class="-intro-x breadcrumb breadcrumb--light mr-auto"> <a href="" class=""><?= $breadcrumb1; ?></a> <i data-feather="chevron-right" class="breadcrumb__icon"></i> <a href="" class="breadcrumb--active"><?= $breadcrumb2; ?></a> </div>
            <!-- END: Breadcrumb -->
            <!-- BEGIN: Account Menu -->
            <div class="intro-x dropdown w-8 h-8 relative">
                <div class="dropdown-toggle w-8 h-8 rounded-full overflow-hidden bg-gray-100 rounded-full shadow-lg image-fit zoom-in scale-110">
                    <img alt="img" src="<?= base_url(); ?>/vendor/dist/user/<?= $user->gambar; ?>">
                </div>
                <div class="dropdown-box mt-10 absolute w-56 top-0 right-0 z-20">
                    <div class="dropdown-box__content box bg-theme-38 dark:bg-dark-6 text-white">
                        <div class="p-4 border-b border-theme-40 dark:border-dark-3">
                            <div class="font-medium"><?= $user->nama; ?></div>
                            <div class="text-xs text-theme-41 dark:text-gray-600"><?= $user->email; ?></div>
                        </div>
                        <div class="p-2">
                            <a href="<?= base_url(); ?>/user/profile" class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-1 dark:hover:bg-dark-3 rounded-md">
                                <i data-feather="user" class="w-4 h-4 mr-2"></i> Profile </a>
                            <a href="<?= base_url(); ?>/user/profile" class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-1 dark:hover:bg-dark-3 rounded-md">
                                <i data-feather="lock" class="w-4 h-4 mr-2"></i> Reset Password </a>
                        </div>
                        <div class="p-2 border-t border-theme-40 dark:border-dark-3">
                            <a href="<?= base_url(); ?>/auth/logout" class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-1 dark:hover:bg-dark-3 rounded-md">
                                <i data-feather="toggle-right" class="w-4 h-4 mr-2"></i> Logout </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: Account Menu -->
        </div>
    </div>
    <!-- END: Top Bar -->
    <!-- BEGIN: Top Menu -->
    <nav class="top-nav">
        <ul>
            <li>
                <a href="<?= base_url(); ?>/user" class="top-menu <?= $topdashboard; ?>">
                    <div class="top-menu__icon"> <i data-feather="home"></i> </div>
                    <div class="top-menu__title"> Dashboard </div>
                </a>
            </li>
            <li>
                <a href="<?= base_url(); ?>/user/profile" class="top-menu <?= $topprofile; ?>">
                    <div class="top-menu__icon"> <i data-feather="user"></i> </div>
                    <div class="top-menu__title"> Profile </div>
                </a>
            </li>
            <li>
                <a href="javascript:;" class="top-menu <?= $topclases; ?>">
                    <div class="top-menu__icon"><i data-feather="monitor"></i></div>
                    <div class="top-menu__title"> Classes
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down top-menu__sub-icon">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </div>
                </a>
                <ul class="">
                    <?php foreach ($myclass as $class) : ?>
                        <li>
                            <a href="<?= base_url(); ?>/user/classes/<?= encrypt_url($class->kode_kelas); ?>" class="top-menu">
                                <div class="top-menu__icon"> <i data-feather="activity"></i> </div>
                                <div class="top-menu__title"> <?= $class->nama_kelas; ?> <br><?= $class->mapel; ?></div>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    <?php foreach ($classes as $class) : ?>
                        <li>
                            <a href="<?= base_url(); ?>/user/classes/<?= encrypt_url($class->kelas_kode); ?>" class="top-menu">
                                <div class="top-menu__icon"> <i data-feather="activity"></i> </div>
                                <div class="top-menu__title"> <?= $class->nama_kelas; ?> <br><?= $class->mapel; ?></div>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>
            <li>
                <a href="<?= base_url(); ?>/user/materials/<?= encrypt_url($data_kode_kelas); ?>" class="top-menu <?= $topmaterials; ?>">
                    <div class="top-menu__icon"><i data-feather="file-text"></i></div>
                    <div class="top-menu__title"> Materials</div>
                </a>
            </li>
            <li>
                <a href="<?= base_url(); ?>/user/assignment/<?= encrypt_url($data_kode_kelas); ?>" class="top-menu <?= $topassignment; ?>">
                    <div class="top-menu__icon"><i data-feather="wifi"></i></div>
                    <div class="top-menu__title"> Assignment</div>
                </a>
            </li>
            <li>
                <a href="<?= base_url(); ?>/user/ujian/<?= encrypt_url($data_kode_kelas); ?>" class="top-menu <?= $topexam; ?>">
                    <div class="top-menu__icon"><i data-feather="clock"></i></div>
                    <div class="top-menu__title"> Exam</div>
                </a>
            </li>
            <li>
                <a href="<?= base_url(); ?>/user/anggota/<?= encrypt_url($data_kode_kelas); ?>" class="top-menu <?= $topanggota; ?>">
                    <div class="top-menu__icon"> <i data-feather="users"></i> </div>
                    <div class="top-menu__title"> Anggota</div>
                </a>
            </li>
        </ul>
    </nav>
    <!-- END: Top Menu -->

    <?= $this->renderSection('content'); ?>
    <script src="<?= base_url(); ?>/vendor/dist/js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script src="<?= base_url(); ?>/assets/summernote/summernote-image-list.min.js"></script>
    <?php // $this->include('security'); 
    ?>

</body>

</html>
