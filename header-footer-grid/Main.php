<?php
namespace HFG;

use HFG\Core\Customizer;
use HFG\Core\Settings;

class Main {
	private static $_instance = null;

	/**
	 * @var Settings $settings
	 */
	private $settings;

	/**
	 * Main Instance
	 *
	 * Ensures only one instance of class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @return \HFG\Main Instance.
	 */
	public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance    = new self();

			self::$_instance->settings = Settings::get_instance();
			self::$_instance->init();
		}
		return self::$_instance;
	}

	public function init() {
		$this->register_sidebars();
		$customizer = new Customizer( $this->settings );

		add_filter( 'hfg-active', array( $this, 'is_active' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	public function enqueue_scripts() {
		wp_enqueue_style( 'hfg-style', esc_url( $this->settings->url ) . '/assets/css/style.css' );

		wp_enqueue_script(
			'hfg-theme-functions',
			esc_url( $this->settings->url ) . '/assets/js/theme.js',
			array(
				'jquery',
			),
			false,
			true
		);
	}

	public function is_active() {
		return true;
	}

	public function register_sidebars() {
		for ( $i = 1; $i <= 6; $i ++ ) {
			register_sidebar(
				array(
					/* translators: 1: Widget number. */
					'name'          => sprintf( __( 'Footer Sidebar %d', 'hfg-module' ), $i ),
					'id'            => 'footer-' . $i,
					'description'   => __( 'Add widgets here.', 'hfg-module' ),
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget'  => '</section>',
					'before_title'  => '<h4 class="widget-title">',
					'after_title'   => '</h4>',
				)
			);
		}

//		global $wp_registered_sidebars;
//
//		var_dump( $wp_registered_sidebars );
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @access public
	 * @since  1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'hfg-module' ), '1.0.0' );
	}

	/**
	 * Un-serializing instances of this class is forbidden.
	 *
	 * @access public
	 * @since  1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'hfg-module' ), '1.0.0' );
	}
}