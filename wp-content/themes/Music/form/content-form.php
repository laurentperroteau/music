<?php /** * Content Form - Contenido de un formulario que podemos incluir en una pagina * @version 0.3 (2012_11_14) * @author Laurent Perroteau * @package Enfusion Theme *//*** * Llamar este include asi: * <?php get_template_part('form/content', 'form'); ?>  *///call the class    include ('class/FormError.php'); ?>    <?php    //Recoge la variable de sesion con el objeto de errores.Si no existe, crea un nuevo objeto    if (isset($_SESSION['error'])) :        $obj_form = unserialize($_SESSION['error']);    else :        $obj_form = new FormError();    endif; ?>        <div id="form-error"><!--dejar el id, sirve de ancla-->        <?php echo $obj_form -> list_errors();//default: esp ?>    </div>    <form action="<?php bloginfo('stylesheet_directory'); ?>/form/envio.php" method="post" novalidate>    <ul>        <li>            <label for="name">Name</label>            <!--Poner a la function get_value el mismo nombre que el name del campo                Ademas poner las clases input-required o email-required para la validacion addicional en JavaScript-->            <input type="text" name="name" id="name" value="<?=$obj_form->get_value('name') ?>" class="input-required" />        </li>        <li>            <label for="email">Email</label>            <input type="text" name="email" id="email" value="<?=$obj_form->get_value('email') ?>" class="email-required" />        </li>                <li>            <label for="country">Country</label>            <input type="text" name="country" id="country" value="<?=$obj_form->get_value('country') ?>" />        </li>        <li class="textarea">            <label for="comment">Leave a message</label>        </li>        <li>            <textarea name="comment" id="comment"><?=$obj_form -> get_value('comment') ?></textarea>        </li>                <li>            <!--Damos al campo de seguridad otro nombre-->            <div style="display:none"><input type="text" name="el_campo_invisible" /></div>                        <!--Este campo nos da el nombre de la pagina donde se ha rellenado el form-->            <input type="hidden" name="page" value="<?php echo get_the_title(); ?>" />            <!--Con este campo enviamos la direccion de la pagina de gracias-->            <input type="hidden" name="gracias" value="<?php echo get_permalink(466); ?>" />        </li>               <li class="submit">            <input type="submit" id="submit" value="Send" />                        <input type="checkbox" name="newsletter" id="newsletter" class="checkbox-required" <?=$obj_form->get_value('newsletter')!=''?'checked="checked"':''?> />            <label for="newsletter">                Sign me up for the newsletter            </label>        </li>    </ul>    </form>