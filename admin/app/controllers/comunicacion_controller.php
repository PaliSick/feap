<?
class comunicacionController extends BaseController {

	public function __construct($basePath) {

		parent::__construct($basePath);

		//Seteamos el Title y el BreadCrumb
		switch(Router::getActionSeo()){
			case 'grupos': $this->tpl->assign('bread_action', 'Listado grupos'); break;
			case 'grupos-new': $this->tpl->assign('bread_action', 'Nuevo grupo'); break;
			case 'boletines': $this->tpl->assign('bread_action', 'Listado boletines'); break;
			case 'boletines-news': $this->tpl->assign('bread_action', 'Nuevo boletin'); break;
			default: $this->tpl->assign('bread_action', 'Default');
		}
		$this->tpl->assign('section',0);//con uno vuelve al index del controlador si tiene
		$this->tpl->assign('bread_section', 'Comunicación');
	}


	public function grupos()
	{
		if (Router::getParam(0) == 'alert') {
			$msgType = Router::getParam(1);
			$msg = Router::getParam(2);
			if ($msgType && $msg) {
				$this->tpl->assign('msg', $msg);
				$this->tpl->assign('msgType', $msgType);
			}
			
		} 
		$this->tpl->assign('menu', array("4"=>' class="selected"'));
		$this->tpl->assign('grupos', $this->model->getGrupos());
		echo $this->renderAction('comunicacion/grupos');
	}

	public function edit_grupo()
	{
		$error = '';
		$id = Router::getParam(0);
		if ($id>0)			
			echo str_replace('\\/', '/', json_encode($this->model->getGrupo($id,$error), JSON_HEX_TAG | JSON_HEX_QUOT | JSON_HEX_APOS));
		else
			echo '{"status": "error", "info": "'.$error.'"}';		
	}

	public function grupo_submit() {
		$msg = '';

		if (empty($_POST['grupo'])) {
			echo '{"status": "error", "info": "El campo grupo  es obligatorio"}';
			return false;
		}

		$r = $this->model->editGrupo($msg);

		if ($r === true) echo '{"status": "ok", "info": " Grupo: '.$_POST['grupo'].' ingresado correctamente"}';
					else echo '{"status": "error", "info": "'.$msg.'"}';

	}

	public function contactos()
	{
		if (Router::getParam(0) == 'alert') {
			$msgType = Router::getParam(1);
			$msg = Router::getParam(2);
			if ($msgType && $msg) {
				$this->tpl->assign('msg', $msg);
				$this->tpl->assign('msgType', $msgType);
			}			
		} 

		$paginado = '';	
		$params = Router::getParams();
		$p = array();
		for($i = 0, $l = count($params); $i<$l; $i = $i + 2) {
			$p[$params[$i]] = urldecode($params[$i+1]);
		}

		if (!empty($p['q']) && $p['q'] != '*')
			$filter= $p['q'];

		if (!empty($p['email']) && $p['email'] != '*')
			$email= $p['email'];

		if (!empty($p['grupo']) && $p['grupo'] != '0')
		$grupo = $p['grupo'];

		$this->tpl->assign('menu', array("5"=>' class="selected"'));
		$this->tpl->assign('contactos', $this->model->getContactos($filter, $email, $grupo,  $p['page'] , $paginado));
		$this->tpl->assign('grupos',$this->model->getGrupos());
		$this->tpl->assign('paginado', $paginado);	
		$this->tpl->assign('search_params', $p);
		echo $this->renderAction('comunicacion/contactos');		
	}

	public function contacto_submit() {
		$msg = '';

		if (empty($_POST['nombre'])) {
			echo '{"status": "error", "info": "El campo nombre  es obligatorio"}';
			return false;
		}
		if (empty($_POST['email'])) {
			echo '{"status": "error", "info": "El campo email  es obligatorio"}';
			return false;
		}
		if ($_POST['grupo']==0) {
			echo '{"status": "error", "info": "El campo grupo  es obligatorio"}';
			return false;
		}
		$r = $this->model->editContacto($msg);

		if ($r === true) echo '{"status": "ok", "info": " Contacto: '.$_POST['nombre'].' ingresado correctamente"}';
					else echo '{"status": "error", "info": "'.$msg.'"}';

	}

}