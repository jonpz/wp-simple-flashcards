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
			<?php
			the_archive_title( '<h1 class="archive-title main-title">', '</h1>' );
			the_archive_description( '<div class="archive-description">', '</div>' );
			?>
		</header>

		<?php
		$args = array(
			'post_type' => 'flashcard',
			'posts_per_page' => -1,
			'tax_query' => array(
				array(
					'taxonomy' => 'deck',
					'field' => 'slug',
					'terms' => get_queried_object()->slug,
				),
			),
		);
		if (!empty($_GET['shuffle'])) $args['orderby'] = 'rand';
		$card_query = new WP_Query($args);
		?>

		<div class="deck-outer">
			<?php if ($card_query->have_posts()) : ?>
				<div class="slider-container">
					<?php while ($card_query->have_posts()) : $card_query->the_post(); ?>
						<div class="flashcard-slide">
							<div class="flashcard-content">
								<div class="flip-container">
									<div class="front" style="background-image:url(<?=get_the_post_thumbnail_url($post, 'full')?>)"></div>
									<div class="back"><?=get_the_content()?></div>
								</div>
								<div class="flashcard-flipper"></div>
							</div>
						</div>
					<?php endwhile; ?>
				</div>
				<p class="shuffle-p">
					<a href="?shuffle=1">Shuffle Deck</a>
				</p>
			<?php else : ?>
				<h3 style="text-align: center; font-style: italic;">No flashcards found in this deck!</h3>
			<?php endif; wp_reset_postdata(); ?>
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
