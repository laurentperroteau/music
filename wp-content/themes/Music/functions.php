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



//iniciar session (para form o otro uso)
////////////////////////////////////////////////////////
if (!function_exists('init_sessions')) {
    function init_sessions() {
        if (!session_id()) {
            session_start();
        }
    }
    add_action('init', 'init_sessions');
}



/***
 * Incluir todos los ficheros que deberian estar en el function.php
 * pero que separamos para una mejor organizacion (ubicados todos 
 * en la carpeta "inc").
 */
//Para incluir este fichero, vaciar el function.php de twentyeleven
//include (STYLESHEETPATH . "/inc/twentyeleven_functions.php");

/***
 * El siguiente agregua un widget, unos sidebar, un post type y custom field 
 * del post type:
 *  -en el post type "Module" se inserta text / link / imagen
 *  -en los widgets, se agregua a los sidebar tantos modulos como require
 *  -incluimos sidebar diferentes en diferentes paginas
 * @see ver resultado thearkservices.com
 */
//include (STYLESHEETPATH . "/inc/module/module_widget.php");

/***
 * Agregua un post type "Home Slideshow", es un ejemplo de post type
 */
//include (STYLESHEETPATH . "/inc/post_types/home_slideshow/home_slideshow_type.php");

/***
 * Aqui se pone los custom field para los post y las page
 */
include (STYLESHEETPATH . "/inc/custom-meta.php");



//llamar a los ficheros de traducciones
//////////////////////////////////////////////////////
/***
 * Crear una carpeta "languagues" en el tema hijo con los fichero .mo y .po
 * usar asi:
 * <?php _e('Texto en el idioma original', 'TEMA'); ?>
 * y en una function "_e" tiene que ser "__"
 */
//load_theme_textdomain('TEMA', STYLESHEETPATH .'/languages');



//quitar menu en el panel (cuidado, el administrador tampoco los vera mas)
/////////////////////////////////////////////////////
function remove_menus() {
    global $menu;
    $restricted = array(__('Links'), __('Media'));//aqui se listea
    end($menu);
    while (prev($menu)) {
        $value = explode(' ', $menu[key($menu)][0]);
        if (in_array($value[0] != NULL ? $value[0] : "", $restricted)) {unset($menu[key($menu)]);
        }
    }
}
add_action('admin_menu', 'remove_menus');



//agreguar tamaño de imagenes (usar las de "Settings > Media" primero)
////////////////////////////////////////////////////////
/*usar asi:
 * <?php the_post_thumbnail('nombre_tamano');?>
 */
if (function_exists('add_image_size')) {
    add_image_size('nombre_tamano', 711, 297, true);
    /***
     * Si se una del panel y que no corta la imagen como quiere
     * entonces ponerle width:0 y height:0 
     * y sobreescribirla aqui el ultimo argumento en true
     */
    //add_image_size('medium', 213, 213, true); 
}



//cambiar tamaño de extracto (por defecto 55 palabras)
//////////////////////////////////////////////////////
function custom_excerpt_length( $length ) {
    return 25;
}
//add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );


/***
 * Para detectar el dispositive via "user_agents", unamos una class hecha
 * a proposito por eso (y que entonces tenemos que cambiar de vez en cuando
 * para actualizarla): https://github.com/serbanghita/Mobile-Detect
 * Esta class nos ayuda a saber si es mobile o tablet, creamos dos funciones
 * usandola para poder detectar facilemente en todo momento el dispositivo.
 */
    include 'inc/Mobile_Detect.php';
    $detect = new Mobile_Detect();
    
    function enfusion_mobile() {
        global $detect;
        if ($detect->isMobile() && !$detect->isTablet()):
             return true;
        endif;
    }
    
    function enfusion_tablet() {
        global $detect;
        if ($detect->isTablet()):
             return true;
        endif;
    }

/***
 * Agreguamos clases condicionales (CSS) en el body usando variables globales
 * que vienen por defecto con Wordpress. Ademas usamos las dos funciones 
 * creada justo antes para tener class css que les coresponde.
 */
function add_more_body_class($classes) {
    global $is_gecko, $is_chrome, $is_opera, $is_safari, $is_IE, $is_macIE, $is_winIE, $is_iphone, $is_lynx, $is_NS4, $post;
    
    //todas estas variables vienes con wordpress
    if ($is_gecko) { $classes[] = "firefox";}
    if ($is_chrome) { $classes[] = "chrome";}
    if ($is_opera) { $classes[] = "opera";}
    if ($is_safari) { $classes[] = "safari";}
    if ($is_IE) { $classes[] = "ie";}
    if ($is_macIE) { $classes[] = "macie";}
    if ($is_winIE) { $classes[] = "winie";}
    if ($is_iphone) { $classes[] = "iphone";}
    if ($is_lynx) { $classes[] = "lynx";}
    if ($is_NS4) { $classes[] = "ns4";}
    
    //agreguo clases a partir de "Mobile Detect"
    if (enfusion_mobile()) { $classes[] = "mobile";}
    if (enfusion_tablet()) { $classes[] = "tablet";}

    return $classes;
}
if (!is_admin()) {
    add_filter('body_class', 'add_more_body_class');
}


