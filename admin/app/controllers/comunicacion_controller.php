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

}