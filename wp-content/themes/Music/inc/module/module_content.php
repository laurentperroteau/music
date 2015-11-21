<?php 
/**
 * Module Content - Funcion para crear el contenido de los "module"Crea un nuevo post type "module"
 * @version 0.1 (2012_11_14)
 * @author Laurent Perroteau
 * @package Enfusion Theme
 */

function module_content($id) {
    
    //get image
    $module_image = get_the_post_thumbnail($id, 'medium');
    
    //get link
    $link_id = get_post_meta($id, 'module_link', true);
    
    if($link_id == 'news'):
        $module_link = get_category_link(1);
        $module_link_target = false;
    elseif(empty($link_id)):
        $module_link =  get_post_meta($id, 'module_link_custom', true);
        $module_link_target = true;
    else:
        $module_link = get_permalink($link_id);
        $module_link_target = false;
    endif;
        
    //get content
    $content_post = get_post($id);
    $content = $content_post->post_content;
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]>', $content);
    
    /***
     * En este ejemplo tengo tres tipos de "mobules", solo imagen, imagen y texto, 
     * imagen texto y enlace y cuando no tendremos nada, pondremos un form.
     * Detectamos entonces que tipo tenemos en funcion de lo que encontramos:
     */
    if(!empty($module_image) && !empty($content) && !empty($module_link)):
        $module_kind = 'module_image_text_link';
    elseif (!empty($module_image) && !empty($content) && empty($module_link)):
        $module_kind = 'module_image_text';
    elseif (empty($module_image) && !empty($content)):
        $module_kind = 'module_text';
    elseif (!empty($module_image) && empty($content)):
        $module_kind = 'module_image';
    else: 
        $module_kind = 'module_empty';
    endif;
    
    //CREAMOS AHORA EL HTML
    
    //if image and text and link
    if($module_kind == 'module_image_text_link'):?>
        <li class="module_image_text_link modules">
        	<ul>
        		<li>
        			<a href="<?php echo $module_link; ?>"<?php    if ($module_link_target == true) : echo " target='_blank'";    endif; ?> title="<?php echo get_the_title($id); ?>"> <?php echo $module_image; ?> </a>		</li>
        		<li>
        			<?php echo $content; ?>
        		</li>
        		<li>
        			<a href="<?php echo $module_link; ?>"<?php    if ($module_link_target == true) : echo " target='_blank'";    endif; ?> title="<?php echo get_the_title($id); ?>"> <span>+</span> view more </a>		</li>
        	</ul>
        </li>
<?php endif;
    if($module_kind == 'module_image_text'): ?>
        <li class="module_image_text modules">
        	<ul>
        		<li>
        			<?php echo $module_image; ?>
        		</li>
        		<li>
        			<?php echo $content; ?>
        		</li>
        	</ul>
        </li>
<?php endif;
    //if only text
    if($module_kind == 'module_text'): ?>
        <li class="module_text modules">
        	<ul>
        		<li>
        			<?php echo $content; ?>
        		</li>
        	</ul>
        </li>
<?php endif;
    //if only image
    if($module_kind == 'module_image'): ?>
        <li>
        	<ul>
        		<li>
        			<a href="<?php echo $module_link; ?>"<?php    if ($module_link_target == true) : echo " target='_blank'";    endif; ?> title="<?php echo get_the_title($id); ?>"> <?php echo $module_image; ?> </a>		</li>
        	</ul>
        </li>
<?php endif;
    //if no image and no text => form
    if($module_kind == 'module_empty'): ?>
        <li class="module_form form modules">
        	<h3>Have any questions?</h3>
        	<?php require_once(STYLESHEETPATH . '/form/content-form.php');	?>
    	</li>
<?php endif;
    }//end function module_content?>