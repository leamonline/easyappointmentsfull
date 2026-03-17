<div id="wizard-frame-1" class="wizard-frame" style="visibility: hidden;">
    <div class="frame-container">
        <h2 class="frame-title mt-md-5"><?= lang('login') ?></h2>

        <div class="row frame-content">
            <div class="col col-md-6 offset-md-3">

                <!-- Login / Register Tabs -->
                <ul class="nav nav-tabs mb-4" id="auth-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="login-tab" data-bs-toggle="tab"
                                data-bs-target="#login-pane" type="button" role="tab">
                            <?= lang('login') ?>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="register-tab" data-bs-toggle="tab"
                                data-bs-target="#register-pane" type="button" role="tab">
                            Register
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="auth-tab-content">
                    <!-- Login Pane -->
                    <div class="tab-pane fade show active" id="login-pane" role="tabpanel">
                        <div class="mb-3">
                            <label for="login-email" class="form-label">
                                <?= lang('email') ?> <span class="text-danger">*</span>
                            </label>
                            <input type="email" id="login-email" class="form-control" maxlength="120"
                                   autocomplete="email" placeholder="your@email.com" aria-label="<?= lang('email') ?>"/>
                        </div>
                        <div class="mb-3">
                            <label for="login-password" class="form-label">
                                Password <span class="text-danger">*</span>
                            </label>
                            <input type="password" id="login-password" class="form-control" maxlength="120"
                                   autocomplete="current-password" aria-label="Password"/>
                        </div>
                        <div id="login-error" class="text-danger small mb-3" style="display:none;"></div>
                        <button type="button" id="btn-login" class="btn btn-dark w-100">
                            <i class="fas fa-sign-in-alt me-2"></i><?= lang('login') ?>
                        </button>
                    </div>

                    <!-- Register Pane -->
                    <div class="tab-pane fade" id="register-pane" role="tabpanel">
                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="reg-first-name" class="form-label">
                                    <?= lang('first_name') ?> <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="reg-first-name" class="form-control" maxlength="100"
                                       autocomplete="given-name" aria-label="<?= lang('first_name') ?>"/>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="reg-last-name" class="form-label">
                                    <?= lang('last_name') ?> <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="reg-last-name" class="form-control" maxlength="120"
                                       autocomplete="family-name" aria-label="<?= lang('last_name') ?>"/>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="reg-email" class="form-label">
                                <?= lang('email') ?> <span class="text-danger">*</span>
                            </label>
                            <input type="email" id="reg-email" class="form-control" maxlength="120"
                                   autocomplete="email" aria-label="<?= lang('email') ?>"/>
                        </div>
                        <div class="mb-3">
                            <label for="reg-phone" class="form-label">
                                <?= lang('phone_number') ?>
                            </label>
                            <input type="tel" id="reg-phone" class="form-control" maxlength="60"
                                   autocomplete="tel" aria-label="<?= lang('phone_number') ?>"/>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="reg-password" class="form-label">
                                    Password <span class="text-danger">*</span>
                                </label>
                                <input type="password" id="reg-password" class="form-control" maxlength="120"
                                       autocomplete="new-password" aria-label="Password"/>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="reg-password-confirm" class="form-label">
                                    Confirm Password <span class="text-danger">*</span>
                                </label>
                                <input type="password" id="reg-password-confirm" class="form-control" maxlength="120"
                                       autocomplete="new-password" aria-label="Confirm password"/>
                            </div>
                        </div>
                        <div id="register-error" class="text-danger small mb-3" style="display:none;"></div>
                        <button type="button" id="btn-register" class="btn btn-dark w-100">
                            <i class="fas fa-user-plus me-2"></i>Register
                        </button>
                    </div>
                </div>

                <!-- Logged-in state (shown after successful auth) -->
                <div id="logged-in-state" class="text-center py-4" style="display:none;">
                    <div class="mb-3">
                        <i class="fas fa-check-circle fa-3x" style="color: var(--sd-sky-blue);"></i>
                    </div>
                    <h5 id="logged-in-name" class="mb-1"></h5>
                    <p id="logged-in-email" class="text-muted small mb-3"></p>
                    <button type="button" id="btn-logout" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-sign-out-alt me-1"></i>Not you? Log out
                    </button>
                </div>

            </div>
        </div>
    </div>

    <div class="command-buttons">
        <span>&nbsp;</span>
        <button type="button" id="button-next-1" class="btn button-next btn-dark"
                data-step_index="1" disabled>
            <?= lang('next') ?>
            <i class="fas fa-chevron-right ms-2"></i>
        </button>
    </div>
</div>
