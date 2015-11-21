<?php
/**
 * Form Error - Clase PHP para mostrar errores en un formulario
 * @author Santi Bartolome
 * @version 0.2 (2012_07_17)
 * @package Enfusion Theme 
 */

include('Message.php');

class FormError{

    private $errores = array();
    private $errores_msg = array();
    private $pcampos = array();
    
    
    //constructora de la clase
    //@param array $input Campos del formularios
    
    public function __construct($input=NULL){
        $this -> pcampos = $input;

        //pasamos un mensaje de error por defecto
        if($input != NULL)
        {
            foreach($this->pcampos as $key => $value)
            {
                $error = new Message('es','Ha de rellenar el campo');
                $this->errores_msg[$key] = $error;
            }
        }
    }
    
    
    //Fija un texto de error para un campo del formulario
    //@param string $key string $msg Nombre del campo y texto del error que se quiere asignar
    //@return void
    
    public function set_error($key, $msg, $lang='es')
    {
        if(isset($this -> errores_msg[$key])) $error = $this -> errores_msg[$key];      
        else $error = new Message();
        $error->set_message($lang,$msg);
        $this -> errores_msg[$key] = $error;      
    }
    
    
    //Comprueba los campos que tienen que estar llenos
    //@param array $array Listado del nombre de los campos a validar
    //@return boolean
    
    public function validar_campos($array)
    {       
        $return = true;
        
        foreach($array as $clave) {
            
            //el checkbox no existe si no check, entonces veirifamos que existe antes
            if(isset($this -> pcampos[$clave])){                      
                if($this -> pcampos[$clave] == ''){
                    $this -> errores[$clave] = $this -> errores_msg[$clave];
                    $return = $return & FALSE;
                }
                else {
                    $return = $return & TRUE;
                }
            }
            else {
                $this -> errores[$clave] = $this -> errores_msg[$clave];
                $return = $return & FALSE;
            }
        }   
    
        return $return;
    }   
    
    
    //valida que el mail sea correcto
    //@param string $key Nombre del campo email en el formulario
    //@return boolean
    
    public function validar_mail($key) {
        
        $address = $this -> pcampos[$key];
        
        if (function_exists('filter_var')) { //Introduced in PHP 5.2
            if(filter_var($address, FILTER_VALIDATE_EMAIL) === FALSE) 
            {
                $this->errores[$key] = $this -> errores_msg[$key];
                return false;
            } else 
            {
                return true;
            }
        } else 
        {
            if( preg_match('/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', $address))
            {
                return true;
            }
            else
            {
                $this->errores[$key] = $this -> errores_msg[$key];
                return false;
            }
        }
    }
    
    
    //Devuelve el valor de un campo del formulario
    //@param string $key Nombre del campo a devolver el valor
    //@return string
    
    public function get_value($key)
    {
        if(isset($this -> pcampos[$key])) return $this -> pcampos[$key];
    }   
    
    
    //Muestra todos los errores que ha tenido el formulario
    //@param void
    //@return array Listado de errores en el formulario
    
    public function list_errors($lang = 'es')
    {       
        //$error = new message();
        foreach($this -> errores as $error):
            echo "<div class='form-error'>"; echo $error->get_message($lang)."</div>";
        endforeach;
    }
    
    
    //Devuelve el error de un campo del formulario
    //@param string $key Nombre del campo a devolver el error
    //@return string Error del campo
    
    public function list_error($key, $lang='es')
    {
        if(isset($this -> errores[$key])) return $this -> errores[$key]->get_message($lang);
    }
} 

?>