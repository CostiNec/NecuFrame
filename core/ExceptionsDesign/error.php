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
        <img src="<?= (is_file('assets/img/errorImage.png')) ? 'assets/img/errorImage.png' : 'https://necuframe.com/assets/img/errorImage.png' ?>">
</div>