<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/thechetanvaghela
 * @since      1.0.0
 *
 * @package    Featured_Posts
 * @subpackage Featured_Posts/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Featured_Posts
 * @subpackage Featured_Posts/public
 * @author     Chetan Vaghela <ckvaghela92@gmail.com>
 */
class Featured_Posts_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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
		if ( get_page_template_slug() == 'featured-posts-public' ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/featured-posts-public.css', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		 if ( get_page_template_slug() == 'featured-posts-public' ) {
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/featured-posts-public.js', array( 'jquery' ), $this->version, false );

			$show_posts_on_scroll =  !empty(get_option('show_posts_on_scroll')) ? get_option('show_posts_on_scroll') : 3;
			wp_localize_script(
				$this->plugin_name,
				'frontend_ajax_object',
				array(
					'ajaxurl' => admin_url('admin-ajax.php'),
					'ppp' => $show_posts_on_scroll
				)
			);
		 }

	}
	
	public function featured_posts_page_template( $template ) {
	
		if ( get_page_template_slug() == 'featured-posts-public' ) {
			
			$template = plugin_dir_path( __FILE__ ) . 'templates/featured-posts-public.php';
		}
		return $template;
		
	}
	

	public function featured_posts_theme_page_templates( $templates ) {
		$templates['featured-posts-public'] = __( 'Featured Posts', 'featured-posts' );
	
		return $templates;
	}

	public function featured_posts_excerpt_more( $more ) {
		return '<b> Read More...</b>';
	}
	
	public function featured_posts_excerpt_length( $length ) {
		return 20;
	}

	function fp_more_post_ajax()
	{
		$load_more = false;
		$ppp = (isset($_POST["ppp"])) ? $_POST["ppp"] : 5;
		$page = (isset($_POST['pageNumber'])) ? $_POST['pageNumber'] : 1;
		
		$featured_category = get_category_by_slug('featured');
		$args = array(
			'posts_per_page' => $ppp, 
			'post_status' => 'publish',
			'paged' => $page,
			'category__not_in' => array($featured_category->term_id)
		);
		$output = '';
		$all_posts = new WP_Query($args);

		$found_posts =  $all_posts->found_posts;
		$post_count =  $all_posts->post_count;
		if (!empty($found_posts)) {
			$load_more = true;
		}
		if ($found_posts == $post_count) {
			$load_more = false;
		} else if ($post_count < $ppp) {
			$load_more = false;
		}

		if ($all_posts->have_posts()) 
		{
			$counter = 0;
			
			while ($all_posts->have_posts())
			{ 
				$all_posts->the_post();
				
				$categories = get_the_category();
				$category_names = array();
				foreach ($categories as $category) {
					$category_names[] = $category->name;
				}

				$excerpt = get_the_excerpt();
				$excerpt = wp_strip_all_tags(strip_shortcodes($excerpt)); // Remove tags and shortcodes

				// Limit the excerpt to the specified word count
				$word_limit = 20;
				$words = explode(' ', $excerpt);
				if (count($words) > $word_limit) {
					$excerpt = implode(' ', array_slice($words, 0, $word_limit)) . ' <a style="text-decoration:none;" href="' . get_the_permalink() . '"><b>Read More...</b></a>';
				}
				
				$output .= '<div class="all-post-item ' . (($counter % 2 == 0) ? 'even' : 'odd') . '">
					<div class="post-content">
						<article id="post-' . get_the_ID() . '" ' . join(' ', get_post_class()) . '>
							<header class="entry-header">
							
       							<div class="post-categories">
       								'.implode(', ', $category_names).'
       							</div>
       
							<h3><a href="'.get_the_permalink().'">' . get_the_title() . '</a></h3>
						</header>

							<div class="entry-content">
								' . $excerpt . '
							</div>
								<footer class="entry-footer">
								<span class="author">By '.get_the_author().'</span>
								<span class="date">'.human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago</span>
								</footer>
						</article>
					</div>
					<div class="post-image">
						' . (has_post_thumbnail() ? get_the_post_thumbnail(null, 'medium') : '<img src="' . plugin_dir_url(__FILE__) . '/assets/images/placeholder.png" alt="Placeholder Image" class="placeholder-image">') . '
					</div>
				</div>';
				?>
				<?php $counter++; ?>
				<?php
			}
			
			wp_reset_postdata();
		}
		else
		{
			$output .= '<div class="col-12">';
			$output .= '<div class="text-center font-24 text-primary">';
			$output .=  'No blog found!';
			$output .= '</div>';
			$output .= '</div>';
		}
		
		$return = array(
			'html'  => $output,
			'load_more' => $load_more,
			'post_count' => $post_count,
			'found_posts' => $found_posts
		);
		echo json_encode($return);
		die();
	}

}


function fp_latest_posts($ppp = 6, $page = 1)
{
	$featured_category = get_category_by_slug('featured');
	$args = array(
		'posts_per_page' => $ppp, 
		'post_status' => 'publish',
		'paged' => $page,
		'category__not_in' => array($featured_category->term_id)
	);
	$output = '';
	$all_posts = new WP_Query($args);
	if ($all_posts->have_posts()) 
	{
		$counter = 0;
		
		while ($all_posts->have_posts())
		{ 
			$all_posts->the_post();

			$categories = get_the_category();
			$category_names = array();
			foreach ($categories as $category) {
				$category_names[] = $category->name;
			}
			?>
			
			<?php
			$output .= '<div class="all-post-item ' . (($counter % 2 == 0) ? 'even' : 'odd') . '">
				<div class="post-content">
					<article id="post-' . get_the_ID() . '" ' . join(' ', get_post_class()) . '>
						<header class="entry-header">
								<div class="post-categories">
       								'.implode(', ', $category_names).'
       							</div>
							<h3><a href="'.get_the_permalink().'">' . get_the_title() . '</a></h3>
						</header>

						<div class="entry-content">
							' . get_the_excerpt() . '
						</div>
						<footer class="entry-footer">
								<span class="author">By '.get_the_author().'</span>
								<span class="date">'.human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago</span>
								</footer>
					</article>
				</div>
				<div class="post-image">
					' . (has_post_thumbnail() ? get_the_post_thumbnail(null, 'medium') : '<img src="' . plugin_dir_url(__FILE__) . '/assets/images/placeholder.png" alt="Placeholder Image" class="placeholder-image">') . '
				</div>
			</div>';
			?>
			<?php $counter++; ?>
			<?php
		}
		
		wp_reset_postdata();
	}
	else
	{
		$output .= '<div class="col-12">';
		$output .= '<div class="text-center font-24 text-primary">';
		$output .=  'No blog found!';
		$output .= '</div>';
		$output .= '</div>';
	}
	return $output;
}
