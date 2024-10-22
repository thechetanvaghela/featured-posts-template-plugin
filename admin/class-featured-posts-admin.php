<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/thechetanvaghela
 * @since      1.0.0
 *
 * @package    Featured_Posts
 * @subpackage Featured_Posts/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Featured_Posts
 * @subpackage Featured_Posts/admin
 * @author     Chetan Vaghela <ckvaghela92@gmail.com>
 */
class Featured_Posts_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Featured_Posts_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Featured_Posts_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/featured-posts-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Featured_Posts_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Featured_Posts_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/featured-posts-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function featured_posts_admin_init()
	{
		register_setting('featured_posts_settings_group', 'show_featured_posts');
		register_setting('featured_posts_settings_group', 'show_posts_on_scroll');
	}

	public function featured_posts_admin_menu() {
		add_menu_page(
			'Featured Post', // Page Title
			'Featured Post', // Menu Title
			'manage_options', // Capability
			'abtt-settings', // Menu Slug
			array($this, 'cv_featured_posts_callback'), // Callback function
			'dashicons-star-filled', // Icon
			20 // Position
		);
	}

	public function cv_featured_posts_callback() {
		?>
		<div id="featured_posts-settings-page">
			<div class="wrap">
				<h1>Featured Posts Page Settings</h1>
				<form method="post" action="options.php">
					<?php settings_fields('featured_posts_settings_group'); ?>
					<?php do_settings_sections('featured_posts_settings_group'); ?>
					<table class="form-table">
						<tr valign="top" class="">
							<th scope="row">Show Featured Posts</th>
							<td>
								<input type="number" name="show_featured_posts" value="<?php echo esc_attr(get_option('show_featured_posts', 5)); ?>" min="1" />
							</td>
						</tr>
						<tr valign="top" class="">
							<th scope="row">Show posts on scroll</th>
							<td>
								<input type="number" name="show_posts_on_scroll" value="<?php echo esc_attr(get_option('show_posts_on_scroll', 5)); ?>" min="1" />
							</td>
						</tr>
					</table>

					<?php submit_button(); ?>
				</form>
			</div>
		</div>
		<?php
	}

}

