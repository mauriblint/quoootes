<?php
use Cake\Routing\Router;
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
    <div class="container">
        <a class="navbar-brand" href="/">
            <img src="/img/logo.png" alt="Quoootes - Free Quote Maker and Generator" height="26" width="161">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="bi bi-list" style="font-size: 27px;"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <?php if (!$user): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= Router::url(['_name' => 'QuoteImages']); ?>">
                        Quotes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= Router::url(['_name' => 'Templates']); ?>">
                        Templates
                    </a>
                </li>
                <li class="nav-item dropdown hidden-xs-down">
                    <a class="nav-link dropdown-toggle" href="#" id="TopicsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Topics
                    </a>
                    <div class="dropdown-menu dropdown-menu-categories" id="TopicsDropdownMenu">
                        <?php foreach ($category_list as $cat) {
                            $link = Router::url(['_name' => 'QuoteImagesTag']) . $cat['slug'];
                            echo '<a class="dropdown-item" href="' . $link . '">' . $cat['name'] . '</a>';
                        } ?>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/blog">
                        Help
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= Router::url(['_name' => 'Login']); ?>">
                        Login
                    </a>
                </li>
                <?php endif; ?>

                <?php if ($user): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= Router::url(['_name' => 'Dashboard']); ?>">
                        My Quotes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= Router::url(['_name' => 'QuoteImages']); ?>">
                        Explore
                    </a>
                </li>
                <?php endif; ?>

                <li class="nav-item active">
                    <a class="btn btn-brand gr" href="<?= Router::url(['_name' => 'Studio']); ?>">
                        <i class="bi bi-plus-lg mr-1"></i>
                        Create
                    </a>
                </li>

                <?php if ($user): ?>
                <li class="nav-item dropdown hidden-xs-down">
                    <a class="nav-link dropdown-toggle" href="#" id="TopicsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="user-profile-pic" src="<?=$user['picture']?>" alt="">
                    </a>
                    <div class="dropdown-menu" id="TopicsDropdownMenu">
                        <a href="<?= Router::url(['_name' => 'Dashboard']); ?>" class="dropdown-item">My Quotes</a>
                        <hr class="dropdown-divider">
                        <a href="<?= Router::url(['_name' => 'Logout']); ?>" class="dropdown-item">Logout</a>
                    </div>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>