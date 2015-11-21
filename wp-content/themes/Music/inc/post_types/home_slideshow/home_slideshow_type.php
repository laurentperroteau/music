<?php 
/**
 * Slideshow Type - Ejemplo de creacion de un post type
 * @version 0.1 (2012_11_14)
 * @author Laurent Perroteau
 * @package Enfusion Theme
 */

add_action('init', 'slideshow_register');
function slideshow_register() {

    //definicion de los labels (textos usados en el panel para este post type)
    $labels = array(
        'name' => 'Home Slideshow',
        'singular_name' => 'Home Slideshow',
        'add_new' => 'Add New Home Slideshow',
        'add_new_item' => 'Add New Home Slideshow',
        'edit_item' => 'Edit Home Slideshow',
        'new_item' => 'New Home Slideshow',
        'view_item' => 'View Home Slideshow',
        'search_items' => 'Search Home Slideshow',
        'not_found' =>  'Nothing found',
        'not_found_in_trash' => 'Nothing found in Trash',
        'parent_item_colon' => ''
    );

    /*
     * Todo los argumentos:
     *  -los labels definidos antes
     *  -menu_icon (el icon que se mostrara en el panel)
     *  -rewrite (para usar friendly url)
     *  -capability_type (puede ser tambine post, attachment o media)
     *  -hierarchical (si puede tener hijos)
     *  -support (puede agreguar tambien revision, except, revisions, page-attributes)
     */
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'menu_icon' => get_stylesheet_directory_uri() . '/img/post-types/slide-type.jpg',
        'rewrite' => true,
        'capability_type' => 'page',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title','editor','thumbnail','page-attributes')
      ); 


    //el slug del post type
    register_post_type( 'home_slideshow' , $args );
    
    /*si no funcciona la url amigables*/
    flush_rewrite_rules();

}
//agraguar soporte para los post thumbnails
add_theme_support('post-thumbnails');

//y podria agregua sus custom field aqui:
//include "slideshow_field.php";

/***
 * Podemos tambien crear categorias proprias para los custom type (taxonomy en 
 * lenguage Wordpress), un ejemplo abajo:
 */

// agreguar categorias de proyectos
  $labels_cate = array(
    'name' => 'Categoria de proyecto',
    'singular_name' => 'Categoria de proyecto',
    'search_items' =>  'Search Categoria de proyecto',
    'all_items' => 'All Categoria de proyecto',
    'parent_item' => 'Parent Categoria de proyecto',
    'parent_item_colon' => 'Parent Categoria de proyecto:',
    'edit_item' => 'Edit Categoria de proyecto', 
    'update_item' => 'Update Categoria de proyecto',
    'add_new_item' => 'Add New Categoria de proyecto',
    'new_item_name' => 'New Categoria de proyecto Name',
    'menu_name' => 'Categoria de proyecto',
  );    

  register_taxonomy('p_category',array('proyecto'), array(
    /***
     * Nota importante: cuando se usa WPML, 
     * poner  'hierarchical' => true, 
     * y luego quitarlo 
     */
    'labels' => $labels_cate,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => true,
  ));
?>