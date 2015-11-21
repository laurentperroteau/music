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
					<span class="nav-previous"><?php previous_post_link( '%link', __( '<span class="meta-nav">&larr;</span> Precédent', 'twentyeleven' ) ); ?></span>
					<span class="nav-next"><?php next_post_link( '%link', __( 'Suivant <span class="meta-nav">&rarr;</span>', 'twentyeleven' ) ); ?></span>
				</nav><!-- #nav-single -->

<!-- CONTENT -->

				<?php $fichier_mp3 = get_post_meta($post->ID, 'fichier_mp3', true);
                        $fichier_ogg = get_post_meta($post->ID, 'fichier_ogg', true); ?>
                          
                  	<h1><?php the_title(); ?></h1>

                    <?php the_content(); ?>

                    <audio preload="auto" controls="controls">
                      <source src="<?php echo $fichier_mp3; ?>" type="audio/mpeg" />
                      <source src="<?php echo $fichier_ogg; ?>" type="audio/ogg" />
                      Ton navigateur ne supporte pas les formats audio de HTML5, utiliser un navigateur moderne comme Firefox ou Chrome.
                    </audio>

                  	<?php if( has_post_thumbnail() ): ?>
                  	     <?php the_post_thumbnail('full'); ?>
                    <?php else: ?>
                        <img class="wp-post-image" src="<?php bloginfo('stylesheet_directory'); ?>/img/line.jpg" />
                    <?php endif; ?>

				<?php comments_template( '', true ); ?>

				<?php $comment = isset($_GET['comment'])? "comment": ""; ?>

				<div class="reply <?php echo $comment; ?>"><a href="#respond">Laisser un commentaire &rarr;</a></div>

			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<div class="more"><a href="#">Précédement &rarr;</a></div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>