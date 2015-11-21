<?
/**
 * Boletin - Clase PHP para suscribir al boletin
 * @author Santi Bartolome
 * @version 0.1 (2012_10_18)
 * @package Enfusion Theme 
 */

class boletin{
	
	private $id;
	private $campo;

	
	public function __construct($id, $campo)
	{
		$this->id = $id;
		$this->campo = $campo;
	}
	
	public function send($nombre, $email, $return= NULL)
	{
		
		echo '<form id="" action="http://news.pombobox.com/RWCode/subscribe.asp?SiteID='.$this->id.'&Mode=subscribe&resize=0" method="post" name="SubscribeForm" novalidate>
			<input type="text" name="Col1"  value="'.$nombre.'" />
			<input type="email" name="Email"  value="'.$email.'"/>
			<input type="hidden" name="SID" value="7">
			<input type="hidden" name="ReturnURL" value="'.$return.'">
			<input type="hidden" name="Resize" value="1">
			<input type="hidden" name="Mode" value="">
			<input type="hidden" name="ResultMode" value="">
			<input type="hidden" name="HitID" value="0">
			<input type="hidden" name="OldEmail" value="">
			<input type="hidden" name="EmailChange" value="0">
			<input type="hidden" name="EmailID" value="0">
			<input type="hidden" name="'.$this->campo.'" value="on">	
		</form>
		<script>document.SubscribeForm.submit()</script>';
		
		return true;
	}
	
}

?>