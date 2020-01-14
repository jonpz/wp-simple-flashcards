<?php
/**
 * Simple Flashcards
 *
 * Plugin Name:       Simple Flashcards
 * Description:       Flashcard study tool plugin with decks
 * Version:           0.1.0
 * Author:            Jon Phillips
 */

if ( ! defined( 'ABSPATH' ) ) {
    die('What are you doing Dave?');
}

function simple_create_flashcards() {
  register_post_type('flashcard', array(
    'labels' => array(
      'name' => __('Flashcards'),
      'singular_name' => __('Flashcard'),
      'add_new_item' => __('Add New Flashcard'),
      'edit_item' => __('Edit Flashcard'),
      'new_item' => __('New Flashcard'),
      'view_item' => __('View Flashcard'),
      'view_items' => __('View Flashcards'),
      'search_items' => __('Search Flashcards'),
      'all_items' => __('All Flashcards'),
      'item_published' => __('Flashcard published.'),
      'item_published_privately' => __('Flashcard published privately.'),
      'item_reverted_to_draft' => __('Flashcard reverted to draft.'),
      'item_scheduled' => __('Flashcard scheduled.'),
      'item_updated' => __('Flashcard updated.'),
    ),
    'public' => true,
    'has_archive' => true,
    'rewrite' => array('slug' => 'flashcard-decks', 'with_front' => false),
    'show_ui' => true,
    'menu_icon' => 'dashicons-images-alt',
    'supports' => array('title', 'editor', 'thumbnail'),
  ));
}
add_action('init', 'simple_create_flashcards');

function flashcards_taxonomy() {
  register_taxonomy( 'deck', array('flashcard'), array(
    'labels' => array(
      'name' => __('Decks'),
      'singular_name' => __('Deck'),
      'all_items' => __('All Decks'),
      'edit_item' => __('Edit Deck'),
      'view_item' => __('View Deck'),
      'update_item' => __('Update Deck'),
      'add_new_item' => __('Add New Deck'),
      'new_item_name' => __('New Deck Name'),
      'parent_item' => __('Parent Deck'),
      'parent_item_colon' => __('Parent Deck:'),
      'search_items' => __('Search Decks'),
      'not_found' => __('No decks found.'),
      'back_to_items' => __('← Back to decks'),
    ),
    'hierarchical' => true,
    'rewrite' => array('slug' => 'flashcards/deck', 'with_front' => false, 'hierarchical' => true),
    'show_admin_column' => true,
    )
  );
}
add_action( 'init', 'flashcards_taxonomy' );

function filter_flashcards_by_deck() {
	global $typenow;
	$post_type = 'flashcard';
	$taxonomy  = 'deck';
	if ($typenow == $post_type) {
		$selected = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
		$info_taxonomy = get_taxonomy($taxonomy);
		wp_dropdown_categories(array(
			'show_option_all' => __("Show All {$info_taxonomy->label}"),
			'taxonomy' => $taxonomy,
			'name' => $taxonomy,
			'orderby' => 'name',
			'selected' => $selected,
			'show_count' => true,
			'hide_empty' => true,
		));
	};
}
add_action('restrict_manage_posts', 'filter_flashcards_by_deck');

function sfc_convert_id_to_term_in_query($query) {
	global $pagenow;
	$post_type = 'flashcard';
	$taxonomy = 'deck';
	$q_vars = &$query->query_vars;
	if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0 ) {
		$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
		$q_vars[$taxonomy] = $term->slug;
	}
}
add_filter('parse_query', 'sfc_convert_id_to_term_in_query');

function deck_template( $template ) {
  if ( is_tax('deck') ) {
    $theme_files = array('archive-deck.php');
    $exists_in_theme = locate_template($theme_files, false);
    if ( $exists_in_theme != '' ) {
      return $exists_in_theme;
    } else {
      return plugin_dir_path(__FILE__) . 'templates/archive-deck.php';
    }
  }
  return $template;
}
add_filter('template_include', 'deck_template');

function flashcard_archive_template( $template ) {
  if ( is_post_type_archive('flashcard') ) {
    $theme_files = array('archive-flashcard.php');
    $exists_in_theme = locate_template($theme_files, false);
    if ( $exists_in_theme != '' ) {
      return $exists_in_theme;
    } else {
      return plugin_dir_path(__FILE__) . 'templates/archive-flashcard.php';
    }
  }
  return $template;
}
add_filter('template_include', 'flashcard_archive_template');

function flashcard_single_template( $template ) {
  if ( is_singular('flashcard') ) {
    $theme_files = array('single-flashcard.php');
    $exists_in_theme = locate_template($theme_files, false);
    if ( $exists_in_theme != '' ) {
      return $exists_in_theme;
    } else {
      return plugin_dir_path(__FILE__) . 'templates/single-flashcard.php';
    }
  }
  return $template;
}
add_filter('template_include', 'flashcard_single_template');

function flashcard_enqueues() {
  wp_enqueue_script('slick_js', plugin_dir_url(__FILE__) . 'slick/slick.min.js', array('jquery'));
  wp_enqueue_script('flashcard_js', plugin_dir_url(__FILE__) . 'js/flashcards.js', array('jquery'), '1.0', true);
  wp_enqueue_style('slick_styles', plugin_dir_url(__FILE__) . 'slick/slick.css');
  wp_enqueue_style('slick_theme_styles', plugin_dir_url(__FILE__) . 'slick/slick-theme.css');
  wp_enqueue_style('flashcard_add_styles', plugin_dir_url(__FILE__) . 'css/add.css');
}
add_action('wp_enqueue_scripts', 'flashcard_enqueues');

?>
