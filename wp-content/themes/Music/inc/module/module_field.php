<?php 
/**
 * Module Fied - Crea custom field para el post type "module"
 * @version 0.1 (2012_11_14)
 * @author Laurent Perroteau
 * @package Enfusion Theme
 */

add_action("admin_init", "admin_module");
function admin_module(){
    add_meta_box("module_link", "Link of module", "module_link", "module", "side", "default");
}

function module_link() {
  global $post;

  //return an array with all custom meta
  $custom = get_post_custom($post->ID);
  
  //give a name for new custom meta (is possible save more than one)
  if(!empty($custom['module_link'][0])):
    $module_link = $custom['module_link'][0];
  endif;
  if(!empty($custom['module_link_custom'][0])):
  $module_link_custom = $custom['module_link_custom'][0];
  endif; ?>

<!--::: Aqui creamos el form que se vera en el module del post type "module" :::-->

<p style="font-style:italic">select the page you want to link in this module</p>

<?php $pages = get_pages();?>

<select name="module_link" style="width:95%">
    <option value="">------</option>
    <?php foreach ($pages as $page) : ?>
        <option value="<?php echo $page->ID; ?>"<?php if($page->ID == $module_link): echo " selected='selected'"; endif; ?>>
            <?php echo $page->post_title; ?>
        </option>
    <?php endforeach; ?>
    <!--category news-->
    <option value="news"<?php if($module_link == 'news'): echo " selected='selected'"; endif; ?>>Category News</option>
</select>

<!--custom link-->
<p style="font-style:italic">or copy a link from an other site</p>   

<input name="module_link_custom" value="<?php if (!empty($module_link_custom)): echo $module_link_custom; endif; ?>" style="width:90%" /><br />


  <?php
}//end module_link


// here we save the data in database
add_action('save_post', 'save_module');
function save_module(){
    global $post;

    // Check for autosaves and quickedits. This is really important.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE || (defined('DOING_AJAX') && DOING_AJAX) ) 
        return;

    $post_type = isset($_POST['post_type']) ? $_POST['post_type'] : '';

    switch($post_type) {
        // If post type page
        case "module":
            update_post_meta($post_id, "module_link", $_POST['module_link']);
            update_post_meta($post_id, "module_link_custom", $_POST['module_link_custom']);
            break;
    }  
} //end save_module
?>