<!doctype html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
    <?php if (is_plugin_active('advanced-custom-fields-pro/acf.php')) : the_field('head_scripts', 'options'); endif; ?>
  </head>

  <body <?php body_class(); ?>>
    <?php if (is_plugin_active('advanced-custom-fields-pro/acf.php')) : the_field('body_start_scripts', 'options'); endif; ?>
    <?php //wp_body_open(); ?>
    <?php do_action('get_header'); ?>

    <div id="app">
      <?php echo view(app('sage.view'), app('sage.data'))->render(); ?>
    </div>

    <?php do_action('get_footer'); ?>
    <?php wp_footer(); ?>
    <?php if (is_plugin_active('advanced-custom-fields-pro/acf.php')) : the_field('body_end_scripts', 'options'); endif; ?>
  </body>
</html>
