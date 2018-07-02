<?php
/**
 * The Theme specific functionality.
 *
 * @since   3.0.0 Removing global variables.
 * @since   2.0.0
 * @package Inf_Theme\Theme
 */

namespace Inf_Theme\Theme;

use Inf_Theme\Helpers\General_Helper;
use Inf_Theme\Includes\Config;

/**
 * Class Theme
 */
class Theme extends Config {

  /**
   * General Helper class
   *
   * @var object General_Helper
   *
   * @since 2.0.1
   */
  public $general_helper;

  /**
   * Initialize class
   *
   * @param Helpers\General_Helper $general_helper Helper class instance.
   *
   * @since 3.0.0 Removing constructor and global variables.
   * @since 2.0.0
   */
  public function __construct( General_Helper $general_helper ) {
    $this->general_helper = $general_helper;
  }

  /**
   * Register the Stylesheets for the theme area.
   *
   * @since 2.0.0
   */
  public function enqueue_styles() {

    $main_style = $this->general_helper->get_manifest_assets_data( 'application.css' );
    wp_register_style( static::THEME_NAME . '-style', $main_style );
    wp_enqueue_style( static::THEME_NAME . '-style' );

  }

  /**
   * Register the JavaScript for the theme area.
   *
   * First jQuery that is loaded by default by WordPress will be deregistered and then
   * 'enqueued' with empty string. This is done to avoid multiple jQuery loading, since
   * one is bundled with webpack and exposed to the global window.
   *
   * @since 2.0.0
   */
  public function enqueue_scripts() {
    // jQuery.
    wp_deregister_script( 'jquery-migrate' );
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', get_template_directory_uri() . '/skin/public/scripts/vendors/jquery.min.js', array(), '3.3.1' );
    wp_enqueue_script( 'jquery' );

    // JS.
    $main_script_vandors = $this->general_helper->get_manifest_assets_data( 'vendors.js' );
    wp_register_script( static::THEME_NAME . '-scripts-vendors', $main_script_vandors );
    wp_enqueue_script( static::THEME_NAME . '-scripts-vendors' );

    $main_script = $this->general_helper->get_manifest_assets_data( 'application.js' );
    wp_register_script( static::THEME_NAME . '-scripts', $main_script );
    wp_enqueue_script( static::THEME_NAME . '-scripts' );

    // Glbal variables for ajax and translations.
    wp_localize_script(
      static::THEME_NAME . '-scripts', 'themeLocalization', array(
          'ajaxurl' => admin_url( 'admin-ajax.php' ),
      )
    );
  }

}
