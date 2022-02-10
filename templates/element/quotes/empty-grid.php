<?php
    use Cake\Routing\Router;
?>
<div class="empty-grid p50">
	<h3>Sorry, we couldn't find any image for this Category.</h3>
    <h5 class="mt20 mb40">Be the first in create one!</h5>
	<a class="btn btn-success btn-lg mt40" href="<?=Router::url(['_name' => 'Editor']);?>">
        <i class="fa fa-plus"></i>Create an Image now!
    </a>
</div>
