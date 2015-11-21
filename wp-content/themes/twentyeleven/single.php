<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

get_header(); ?>

		<div id="primary">
			<div id="content" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

					<nav id="nav-single">
						<h3 class="assistive-text"><?php _e( 'Post navigation', 'twentyeleven' ); ?></h3>
						<span class="nav-previous"><?php previous_post_link( '%link', __( '<span class="meta-nav">&larr;</span> Previous', 'twentyeleven' ) ); ?></span>
						<span class="nav-next"><?php next_post_link( '%link', __( 'Next <span class="meta-nav">&rarr;</span>', 'twentyeleven' ) ); ?></span>
					</nav><!-- #nav-single -->

	<!-- CONTENT -->

					<?php $fichier_mp3 = get_post_meta($post->ID, 'fichier_mp3', true);
                            $fichier_ogg = get_post_meta($post->ID, 'fichier_ogg', true); ?>
                              
                      	<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>

                        <p><?php the_content(); ?></p>

                        <audio preload="auto" controls="controls">
                          <source src="<?php echo $fichier_mp3; ?>" type="audio/mpeg" />
                          <source src="<?php echo $fichier_ogg; ?>" type="audio/ogg" />
                          Votre navigateur ne supporte pas les formats audio, utilisé un navigateur moderne comme Firefox
                        </audio>

                      	<?php the_post_thumbnail('full'); ?>

					<?php comments_template( '', true ); ?>

				<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>