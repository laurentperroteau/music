<?php 
/**
 * Module Widget - Crea un nuevo widget y nuevos sidebar
 * @version 0.1 (2012_11_14)
 * @author Laurent Perroteau
 * @package Enfusion Theme
 */

// 1 - Cremos un nuevo post type "module"
include "module_type.php";

// 2 - Creamos custom field para el post type
include "module_field.php";

// 3 - Creamos una function (para el contenido de los "modules") que usaremos mas abajo
include "module_content.php";

// 4 - Creamos el nuevo widget

add_action( 'widgets_init', 'my_widget' );
//register the name of class (call in action)
function my_widget() {
    register_widget( 'MY_Widget' );
}

//principio de la class
class MY_Widget extends WP_Widget {

    function __construct() {
        $widget_ops = array( 'classname' => 'module', 'description' => 'Module for Estate Agents' );
        $control_ops = array( 'width' => 200, 'height' => 350, 'id_base' => 'module-widget' );
        
        $this->WP_Widget( 'module-widget', 'Module', $widget_ops, $control_ops );
    }
    
    function widget( $args, $instance ) {
        extract( $args );
        
        $module_id = $instance['module'];
        
        //aqui llamamos la funcion que crea el contenido (le pasamos el id del "module")
        module_content($module_id);
    }

    //Update the widget 
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        
        $instance['title'] = $new_instance['title'];
        $instance['module'] = $new_instance['module'];

        return $instance;
    }

    //Creamos el form del widget (un desplegable de los nombres de todos los modules, )
    function form( $instance ) {

        //Set up some default widget settings.
        $defaults = array( 'title' => 'The Ark What is it?', 'module' => '49');
        $instance = wp_parse_args( (array) $instance, $defaults ); ?>
        
        
        <label for="<?php echo $this->get_field_id( 'module' ); ?>">Choice of modules</label><br /><br />
        
        <?php   //get all modules page
                $module_array = get_posts(array('post_type' => 'module', 'numberposts' => 100, 'orderby' => 'title', 'order' => 'ASC' )); 

                //define variables
                $module_value = $instance['module']; ?>
            
                <select id="<?php echo $this->get_field_id('module'); ?>" name="<?php echo $this->get_field_name('module'); ?> width="100%">
                
         <?php  foreach ( $module_array as $page):
                    $module_id = $page->ID;
                    $module_title = $page->post_title;
                    
                    echo "<option value='$module_id'";
                        if($module_id == $module_value):
                            echo " selected='selected'";
                            $module_name = $module_title;
                        endif;
                    echo ">";
                    echo $module_title;
                    echo "</option>";
                endforeach;
                
                echo "</select>"; ?>
                
            <!-- Este campo sirve unicamente para tener un titulo al "module" -->
            <input type="hidden" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $module_name; ?>" />
    <?php
    }
}//end class widget


// 5 - Desactivamos todos los widgets que vienen por defecto y que no nos hare falta

function unregister_default_wp_widgets() {
    //unregister_widget('WP_Widget_Pages');
    unregister_widget('WP_Widget_Calendar');
    unregister_widget('WP_Widget_Archives');
    unregister_widget('WP_Widget_Links');
    unregister_widget('WP_Widget_Meta');
    //unregister_widget('WP_Widget_Search');
    //unregister_widget('WP_Widget_Text');
    unregister_widget('WP_Widget_Categories');
    unregister_widget('WP_Widget_Recent_Posts');
    unregister_widget('WP_Widget_Recent_Comments');
    unregister_widget('WP_Widget_RSS');
    unregister_widget('WP_Widget_Tag_Cloud');
    unregister_widget('WP_Nav_Menu_Widget');
}
add_action('widgets_init', 'unregister_default_wp_widgets', 1);


// 6 - Desactivamos todos los sidebar que vienen por defecto y que no nos hare falta

function remove_some_widgets(){    
    // Unregsiter some of the TwentyTen sidebars
    unregister_sidebar( 'sidebar-1' );
    unregister_sidebar( 'sidebar-2' );
    unregister_sidebar( 'sidebar-3' );
    unregister_sidebar( 'sidebar-4' );
    unregister_sidebar( 'sidebar-5' );
}
add_action( 'widgets_init', 'remove_some_widgets', 11 );


// 7 - En fin agregamos nuestros proprios sidebar

/***
 * para llamarlos:    
    <?php dynamic_sidebar('home-top'); ?>
 */

if ( function_exists('register_sidebar') ) {
    register_sidebar(array('name' => 'Home top', 'id' => 'home-top', 'description' => 'Appears at the top of the home page'));
    register_sidebar(array('name' => 'Home right','id' => 'home-right','description' => 'Appears on the right in home page'));
    register_sidebar(array('name' => 'Search result left','id' => 'search-left', 'description' => 'Appears on the lower left in the search result page'));
    register_sidebar(array('name' => 'Details property right','id' => 'property-right', 'description' => 'Appears on the lower right in the details property page'));
}

?>