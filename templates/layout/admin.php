<?php
    $title = isset($title) ? $title : __('Free Meme Generator');
?>
<!DOCTYPE html>
<html>

<head>
    <?= $this->Html->charset() ?>
    <title>Quoootes - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex" />
    <link rel="stylesheet" href="https://fonts.cdnfonts.com/css/satoshi" referrerpolicy="no-referrer" media="print" onload="this.onload=null;this.removeAttribute('media');">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.0/font/bootstrap-icons.min.css" referrerpolicy="no-referrer" media="print" onload="this.onload=null;this.removeAttribute('media');" />
    <?= $this->Html->css('/bootstrap4/css/bootstrap.min.css') ?>
    <?= $this->Html->css('styles.css') ?>
    <?= $this->Html->css('helpers.css') ?>
</head>

<body class="admin">
    <div id="admin" v-cloak>
        <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
            <a class="navbar-brand" href="/">
                <img src="/img/logo.png?new3" width="160" alt="Crea Tu Frase" title="Crea Tu Frase - Crea frases para compartir">
            </a>
            <div class="navbar-collapse">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" :class="{active: page === 'users'}" href="#users" @click="navigate('users')">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="{active: page === 'designs'}" href="#designs" @click="navigate('designs')">Designs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="{active: page === 'templates'}" href="#templates" @click="navigate('templates')">Templates</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="{active: page === 'backgrounds'}" href="#backgrounds" @click="navigate('backgrounds')">Backgrounds</a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="container-fluaid my-6" id="mainContainer">
            <?= $this->fetch('content'); ?>
        </div>

    </div>
    <script>
        var __NEXT_DATA__ = {
            tags: <?= json_encode($category_list) ?>,
        }
    </script>
    <?= $this->Html->script([
        'vendor/vue.js',
        'vendor/jquery.min.js',
        'vendor/popper.min.js',
        '/bootstrap4/js/bootstrap.min.js',
        'https://unpkg.com/vue-toasted',
        'admin.js',
    ], ['defer' => true]) ?>

<style>
    .table td { vertical-align: middle; }
    .navbar-light .navbar-nav .active>.nav-link, .navbar-light .navbar-nav .nav-link.active, .navbar-light .navbar-nav .nav-link.show, .navbar-light .navbar-nav .show>.nav-link {
        color: var(--primary-color);
    }
    .card-overlay .bi {
        font-size: 46px;
        margin: 10px;
        cursor: pointer;
    }
    .card-overlay .bi:hover {
        opacity: .8;
    }
    .card-overlay {
        align-items: center;
        justify-content: center;
    }
</style>
<style>
th, td {
    text-align: center;
    vertical-align: middle;
}
.badge {
    padding: 5px 10px;
}
.badge.clickeable {
    cursor: pointer;
    opacity: .8;
}
</style>
</body>

</html>