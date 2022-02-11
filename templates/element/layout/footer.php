<?php
    use Cake\Routing\Router;
?>

<!-- <div class="footer-top">

</div> -->
<footer class="">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <img src="/img/logo5.png" alt="Quoootes - Free Quote Maker and Generator" height="30">
                <p class="mt-3 text-muted"><strong>Quote maker and generator online</strong>. Create beautiful quotes for Instagram, Pinterest and more (ready to share). Motivational and Inpirational quotes.</p>
                <div class="clearfix"></div>
                <!-- <a href="<?=Router::url(['_name' => 'QuoteImages']);?>" class="btn btn-info btn-block btn-lg  btn-bold mt40">Create free Quotes now!</a> -->
            </div>
            <!-- /.col -->

            <div class="col-md-5">
                <h4>Images Quotes</h4>
                <nav class="footer-category-list">
                    <?php foreach ($category_list as $cat) {
                        $link = Router::url(['_name' => 'QuoteImagesTag']).$cat['slug'];
                        echo '<a href="'.$link.'" title="Free '.$cat['name_seo'].' maker and generator">'.$cat['name_seo'].'</a>';
                    } ?>
                </nav>
            </div>
            <!-- /.col -->

            <div class="col-md-3">
                <h4>Blog</h4>
                <ul class="list-unstyled">
                <li>
                    <a href="/blog/how-to-create-images-with-text">How to create Images with Text</a>
                </li>
                </ul>
                <h4 class="mt-5">More</h4>
                <ul class="list-unstyled">
                <li>
                    <a href="//app-sorteos.com/en">Free Instagram Comment Picker</a>
                </li>
                <li>
                    <a href="//creatufrase.net">Spanish Quote Maker</a>
                </li>
                </ul>
            </div>
            <!-- /.col -->
        </div>
    </div>
</footer>