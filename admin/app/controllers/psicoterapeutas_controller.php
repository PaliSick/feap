<?
class psicoterapeutasController extends BaseController {
	
	public function __construct($basePath) {
		//Llamamos al parent construct
		parent::__construct($basePath);

		//Seteamos el Title y el BreadCrumb
		switch(Router::getActionSeo()){
			case 'listado': $this->tpl->assign('bread_action', 'Listado'); break;
			case 'nuevos': $this->tpl->assign('bread_action', 'Nuevo'); break;
			case 'carga-datos': $this->tpl->assign('bread_action', 'Carga de datos'); break;
			default: $this->tpl->assign('bread_action', 'Default');
		}
		$this->tpl->assign('bread_section', 'Factura');
	}
	
	public function listado() {
		//Setting the menu
			$this->tpl->assign('bread_action', 'Listado');
			$paginado = '';
		
			$params = Router::getParams();

			$p = array();
			for($i = 0, $l = count($params); $i<$l; $i = $i + 2) {
				$p[$params[$i]] = urldecode($params[$i+1]);
			}

			if (!empty($p['q']) && $p['q'] != '*')
				$filter= $p['q'];

			if (empty($p['page'])) $p['page'] = 1;
			

			
			if (Router::getParam(1) == 'alert') {

				$msgType = Router::getParam(2);
				$msg = Router::getParam(3);
				if ($msgType && $msg) {
					$this->tpl->assign('msg', $msg);
					$this->tpl->assign('msgType', $msgType);
				}
		
			}
			$this->tpl->assign('menu', array('1' =>' class="selected"'));
	
			$this->tpl->assign('path', Router::getPath());
			$this->tpl->assign('psicoterapeutas', $this->model->getPsicoterapeutas(2,$filter, $from, $to, $p['page'] , $paginado));
			$this->tpl->assign('paginado', $paginado);
			
			echo $this->renderAction("psicoterapeutas/lista");

	}



}