<?php
    use App\Utils\PaginationLinks;
    use Cake\Routing\Router;

    $next = ( $pagination['page'] < $pagination['pages'] ) ? '?page='.($pagination['page']+1) : '#';
    $prev = ( $pagination['page'] > 1 ) ? '?page='.($pagination['page']-1) : '#';

    $baseUrl = preg_replace('/\?.*/', '', Router::url(null, true));
?>

<?php if ($pagination['total'] > 0 ): ?>
<div class="d-flex align-items-center justify-content-center my-5">
	<ul class="pagination">
		<li class="page-item <?=($prev=='#') ? 'disabled' : '';  ?>">
			<a class="page-link" href="<?=$baseUrl . $prev?>" tabindex="-1"><i class="bi bi-chevron-left" aria-hidden="true"></i></a>
		</li>
		<?php
		echo PaginationLinks::create($pagination['page'],$pagination['pages'],1,
		    '<li class="page-item"><a class="page-link" href="'. $baseUrl .'?page=%d">%d</a></li>',
		    '<li class="page-item active"><a class="page-link" href="#">%d</a></li>',
		    '<li class="page-item disabled"><a class="page-link" href="#">&mdash;</a></li>');
		?>
		<li class="page-item <?=($next=='#') ? 'disabled' : '';  ?>">
			<a class="page-link" href="<?=$baseUrl . $next?>"><i class="bi bi-chevron-right" aria-hidden="true"></i></a>
		</li>
	</ul>
</div>
<?php endif; ?>
