<!doctype html>
<html lang="en">

<head>
    <!-- metas -->
    <meta charset="utf-8">
    <meta name="author" content="Abduloh" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="Aplikasi E Learning" />
    <meta name="description" content="BELIBIS" />
    <!-- title -->
    <title>BELIBIS</title>
    <!-- Favicon -->
    <!-- <link rel="shortcut icon" type="image/x-icon" href="favicon.ico"> -->
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url(); ?>/vendor/dist/images/rb.png">
    <!-- plugin CSS -->
    <link href="<?= base_url(); ?>/assets/auth/static/plugin/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>/assets/auth/static/plugin/font-awesome/css/all.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>/assets/auth/static/plugin/themify-icons/themify-icons.css" rel="stylesheet">
    <!-- theme css -->
    <link href="<?= base_url(); ?>/assets/auth/static/style/master.css" rel="stylesheet">

    <!-- Sweetalert -->
    <script src="<?= base_url(); ?>/assets/swal/sweetalert2.all.js"></script>
</head>
<!-- Body Start -->

<body data-spy="scroll" data-target="#navbar-collapse-toggle" data-offset="98">
    <!-- Preload -->
    <div id="loading">
        <div class="load-circle"><span class="one"></span></div>
    </div>

    <?= $this->renderSection('content'); ?>

    <!-- jquery -->
    <script src="<?= base_url(); ?>/assets/auth/static/js/jquery-3.2.1.min.js"></script>
    <script src="<?= base_url(); ?>/assets/auth/static/js/jquery-migrate-3.0.0.min.js"></script>
    <!-- end jquery -->
    <!-- appear -->
    <script src="<?= base_url(); ?>/assets/auth/static/plugin/appear/jquery.appear.js"></script>
    <!-- end appear -->
    <!--bootstrap-->
    <script src="<?= base_url(); ?>/assets/auth/static/plugin/bootstrap/js/popper.min.js"></script>
    <script src="<?= base_url(); ?>/assets/auth/static/plugin/bootstrap/js/bootstrap.js"></script>
    <!--end bootstrap-->
    <!-- End -->
    <!-- custom js -->
    <script src="<?= base_url(); ?>/assets/auth/static/js/custom.js"></script>
    <script src="<?= base_url(); ?>/assets/typeit/typeit.min.js"></script>
    <!-- Particles JS -->
    <script src="<?= base_url(); ?>/assets/auth/static/plugin/particles/particles.min.js"></script>
    <script src="<?= base_url(); ?>/assets/auth/static/plugin/particles/particles-app.js"></script>
    <!-- My-script -->
    <script src="<?= base_url(); ?>/assets/my-assets/js/myscript.js"></script>
    <script>
        $(document).ready(function() {
            // Particle JS
            if ($("#particles-box").exists()) {
                loadScript(plugin_track + 'particles/particles.min.js', function() {});
                loadScript(plugin_track + 'particles/particles-app.js', function() {});
            }
            // End Particle Js

        });
        // Typeit JS
        new TypeIt('#type-it', {
            speed: 200,
            loop: true,
            strings: [
                'Ruang Belajar'
            ],
            breakLines: false
        }).go();
        // End Typeit JS
    </script>
    <!-- end -->
</body>
<!-- end body -->

</html>