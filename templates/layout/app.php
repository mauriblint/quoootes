<?php
    use App\Utility\Html;
    use App\Utility\Metadata;

    $frase = (isset($frase)) ? $frase : null;
    $Meta_Title = ( isset($title) ) ? $title : false;
    $Meta_Descr = ( isset($description) ) ? $description : false;

    $images = Html::Load_EDITOR_Images();
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=Metadata::getTitle($Meta_Title);?></title>
    <meta name="description" content="<?=Metadata::getDescription($Meta_Descr);?>">
    <meta name="keywords" content="<?=Metadata::getKeywords();?>">
    <?= $this->Html->meta('icon') ?>
    <?= $this->Html->css('/bootstrap4/css/bootstrap.min.css') ?>
    <?= $this->Html->css('styles.css') ?>
    <?= $this->Html->css('app.css') ?>
    <?= $this->Html->css('helpers.css') ?>
    <?= $this->Html->css('//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css') ?>
    <link rel="stylesheet" href="https://unpkg.com/vue-swatches/dist/vue-swatches.min.css">
    <link rel="stylesheet" href="/fonts/kredit/styles.css">
    <link rel="stylesheet" href="/fonts/nexarust/stylesheet.css">
    <link rel="stylesheet" href="/fonts/1942/stylesheet.css">
    <link rel="stylesheet" href="/fonts/team_spirit/stylesheet.css">


    <?= $this->Html->css('/generator/layouts.css') ?>
    <?php echo $this->Html->css(Html::Load_VUE_Components_CSS()); ?>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-125035147-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-125035147-1');
    </script>
</head>
<body class="app">

    <?= $this->fetch('content');?>
    <?php
        $VueComponents2 = Html::Load_VUE_Components_Templates();

        foreach ($VueComponents2 as $Component) {
            echo $this->element('../../webroot/Components/' . $Component);
        }
    ?>
    <script>
        var App = {};
        App.tags = <?php echo json_encode($category_list); ?>;
        App.frase = <?php echo json_encode($frase); ?>;
        App.images = <?php echo json_encode($images); ?>;
        App.User = null;
    </script>

    <script src="https://unpkg.com/vue-swatches@1.0.1/dist/vue-swatches.min.js"></script>

    <?php
        echo $this->Html->script(Html::jsFiles('data'));
        echo $this->Html->script(Html::jsFiles('vendor'));
        echo $this->Html->script(Html::jsFiles('vendor-plugins'));
        echo $this->Html->script(Html::jsFiles('lib'));
        echo $this->Html->script(Html::Load_VUE_Components_JS());
        echo $this->Html->script('/bootstrap4/js/bootstrap.min.js');
        echo $this->Html->script('layouts.js');
        echo $this->Html->script('generator.js');
        echo $this->Html->script('app.js');
    ?>

    <!-- <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-59209fdf8bb355e5"></script> -->
</body>
</html>
