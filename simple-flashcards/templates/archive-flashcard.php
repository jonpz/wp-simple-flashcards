<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each specific one. For example, Boss already
 * has tag.php for Tag archives, category.php for Category archives, and
 * author.php for Author archives.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 1.0.0
 */
get_header();
?>

<?php
if ( is_active_sidebar( 'sidebar' ) ) :
	echo '<div class="page-right-sidebar">';
else :
	echo '<div class="page-full-width">';
endif;
?>

<section id="primary" class="site-content">
	<div id="content" role="main">

		<header class="archive-header page-header">
			<h1 class="archive-title main-title">Flashcard Decks</h1>
		</header>

		<?php
		$decks = get_terms(array(
			'taxonomy' => 'deck',
			'orderby' => 'name',
		));
		?>

		<div>
			<?php
			foreach ($decks as $d) :
				$image = get_field('deck_image', 'deck_' . $d->term_id);
				?>
				<div class="deck-tile">
					<a href="/flashcards/deck/<?=$d->slug?>">
						<?php if ($image) : ?>
							<img src="<?=$image['sizes']['thumbnail']?>"/><h4><?=$d->name?></h4>
						<?php else : ?>
							<i class="fa fa-layer-group fa-2x"></i><h4><?=$d->name?></h4>
						<?php endif; ?>
					</a>
				</div>
			<?php endforeach; ?>
		</div>

	</div><!-- #content -->
</section><!-- #primary -->

<?php
if ( is_active_sidebar( 'sidebar' ) ) :
	get_sidebar( 'sidebar' );
endif;

// page-left-sidebar
echo '</div>';

get_footer();
