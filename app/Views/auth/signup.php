<?= $this->extend('layout/auth'); ?>
<?= $this->section('content'); ?>
<!-- Main -->
<main>
    <section class="section effect-section">
        <div class="effect theme-bg effect-skew"></div>
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-6 col-xl-5 p-40px-tb">
                    <div class="p-30px white-bg box-shadow border-radius-10">
                        <div class="p-5px-b text-center">
                            <h3 class="font-w-600 dark-color m-3px-b">Create your account</h3>
                        </div>
                        <form action="<?= base_url(); ?>/auth/signup_" method="POST">
                            <div class="form-group">
                                <label class="form-control-label">Name</label>
                                <input type="text" name="name" class="form-control <?= ($validation->hasError('name')) ? 'is-invalid' : ''; ?>" value="<?= old('name'); ?>" placeholder="Jane Doe">
                                <div class="invalid-feedback">
                                    <?= $validation->getError('name'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Email address</label>
                                <input type="email" name="email" class="form-control <?= ($validation->hasError('email')) ? 'is-invalid' : ''; ?>" value="<?= old('email'); ?>" placeholder="name@example.com">
                                <div class="invalid-feedback">
                                    <?= $validation->getError('email'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Password</label>
                                <input type="password" name="password" class="form-control <?= ($validation->hasError('password')) ? 'is-invalid' : ''; ?>" placeholder="••••••••••••••••">
                                <div class="invalid-feedback">
                                    <?= $validation->getError('password'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Repeat Password</label>
                                <input type="password" name="password2" class="form-control <?= ($validation->hasError('password2')) ? 'is-invalid' : ''; ?>" placeholder="••••••••••••••••">
                                <div class="invalid-feedback">
                                    <?= $validation->getError('password2'); ?>
                                </div>
                            </div>
                            <div class="p-10px-t">
                                <button type="submit" class="m-btn m-btn-theme m-btn-radius w-100">Create my account</button>
                            </div>
                            <div class="m-15px-t text-center"><small>Already have an acocunt?</small> <a href="<?= base_url(); ?>/auth" class="small font-weight-bold">Login</a></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<!-- main end -->
<?= $this->endSection(); ?>