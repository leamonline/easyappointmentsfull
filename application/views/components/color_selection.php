<?php
/**
 * @var string $attributes
 */
?>

<?php section('styles'); ?>

<link rel="stylesheet" type="text/css" href="<?= asset_url('assets/css/components/color_selection.css') ?>">

<?php end_section('styles'); ?>

<label class="form-label"><?= lang('color') ?></label>

<div <?= $attributes ?? '' ?> class="color-selection d-flex justify-content-between mb-4">
    <button type="button" class="color-selection-option selected" data-value="#7cbae8" aria-label="<?= lang('color') ?>: Blue">
        <i class="fas fa-check"></i>
    </button>

    <button type="button" class="color-selection-option" data-value="#acbefb" aria-label="<?= lang('color') ?>: Lavender">
        <i class="fas fa-check"></i>
    </button>

    <button type="button" class="color-selection-option" data-value="#82e4ec" aria-label="<?= lang('color') ?>: Cyan">
        <i class="fas fa-check"></i>
    </button>

    <button type="button" class="color-selection-option" data-value="#7cebc1" aria-label="<?= lang('color') ?>: Mint">
        <i class="fas fa-check"></i>
    </button>

    <button type="button" class="color-selection-option" data-value="#abe9a4" aria-label="<?= lang('color') ?>: Green">
        <i class="fas fa-check"></i>
    </button>

    <button type="button" class="color-selection-option" data-value="#ebe07c" aria-label="<?= lang('color') ?>: Yellow">
        <i class="fas fa-check"></i>
    </button>

    <button type="button" class="color-selection-option" data-value="#f3bc7d" aria-label="<?= lang('color') ?>: Orange">
        <i class="fas fa-check"></i>
    </button>

    <button type="button" class="color-selection-option" data-value="#f3aea6" aria-label="<?= lang('color') ?>: Peach">
        <i class="fas fa-check"></i>
    </button>

    <button type="button" class="color-selection-option" data-value="#eb8687" aria-label="<?= lang('color') ?>: Red">
        <i class="fas fa-check"></i>
    </button>

    <button type="button" class="color-selection-option" data-value="#dfaffe" aria-label="<?= lang('color') ?>: Purple">
        <i class="fas fa-check"></i>
    </button>

    <button type="button" class="color-selection-option" data-value="#e3e3e3" aria-label="<?= lang('color') ?>: Grey">
        <i class="fas fa-check"></i>
    </button>
</div>

<?php section('scripts'); ?>

<script src="<?= asset_url('assets/js/components/color_selection.js') ?>"></script>

<?php end_section('scripts'); ?>
