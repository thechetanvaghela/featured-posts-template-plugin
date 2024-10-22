<?php

get_header();

echo '<div class="featured-posts-template">';
echo '<div class="posts-template-container">';
$featured_category = get_category_by_slug('featured');
$show_posts_on_scroll =  !empty(get_option('show_posts_on_scroll')) ? get_option('show_posts_on_scroll') : 3;
if ($featured_category) 
{
    $show_featured_posts =  !empty(get_option('show_featured_posts')) ? get_option('show_featured_posts') : 3;
    $args = array(
        'cat' => $featured_category->term_id,
        'posts_per_page' => $show_featured_posts, 
        'post_status' => 'publish'
    );

    $featured_posts = new WP_Query($args);
    if ($featured_posts->have_posts()) 
    {
        $counter = 0;
        echo '<div class="featured-posts-wrapper">';
        while ($featured_posts->have_posts())
        { 
            $featured_posts->the_post();
            $counter++;
            
            if ($counter % 2 == 1) {
                echo '<div class="featured-posts-row">';
            }
            ?>
           
            <div class="featured-post-item <?php echo $counter % 3 == 0 ? 'full' : ''; ?>" <?php if (has_post_thumbnail()) : ?> style="background : linear-gradient(0deg, rgb(4 4 4 / 48%), rgb(24 21 23 / 27%)), url('<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>'); background-size: cover; background-position: center;"<?php endif; ?>>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                        <?php the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>'); ?>
                    </header>

                    <div class="entry-content">
                        <?php
                        $excerpt = get_the_excerpt();
                        $excerpt = preg_replace('/\s*\[.*?\]\s*/', '', $excerpt);
                        echo wp_trim_words($excerpt, 20, '...');
                        ?>
                    </div>
                    <div class="read-more-content">
                        <a href="<?php echo esc_url(get_permalink()); ?>">Read More</a>
                    </div>
                </article>
            </div>
           
            <?php
            if ($counter % 2 == 0) {
                echo '</div>';
            }
        }
        
        if ($counter % 2 != 0) {
            echo '</div>';
        }
        
        echo '</div>';
        wp_reset_postdata();
    }
    else
    {
        echo '<p>No featured posts found.</p>';
    }
} else {
    echo '<p>Featured category not found.</p>';
}


echo '<div class="all-posts-wrapper">';
echo '<div id="ajax-post-data" class="">';
echo fp_latest_posts($show_posts_on_scroll,1);
echo '</div>';
echo '</div>';
?>
<div class="blog-loader-wrapper">
    <div class="blog-loader"></div>
</div>
<?php

echo '</div>';
echo '</div>';

get_footer();