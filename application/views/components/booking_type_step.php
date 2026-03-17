<?php
/**
 * Local variables.
 *
 * @var array $available_services
 */
?>

<div id="wizard-frame-3" class="wizard-frame" style="display:none;">
    <div class="frame-container">
        <h2 class="frame-title mt-md-5"><?= lang('service_and_provider') ?></h2>

        <!-- Hidden select for JS compatibility -->
        <select id="select-service" class="form-select" style="display:none;">
            <option value=""><?= lang('please_select') ?></option>
            <?php foreach ($available_services as $service): ?>
                <option value="<?= $service['id'] ?>"><?= e($service['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <div class="mb-3" hidden>
            <select id="select-provider" class="form-select">
                <option value=""><?= lang('please_select') ?></option>
            </select>
        </div>

        <?php slot('after_select_provider'); ?>

        <!-- JS renders service cards here based on selected dog -->
        <div class="row frame-content service-cards-container"></div>

        <?php slot('after_service_description'); ?>

    </div>

    <div class="command-buttons">
        <button type="button" id="button-back-3" class="btn button-back btn-outline-secondary"
                data-step_index="3">
            <i class="fas fa-chevron-left me-2"></i>
            <?= lang('back') ?>
        </button>
        <button type="button" id="button-next-3" class="btn button-next btn-dark"
                data-step_index="3">
            <?= lang('next') ?>
            <i class="fas fa-chevron-right ms-2"></i>
        </button>
    </div>
</div>
