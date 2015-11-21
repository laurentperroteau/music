<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 */

get_header(); ?>

	<div id="primary">
		<div id="content" role="main">

			<?php $args = array(
                     'post_type' => 'post',
                     'posts_per_page' => 1
                  );

                  $the_query = new WP_Query( $args );?>

                  <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

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

                    <div class="reply"><a href="<?php the_permalink(); ?>?comment">Laisser un commentaire &rarr;</a></div>

                  <?php endwhile; wp_reset_postdata(); ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<div class="more"><a href="#">Précédement &rarr;</a></div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>