//agregua un clases (CSS) en el body a partir del slug del post (no usar en la mayoria de los casos)
/////////////////////////////////////////////////////
/*function add_slug_to_body_class($classes) {
    global $post;
    if (is_home()) {
        $key = array_search('blog', $classes);
        if ($key > -1) {
            unset($classes[$key]);
        }
    } elseif (is_page()) {
        $classes[] = sanitize_html_class($post->post_name);
    } elseif (is_singular()) {
        $classes[] = sanitize_html_class($post->post_name);
    }

    return $classes;
}
add_filter('body_class', 'add_slug_to_body_class'); // Add slug to body class (Starkers build)*/


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
    wp_deregister_script( 'jquery' );  
      
    // Registrar el jquery disponible en Google's CDN (mas rapido)
    wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js', array(), null, true ); 
      
    /***
     * Registrar los jquery:
     * @param $handle: nombre de script
     * @param $src: ubicacion
     * @param $deps: de cual otro script depende(asi lo pone antes)
     * @param $ver: la version
     * @param $in_footer: si queremos se ponga en el footer (muy recomendado)
     */
    //wp_register_script( 'fancy', get_bloginfo('stylesheet_directory') . '/js/jquery.fancybox.pack.js', array('jquery'), '2012.10.13', true );
    wp_register_script( 'script', get_bloginfo('stylesheet_directory') . '/js/script.js', array('jquery'), '2012.10.13', true );
  
    // Ahora usamos un tag condicional por agreguarlo en cola
    wp_enqueue_script( 'script' ); 
    //wp_enqueue_script( 'fancy' ); 
}  
add_action( 'wp_enqueue_scripts', 'enfusion_scripts' ); 

//lo mismo pero para poner en cola una validacion en el admin
function enfusion_admin() {
    wp_register_script( 'validate-admin', get_bloginfo('stylesheet_directory') . '/js/validate-admin.js', array('jquery'), '2012.09.13', true );
    wp_enqueue_script( 'validate-admin' );
}
//add_action('admin_enqueue_scripts', 'enfusion_admin');


// function que devuelve el contenido de una pagina y opcionalemente su titulo
/////////////////////////////////////////////////

/* @param id de la pagina
 * @param true si se quiere tambien el titulo (opcional)
 * @param tag del titulo (opcional)
 */
function enfusion_content_page($page_id, $title=false, $header='h1') {
    if (get_page($page_id)) :        
        $my_query = new WP_Query("page_id=$page_id");       
        while ($my_query->have_posts()) : $my_query->the_post();
            if($title):
                echo "<$header>";
                the_title();
                echo "</$header>";
            endif;                                                          
            the_content();
        endwhile;   
        // Reset Post Data
        wp_reset_postdata();
    endif;                
}

//quita el exceso de class en los menus
/////////////////////////////////////////////////////
function my_css_attributes_filter($var) {
    return is_array($var) ? array() : '';
}
//add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected classes (Commented out by default)
//add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected ID (Commented out by default)
//add_filter('page_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> Page ID's (Commented out by default)


/***
 * En los posts cuando se agregua un plugin SEO,
 * hay demasiadas columnas, se ve mal. Aqui
 * quitamos unas columnas no necesarias.
 */
function my_columns_filter( $columns ) {
    unset($columns['tags']);
    unset($columns['categories']);
    unset($columns['tags']);
    unset($columns['comments']);
    return $columns;
}
//add_filter( 'manage_edit-post_columns', 'my_columns_filter', 10, 1 );


//custom shortcode
//////////////////////////////////////////////////////

/*escribir en el editor:
 * [one_fourth] 
 * Lorem Ipsum
 * [one_fourth_end]
 * 
 * salida:
 *      <div class='one_fourth'>
 *          Lorem Ipsum
 *      </div>
 */
function one_fourth() {
    return "<div class='one_fourth'>";
}
//add_shortcode('one_fourth', 'one_fourth');

function one_fourth_end() {
    return "</div>";
}
//add_shortcode('one_fourth_end', 'one_fourth_end');


/*shortcode con attributo, escribir en el editor:
 * [tab name="a"]
 * Lorem Ipsum
 * [tab_end]
 * 
 * salida:
 *      <div id="tabs-a">
 *          Lorem Ipsum
 *      </div>
 */
function shortcode_tab($atts) {
    extract(shortcode_atts(array('name' => 'a'), $atts));
    return "<div id='tabs-$name'>";
}

//add_shortcode('tab', 'shortcode_tab');

function shortcode_tab_end() {
    return "</div>";
}
//add_shortcode('tab_end', 'shortcode_tab_end');

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