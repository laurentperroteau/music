
<?php 
/**
 * Module Type - Crea un nuevo post type "module"
 * @version 0.1 (2012_11_14)
 * @author Laurent Perroteau
 * @package Enfusion Theme
 */
 
add_action('init', 'module_register');
 
function module_register() {
 
    $labels = array(
        'name' => 'Modules',
        'singular_name' => 'Modules',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Module',
        'edit_item' => 'Edit Module',
        'new_item' => 'New Module',
        'view_item' => 'View Module',
        'search_items' => 'Search Module',
        'not_found' =>  'Nothing found',
        'not_found_in_trash' => 'Nothing found in Trash',
        'parent_item_colon' => ''
    );
 
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'menu_icon' => get_stylesheet_directory_uri() . '/img/post-types/module-type.jpg',
        'rewrite' => true,
        'capability_type' => 'page',//post
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title','editor','thumbnail')
      ); 
 
    register_post_type( 'module' , $args );
}

//si no funcciona la url amigables
flush_rewrite_rules();

//para funcionalidad de post thumbnails
add_theme_support('post-thumbnails');
 ?>