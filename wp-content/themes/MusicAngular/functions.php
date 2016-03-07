<?php 
/**
 * Function.php - Ejemplo de la funciones mas usadas
 * @version 0.1 (2012_11_14)
 * @author Laurent Perroteau
 * @package Enfusion Theme
 */

/***
 * Descomentar para usarlo (quitar el guion bajo)!
 * El function.php es quizas el fichero mas importantes, 
 * esta leido antes de todos los otros ficheros de tema
 * pero despues de los plugins. 
 */

//definir ruta de los cookies (para hosting como godaddy)
/////////////////////////////////////////////////////////
//session_save_path('/home/content/65/9646665/html/sess');

/*function addPostToJson( $example ) {

        $data = array();

        $data['customMeta'] = array(
            'fichierMp3' => get_post_meta( $post->ID, 'fichier_mp3', true )
        );

        return $data;
    }
    add_filter( 'json_prepare_post', 'addPostToJson', 10, 3);*


/***
 * Aqui se pone los custom field para los post y las page
 */
// include 'inc/custom-meta.php';

/***
 * Incluir todos los ficheros que deberian estar en el function.php
 * pero que separamos para una mejor organizacion (ubicados todos 
 * en la carpeta "inc").
 */
//Para incluir este fichero, vaciar el function.php de twentyeleven
//include (STYLESHEETPATH . "/inc/twentyeleven_functions.php");

add_theme_support( 'post-thumbnails' );

/***
 * MUY IMPORTANTE - Cuando usamos un plugin jQuery (un slideshow por ejemplo), 
 * generalemente no lo necesitamos en mas de una pagina o de un tipo de pagina.
 * Es necesario que este llamado unicamente en las paginas necesarios por razones
 * de optimizacion y para evitar conflictos.
 * Aqui registramos primeros todos los css y javascript adicional y los ponemos
 * "enqueue" (en cola) donde hace falta.
 */
function enfusion_scripts()  {
    
    // Registrar css utiles para los scripts jquery
    //wp_register_style( 'sequence', get_stylesheet_directory() . '/js/home-slideshow/css/sequence.css', array(), '2012.10.13', 'all' );   
    
    // De-registrar el jquery incluido en el wordpress  
    // wp_deregister_script( 'jquery' );  
      
    // Registrar el jquery disponible en Google's CDN (mas rapido)
    // wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js', array(), null, true ); 
      
    /***
     * Registrar los jquery:
     * @param $handle: nombre de script
     * @param $src: ubicacion
     * @param $deps: de cual otro script depende(asi lo pone antes)
     * @param $ver: la version
     * @param $in_footer: si queremos se ponga en el footer (muy recomendado)
     */
    //wp_register_script( 'fancy', get_bloginfo('stylesheet_directory') . '/js/jquery.fancybox.pack.js', array('jquery'), '2012.10.13', true );
    wp_register_script( 'angular', get_bloginfo('stylesheet_directory') . '/bower_components/angular/angular.min.js');
    wp_register_script( 'app', get_bloginfo('stylesheet_directory') . '/js/app.js');
    wp_register_script( 'factories', get_bloginfo('stylesheet_directory') . '/js/factories.js');
    wp_register_script( 'directives', get_bloginfo('stylesheet_directory') . '/js/directives.js');
    wp_register_script( 'controllers', get_bloginfo('stylesheet_directory') . '/js/controllers.js');
  
    // Ahora usamos un tag condicional por agreguarlo en cola
    wp_enqueue_script( 'angular' ); 
    wp_enqueue_script( 'app' ); 
    wp_enqueue_script( 'factories' ); 
    wp_enqueue_script( 'directives' ); 
    wp_enqueue_script( 'controllers' ); 
}  
add_action( 'wp_enqueue_scripts', 'enfusion_scripts' ); 



if ( ! function_exists( 'twentyeleven_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own twentyeleven_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_comment( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    switch ( $comment->comment_type ) :
        case 'pingback' :
        case 'trackback' :
    ?>
    <li class="post pingback">
        <p><?php _e( 'Pingback:', 'twentyeleven' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?></p>
    <?php
            break;
        default :
    ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
        <article id="comment-<?php comment_ID(); ?>" class="comment">
            <footer class="comment-meta">
                <div class="comment-author vcard">
                    <?php
                        $avatar_size = 68;
                        if ( '0' != $comment->comment_parent )
                            $avatar_size = 39;

                        echo get_avatar( $comment, $avatar_size );

                        /* translators: 1: comment author, 2: date and time */
                        printf( __( 'Commentaire de %1$s:</span>', 'twentyeleven' ),
                            sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
                            sprintf( '<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
                                esc_url( get_comment_link( $comment->comment_ID ) ),
                                get_comment_time( 'c' ),
                                /* translators: 1: date, 2: time */
                                sprintf( __( '%1$s at %2$s', 'twentyeleven' ), get_comment_date(), get_comment_time() )
                            )
                        );
                    ?>

                    <?php edit_comment_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?>
                </div><!-- .comment-author .vcard -->

                <?php if ( $comment->comment_approved == '0' ) : ?>
                    <em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'twentyeleven' ); ?></em>
                    <br />
                <?php endif; ?>

            </footer>

            <div class="comment-content"><?php comment_text(); ?></div>

            <div class="reply">
                <?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply <span>&darr;</span>', 'twentyeleven' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
            </div><!-- .reply -->
        </article><!-- #comment-## -->

    <?php
            break;
    endswitch;
}
endif; // ends check for twentyeleven_comment()
?>