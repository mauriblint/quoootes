<?php
	use Cake\Routing\Router;
	use App\Utils\Helpers;
?>

<?php
    if ($pagination['total'] > 0 ) {
        echo $this->element('quotes/page-title-with-categories', ['page_title' => $page_title, 'categories' => $category_list, 'tag' => $tag]);
        echo $this->element('quotes/quotes-grid', ['quotes' => $recents]);
        echo $this->element('quotes/pagination', ['pagination' => $pagination]);
    } else {
        echo $this->element('quotes/empty-grid');
    }
?>
<!-- /.col -->