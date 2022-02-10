<?php
	use Cake\Routing\Router;
	use App\Utils\PaginationLinks;
	use App\Utils\Helpers;
?>

<?php foreach ($quotes as $quote) echo $this->element('ui/quote', [ 'quote' => $quote]); ?>