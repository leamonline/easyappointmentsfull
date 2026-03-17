<div id="wizard-frame-2" class="wizard-frame" style="display:none;">
    <div class="frame-container">
        <h2 class="frame-title"><?= lang('select') ?> Your Dog</h2>

        <div class="row frame-content">
            <div class="col col-md-8 offset-md-2">

                <!-- Dog selection cards (populated by JS) -->
                <div id="dog-selection-list" class="mb-4">
                    <!-- JS will populate dog cards here -->
                </div>

                <div id="no-dogs-message" class="text-center text-muted py-4" style="display:none;">
                    <i class="fas fa-paw fa-2x mb-3 d-block" style="color: var(--sd-sky-blue);"></i>
                    <p>You haven't added any dogs yet. Add your dog below to get started!</p>
                </div>

                <!-- Add New Dog Form -->
                <div id="add-dog-section">
                    <button type="button" id="btn-show-add-dog" class="btn btn-outline-primary btn-sm mb-3">
                        <i class="fas fa-plus me-2"></i>Add a New Dog
                    </button>

                    <div id="add-dog-form" class="p-3 rounded-3 mb-3" style="display:none; background: rgba(11, 173, 235, 0.06); border: 2px solid rgba(11, 173, 235, 0.2);">
                        <h6 class="mb-3" style="font-family: 'Quicksand', sans-serif; font-weight: 700;">
                            <i class="fas fa-paw me-2" style="color: var(--sd-sky-blue);"></i>New Dog Details
                        </h6>
                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="new-dog-name" class="form-label">
                                    Dog's Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="new-dog-name" class="form-control" maxlength="128"
                                       placeholder="e.g. Buddy" aria-label="Dog's name"/>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="new-dog-breed" class="form-label">Breed</label>
                                <input type="text" id="new-dog-breed" class="form-control" maxlength="128"
                                       placeholder="e.g. Cockapoo" aria-label="Dog's breed"/>
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <label for="new-dog-dob" class="form-label">
                                    Date of Birth <span class="text-danger">*</span>
                                </label>
                                <input type="date" id="new-dog-dob" class="form-control"
                                       aria-label="Dog's date of birth"/>
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <label for="new-dog-size" class="form-label">
                                    Size <span class="text-danger">*</span>
                                </label>
                                <select id="new-dog-size" class="form-select" aria-label="Dog's size">
                                    <option value="small">Small (under 10kg)</option>
                                    <option value="medium" selected>Medium (10-25kg)</option>
                                    <option value="large">Large (over 25kg)</option>
                                </select>
                            </div>
                        </div>
                        <div id="add-dog-error" class="text-danger small mb-2" style="display:none;"></div>
                        <div class="d-flex gap-2">
                            <button type="button" id="btn-save-dog" class="btn btn-dark btn-sm">
                                <i class="fas fa-check me-1"></i>Save Dog
                            </button>
                            <button type="button" id="btn-cancel-add-dog" class="btn btn-outline-secondary btn-sm">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="command-buttons">
        <button type="button" id="button-back-2" class="btn button-back btn-outline-secondary"
                data-step_index="2">
            <i class="fas fa-chevron-left me-2"></i>
            <?= lang('back') ?>
        </button>
        <button type="button" id="button-next-2" class="btn button-next btn-dark"
                data-step_index="2">
            <?= lang('next') ?>
            <i class="fas fa-chevron-right ms-2"></i>
        </button>
    </div>
</div>
