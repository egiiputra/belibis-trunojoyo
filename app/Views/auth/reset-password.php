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
                            <p class="login-box-msg">Change Password for<br><?= $email; ?></p>
                        </div>
                        <form action="<?= base_url(); ?>/auth/changepassword" method="post">
                            <input type="hidden" name="email" value="<?= $email ?>">
                            <input type="hidden" name="token" value="<?= $token ?>">
                            <div class="form-group">
                                <label class="form-control-label">Password</label>
                                <input type="password" name="password" class="form-control <?= ($validation->hasError('password')) ? 'is-invalid' : ''; ?>">
                                <div class="invalid-feedback">
                                    <?= $validation->getError('password'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Repeat Password</label>
                                <input type="password" name="password2" class="form-control <?= ($validation->hasError('password2')) ? 'is-invalid' : ''; ?>">
                                <div class="invalid-feedback">
                                    <?= $validation->getError('password2'); ?>
                                </div>
                            </div>
                            <div class="p-10px-t">
                                <button type="submit" class="m-btn m-btn-theme m-btn-radius w-100">Change password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<!-- main end -->
<?= $this->endSection(); ?>