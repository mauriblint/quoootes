<?php
    $this->disableAutoLayout();
    use Cake\Routing\Router;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quoootes - Embed Quote</title>
    <?= $this->Html->css('vendor/bootstrap.min.css') ?>
    <?= $this->Html->css('embed.css') ?>
    <link href="/favicon.ico?version=3" type="image/x-icon" rel="icon"/>
    <link href="/favicon.ico?version=3" type="image/x-icon" rel="shortcut icon"/>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.0/font/bootstrap-icons.min.css" referrerpolicy="no-referrer" media="print" onload="this.onload=null;this.removeAttribute('media');" />
    <style>
        [v-cloak] { display: none; }
    </style>
    <?php echo $this->element('meta/analitycs');  ?>
</head>
<body>
    <div class="embed-content v-<?=$widget_version?>">
        <div class="card mb-0 position-relative">
            <div class="card-top">
                <img class="card-img-top" src="<?=$design['image_url'];?>" />
            </div>
            <div class="card-body">
                <span class="quote-text"><?=$design['text'];?></span>
            </div>
            <div class="copy">
                <a href="<?= Router::url('/quote-' . $design['id'], true); ?>" class="stretched-link">Powered by QUOOOTES.COM</a>
            </div>
        </div>
    </div>
</body>
</html>