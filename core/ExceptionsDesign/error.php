<head>
    <title>Error</title>

</head>
<style>
    <?= include __DIR__. '/css/error.css'?>
</style>
<div class="row">
        <div class="error-position">
            <p><?= $errorCode ?></p>
            <p><?= $message ?></p>
        </div>
        <img src="assets/img/errorImage.jpg">
</div>