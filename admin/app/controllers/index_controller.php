<?
class indexController extends BaseController {

	public function index() {
		if ($_SESSION['c_autorized'] != 1) {
			header("Location: /admin/index/login");
			return true;
		}
		
		$this->tpl->assign('bread_section', 'Inicio');
		
		echo $this->renderAction("index/index");
	}

	public function login()
	{
		if($_SESSION['sd_cont_ip']>=3){
			 echo 'Ha intentado ingresar incorrectamente, su ip: '.$_SESSION['sd_ip_addres'].' ha sido baneada, comuniquese con el Administrador. Disculpe';
			 $log = new KLogger('logs/banned/', KLogger::DEBUG);
			 $log->logInfo('IP:'.$_SESSION['sd_ip_addres']);
			  exit;
		}
		echo $this->renderAction("index/login");
	}
	
	public function login_submit()
	{
		if($this->model->login()){
		//if (strcmp($_POST['user'],  $cfg['admin_user']) == 0 && strcmp(sha1($_POST['pass']), $cfg['admin_pass']) == 0) {
			session_start();
			$_SESSION['c_autorized'] = 1;
			header("Location: /admin");
			echo 'Espere por favor...';
		} else {
			header("Location: /admin/index/login/error");
		}
	}

	public function salir()
	{
		session_destroy();
		echo '{"status": "ok", "info": "Saliendo"}';
	}

}