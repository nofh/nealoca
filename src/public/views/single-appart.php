<?php
/**
 * The Template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">
            <h1> DEBUG CPT APPART </h1>
            <?php while ( have_posts() ) : the_post(); ?> 
                <?php if( is_cpt_appart( $post->ID ) ) : global $post_appart; ?>
                    <!-- Cpt appart -->
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <pre>
                        <?php print_r( $post_appart ) ?> 
                        </pre>
                    </article><!-- #post -->
                <?php endif; ?>

                <!-- Nav -->
				<nav class="nav-single">
					<h3 class="assistive-text"><?php _e( 'Post navigation', 'twentytwelve' ); ?></h3>
					<span class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'twentytwelve' ) . '</span> %title' ); ?></span>
					<span class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'twentytwelve' ) . '</span>' ); ?></span>
				</nav><!-- .nav-single -->


			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>
