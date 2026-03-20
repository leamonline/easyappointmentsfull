<?php
/**
 * Local variables.
 *
 * @var string $subject
 * @var string $message
 * @var array $settings
 */
?>
<html lang="en">
<head>
    <title><?= e($subject) ?> | Smarter Dog</title>
</head>
<body style="font: 13px arial, helvetica, tahoma;">

<div class="email-container" style="width: 650px; border: 1px solid #eee; margin: 30px auto;">
    <div id="header"
         style="background-color: <?= !empty($settings['company_color']) && preg_match('/^#[0-9a-fA-F]{3,8}$/', $settings['company_color']) ? $settings['company_color'] : '#429a82' ?>; height: 45px; padding: 10px 15px;">
        <strong id="logo" style="color: white; font-size: 20px; margin-top: 10px; display: inline-block">
            <?= e($settings['company_name']) ?>
        </strong>
    </div>

    <div id="content" style="padding: 10px 15px; min-height: 400px">
        <h2>
            <?= e($subject) ?>
        </h2>
        <p>
            <?= e($message) ?>
        </p>
    </div>

    <div id="footer" style="padding: 10px; text-align: center; margin-top: 10px;
                border-top: 1px solid #EEE; background: #FAFAFA;">
        Powered by
        <a href="https://easyappointments.org" style="text-decoration: none;">
            Smarter Dog
        </a>
        |
        <a href="<?= e($settings['company_link']) ?>" style="text-decoration: none;">
            <?= e($settings['company_name']) ?>
        </a>
    </div>
</div>

</body>
</html>
