<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?> data-page-id="<?php the_ID(); ?>">
  <!-- Loading spinner (initially hidden) -->
  <div id="spa-loading">
    <div class="spinner"></div>
  </div>