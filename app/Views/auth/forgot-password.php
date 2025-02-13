<?= $this->extend('layout/auth'); ?>
<?= $this->section('content'); ?>
<?= session()->getFlashdata('pesan'); ?>
<!-- Main -->
<main>
    <section>
        <div class="container">
            <div class="row align-items-center justify-content-center min-vh-100">
                <div class="col-md-6 col-xl-5 p-40px-tb">
                    <div class="p-40px white-bg box-shadow border-radius-10">
                        <div class="p-20px-b text-center">
                            <h3 class="font-w-600 dark-color m-10px-b">Reset password</h3>
                            <p>Enter your email to reset your password.</p>
                        </div>
                        <form action="<?= base_url(); ?>/auth/forgotpassword_" method="post">
                            <div class="form-group">
                                <label class="form-control-label">Email address</label>
                                <input type="text" class="form-control" name="email" placeholder="name@example.com">
                            </div>
                            <div class="p-10px-t">
                                <button type="submit" class="m-btn m-btn-theme m-btn-radius w-100">Request new password</button>
                            </div>
                            <div class="m-20px-t text-center"><small>Back to</small> <a href="<?= base_url(); ?>/auth" class="small font-weight-bold">Dashboard</a></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<!-- main end -->
<?= $this->endSection(); ?>