<?
//chdir('../');
/*
include 'class/class.phpmailer.php';
include 'class/class.smtp.php';
*/
class Emailer
{
	
	private $emailer;
	private $error;
	private $buffer;
	
	function __construct() {
		$this->emailer = new PHPMailer(true);
		$this->emailer->IsSMTP();
		$this->emailer->CharSet = 'UTF-8';
		$this->emailer->Encoding = 'base64';
		$this->buffer = array();
		
		$this->emailer->Host = 'mail.vinooferta.com';
		$this->emailer->Port = '25';
		$this->emailer->Username = 'notificaciones@vinooferta.com';
		$this->emailer->Password = 'HBtIz]pT3ZJc';
		$this->emailer->SMTPAuth = true;
		
		
		
	}
	
	function sendEmail($src, $src_name, $dst, $dst_name, $subject, $content, $replyTo = false, $replyTo_name = '', $alt = false)
	{
		//Sender
		$this->emailer->SetFrom($src, $src_name);
		
		//Destination
		$this->emailer->AddAddress($dst, $dst_name); 
		
		//Reply
		if ($replyTo) {
			$this->emailer->ClearReplyTos();
			$this->emailer->AddReplyTo($replyTo, $replyTo_name);
		}
		
		//Subject
		$this->emailer->Subject  = $subject;

		//Content
		$this->emailer->MsgHTML($content);
		if ($alt)
			$this->emailer->AltBody = $alt;

		try {
			$this->emailer->send();
			return true;
		} catch (Exception $e) {
			$this->error = $e->getMessage();
			return false;
		}
		/*if(!$this->emailer->Send()) echo 'Se produjo un error al enviar el email. Intentelo nuevamente mas tarde.';
		else echo 'Su mensaje ha sido enviado. Pronto nos pondremos en contacto con Usted.';*/
	}
	
	public function getError()
	{
		return $this->error;
	}
	
	public function lowStockNotification($item, $code, $stock)
	{
		$this->buffer[] = "<p><strong>[$code]</strong> - $item: quedan $stock unidades</p>";
	}
	
	public function sendLowStock($to, $to_name = '')
	{
		return $this->sendEmail(
				'notificaciones@vinooferta.com', 'Notificaciones VinoOferta.com', //FROM
				$to, $to_name,													  //TO
				'Productos con stocks bajos', 									  //SUBJECT
				
				"<p>Se registró un nuevo pedido en VinoOferta.com y hay productos que poseen bajo stock</p>
				<h2>Productos</h2>
				".join("\n", $this->buffer)
				);
	}
	
	public function sendFactura($articulos, $factura, $envio)
	{
		# code...
	}
	
}
