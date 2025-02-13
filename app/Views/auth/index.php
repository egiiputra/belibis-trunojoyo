<?= $this->extend('layout/auth'); ?>
<?= $this->section('content'); ?>
<?= session()->getFlashdata('pesan'); ?>
<!-- Main -->
<main style="margin-top: -100px;">
    <!-- Home Banner -->
    <section class="theme-bg section p-0px-b">
        <div class="particles-box" id="particles-box"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-8 p-50px-tb text-center mt-4">
                    <div class="text-center">
                        <h1 class="white-color h1 m-15px-b">Welcome To <br> <span id="type-it">Belibis</span> </h1>
                    </div>
                    <p class="font-2 white-color-light font-w-400 m-20px-b">Trunojoyo Madura University: A place where knowledge grows and dreams fly, <a href="#sign-in" class="white-color-light"><b>sign in now! </b></a></p>
                </div>
                <div class="col-12 col-lg-10">
                    <img src="<?= base_url(); ?>/assets/auth/static/img/utm.png" title="" alt="">
                </div>
            </div>
        </div>
    </section>
    <!-- End Home Banner -->
    <!-- Section -->
    <section class="section">
        <div class="container">
            <div class="row justify-content-lg-between">
                <div class="col-lg-4 m-15px-tb">
                    <h3>Here is a little description about Ruang Belajar</h3>
                    <p class="font-2">
                        Ruang Belajar is an application made not only for schools, we make this application flexible so that it can be used by anyone.
                    </p>
                    <div class="p-5px-t">
                        <a class="m-btn m-btn-theme2nd-light m-btn-round" href="#learnmore">Learn More</a>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="row">
                        <div class="col-sm-6 m-15px-tb">
                            <div class="p-30px border-all-1 border-color-dark-gray box-shadow-only-hover hover-top border-radius-5">
                                <p>Flexible Class</p>
                                <img src="<?= base_url(); ?>/assets/auth/static/img/ruangbelajar1.png" alt="">
                            </div>
                        </div>

                        <div class="col-sm-6 m-15px-tb">
                            <div class="p-30px border-all-1 border-color-dark-gray box-shadow-only-hover hover-top border-radius-5">
                                <p>Stream / Room Class</p>
                                <img src="<?= base_url(); ?>/assets/auth/static/img/ruangbelajar2.png" alt="">
                            </div>
                        </div>

                        <div class="col-sm-6 m-15px-tb">
                            <div class="p-30px border-all-1 border-color-dark-gray box-shadow-only-hover hover-top border-radius-5">
                                <p>Easy to Share Material</p>
                                <img src="<?= base_url(); ?>/assets/auth/static/img/ruangbelajar3.png" alt="">
                            </div>
                        </div>

                        <div class="col-sm-6 m-15px-tb">
                            <div class="p-30px border-all-1 border-color-dark-gray box-shadow-only-hover hover-top border-radius-5">
                                <div class="icon-60 border-radius-5 green-bg white-color m-20px-b">
                                    <i class="icon-tools"></i>
                                </div>
                                <p>And Much More</p>
                                <p class="m-0px">Sign in now to see more features in Ruang Belajar</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Section -->

    <!-- Section -->
    <section class="section gray-bg" id="learnmore">
        <!-- <div class="effect gray-bg effect-skew"></div> -->
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-lg-5 m-15px-tb">
                    <h3 class="h2">Ruang Belajar will always with you</h3>
                    <p class="font-2">
                        Don't worry if you forgot your online classes, ruangbelajar will always tell you when class starts, or your teacher gives you an assignment
                    </p>
                    <ul class="list-type-03 m-20px-b">
                        <li><i class="fas fa-check"></i>Stream Notifications</li>
                        <li><i class="fas fa-check"></i>Material Notifications</li>
                        <li><i class="fas fa-check"></i>Assignment Notifications</li>
                    </ul>
                    <a class="m-btn m-btn-theme m-btn-radius" href="#sign-in">Sign in Now</a>
                </div>
                <div class="col-lg-6 m-15px-tb">
                    <img src="<?= base_url(); ?>/assets/auth/static/img/cat-v2-6.svg" title="" alt="">
                </div>
            </div>
        </div>
    </section>
    <!-- End Section -->

    <!-- Section -->
    <section class="section" id="sign-in">
        <div class="container">
            <div class="row flex-row-reverse align-items-center justify-content-between">
                <div class="col-lg-6 m-15px-tb">
                    <img src="<?= base_url(); ?>/assets/auth/static/img/cat-v2-3.svg" title="" alt="">
                </div>
                <div class="col-lg-5 m-15px-tb">
                    <h2 class="h1 m-25px-b">Sign In <u class="theme-color">Now!</u></h2>
                    <form action="<?= base_url(); ?>/auth/login" method="post">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" name="email" id="email" class="form-control" value="<?= old('email'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>
                        <div class="btn-bar p-15px-t">
                            <button type="submit" class="m-btn m-btn-radius m-btn-theme">Sign In</button>
                        </div>
                    </form>
                    <br>
                    <a href="<?= base_url(); ?>/auth/forgotpassword/" class="text-primary" style="margin-top: -42px; outline: none; border: none; background: transparent;">Forgot Password ?</a>
                    <a href="<?= base_url(); ?>/auth/signup/" class="text-primary ml-3"> Create new Account</a>
                </div>
            </div>
        </div>
    </section>
    <!-- End Section -->

    <!-- Section -->
    <section class="section gray-bg">
        <div class="container">
            <div class="row md-m-25px-b m-45px-b justify-content-center text-center">
                <div class="col-lg-5">
                    <label class="theme-bg-alt font-small font-w-500 p-20px-lr p-5px-tb theme-color border-radius-15 m-15px-b">We are help you</label>
                    <h3 class="h2 m-0px">What kind of help do you need today?</h3>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5 m-15px-tb">
                    <div class="box-shadow text-center p-40px white-bg border-radius-15 hover-top">
                        <div class="p-40px-lr">
                            <img src="<?= base_url(); ?>/assets/auth/static/img/cat-v2-1.svg" title="" alt="">
                        </div>
                        <div class="p-30px-t">
                            <h5 class="m-20px-b">Need Help support?</h5>
                            <a class="m-btn m-btn-round m-btn-yellow-light" href="mailto:admin@teknoonline.id">Send me email</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-5 m-15px-tb">
                    <div class="box-shadow text-center p-40px white-bg border-radius-15 hover-top">
                        <div class="p-40px-lr">
                            <img src="<?= base_url(); ?>/assets/auth/static/img/cat-v2-2.svg" title="" alt="">
                        </div>
                        <div class="p-30px-t">
                            <h5 class="m-20px-b">Need a Tutorial?</h5>
                            <a class="m-btn m-btn-round m-btn-green-light" href="#" target="_blank">see how to use</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Section -->
</main>
<!-- End Main -->

<!-- Footer-->
<footer class="dark-bg footer">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-4 col-lg-4 m-15px-tb">
                    <div class="m-10px-b">
                        <img src="<?= base_url(); ?>/assets/auth/static/img/logo-rb.png" width="151px" title="Ruang Belajar Elearning" alt="Ruang Belajar">
                    </div>
                    <p class="font-1">Universitas Trunojoyo Madura</p>
                </div>
                <div class="col-lg-3 col-sm-6 m-15px-tb">
                    <h6 class="white-color">
                        Contact Us
                    </h6>
                    <address>
                    <p class="m-5px-b"><a class="theme2nd-color border-bottom-1 border-color-theme2nd" href="mailto:admin@trunojoyo.ac.id">admin@trunojoyo.ac.id</a></p>
                    <p class="m-5px-b"><a class="theme2nd-color border-bottom-1 border-color-theme2nd" href="tel:0856-9865-822">0856-9865-822</a></p>
                    <p class="m-5px-b"><a class="theme2nd-color border-bottom-1 border-color-theme2nd" href="tel:0856-9865-822">0813-5757-1062</a></p>
                    </address>
                </div>
                <div class="col-lg-3 col-sm-6 m-15px-tb">
                    <h6 class="white-color">
                        Information
                    </h6>
                    <address>
                        <p class="white-color-light m-5px-b">Jl. Raya Telang, Perumahan Telang Inda, Telang, Kec. Kamal, Kabupaten Bangkalan, Jawa Timur 69162</p>
                    </address>
                    <div class="social-icon si-30 theme2nd nav">
                        <a href="#" target="_blank"><i class="fab fa-facebook"></i></a>
                        <a href="#" target="_blank"><i class="fab fa-youtube"></i></a>
                        <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom footer-border-light">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-md-right">
                </div>
                <div class="col-md-6 text-center text-md-right">
                    <p class="m-0px font-small white-color-light">© 2024 copyright Learning Universitas Trunojoyo</p>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- End Footer -->
<?= $this->endSection(); ?>
