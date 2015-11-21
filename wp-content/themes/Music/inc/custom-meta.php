<?php 
/**
 * Custom Meta - Ejemplos para crear custom meta (campos de formulario adicional en los post, page o post type)
 * @version 0.2 (2012_11_14)
 * @author Laurent Perroteau
 * @package Enfusion Theme
 */
  
add_action("admin_init", "admin_post");
/*add box in dashboard
 * @param $id, name identification
 * @param $title, title box in dashboard
 * @param $callback, function print in dashboad (it's down)
 * @param $post_type, post, page or custom post type
 * @param $context, place of box (normal, advanced or side)
 * @param $priority, hight, core, defaulot or low 
 * @see (para las posiciones de los box): http://www.wproots.com/wp-content/uploads/2011/08/positions.png
 * @param $callback_args
 */
function admin_post(){
	add_meta_box("fichiers_audio", "Fichiers audio", "fichiers_audio", "page", "side", "default");
}

/***
 * Ejemplos con un input simple, un select, un checkbox y de select multiple
 */
function fichiers_audio() {
  
  global $post;
  
  //return an array with all custom meta
  $custom = get_post_custom($post->ID);
  
  //give a name for new custom meta (is possible save more than one)
  if(!empty($custom['fichier_mp3'][0])):
    $fichier_mp3 = $custom['fichier_mp3'][0];
  endif;

  if(!empty($custom['fichier_ogg'][0])):
    $fichier_ogg = $custom['fichier_ogg'][0];
  endif; 

?>

<!--::: Aqui creamos el form que :::-->

<p style="font-style:italic">Copier le "File Url" des fichiers audio:</p>


<?php /* display input
       * @param name, the name of the custom meta        
       */
?>       
      <label>Fichier MP3:</label><br />
      <input name="fichier_mp3" value="<?php if(!empty($fichier_mp3)) echo $fichier_mp3; ?>" class="dd" ></input>
      <br />
      <label>Fichier OGG (<a href="http://media.io/" title"Convertir" target="_blank">convertir format ogg):</a></label><br />
      <input name="fichier_ogg" value="<?php if(!empty($fichier_ogg)) echo $fichier_ogg; ?>" class="dd" ></input>

  <?php 
} //end callback_function

/***
 * Aqui guardamos los datos del form en la bdd
 */
add_action('save_post', 'save_detail');
function save_detail(){
    global $post;

    // Check for autosaves and quickedits. This is really important.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE || (defined('DOING_AJAX') && DOING_AJAX) ) 
        return;

    $post_type = isset($_POST['post_type']) ? $_POST['post_type'] : '';

    switch($post_type) {
        // If post type page
        case "page":
            update_post_meta($post_id, "fichier_mp3", $_POST['fichier_mp3']); 
            update_post_meta($post_id, "fichier_ogg", $_POST['fichier_ogg']); 
            break;
    }  
} //end save_details

?>