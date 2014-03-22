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
		$this->tpl->assign('section',0);//con uno vuelve al index del controlador si tiene
		$this->tpl->assign('bread_section', 'Psicoterapeutas');
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
		
		if(!empty($p['miembro']) && $p['miembro']>0 ){
			$this->tpl->assign('miembro', $p['miembro']);
			$miembro=$p['miembro'];
		}

		if(!empty($p['prov']) && $p['prov']>0){
			$this->tpl->assign('provincia', $p['prov']);
			$prov=$p['prov'];
		}

		if(!empty($p['seccion']) && $p['seccion']>0){
			$this->tpl->assign('seccion', $p['seccion']);
			$seccion=$p['seccion'];
		}

		if(!empty($p['estado']) && $p['estado']>0){
			$this->tpl->assign('estado', $p['estado']);
			$estado= $p['estado'];
		}
		if ($p['excel'] != '1') {
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
			$this->tpl->assign('miembros', $this->model->getMiembros());
			$this->tpl->assign('secciones', $this->model->getSecciones());
			$this->tpl->assign('provincias', $this->model->getProvincias());
			$this->tpl->assign('psicoterapeutas', $this->model->getPsicoterapeutas($estado, $filter, $miembro, $prov, $seccion,  $p['page'] , $paginado, 0));
			$this->tpl->assign('paginado', $paginado);			
			echo $this->renderAction("psicoterapeutas/lista");
		}else{
			$data = array();


			$psicologos=$this->model->getPsicoterapeutas($estado, $filter, $miembro, $prov, $seccion,  0 , $paginado, 1);

			require_once "app/inc/class.writeexcel_workbook.inc.php";
			require_once "app/inc/class.writeexcel_worksheet.inc.php";

			$fname = tempnam("/tmp", "simple.xls");
			$workbook = &new writeexcel_workbook($fname);
			$worksheet = &$workbook->addworksheet();

			$header =& $workbook->addformat();
			$header->set_bold();
			$header->set_size(12);
			$header->set_color('blue');

			$worksheet->write(0, 0, 'Nº', $header);	
			$worksheet->write(0, 1, 'Psicoterapeuta', $header);
			$worksheet->write(0, 2, 'Opciones', $header);
			$worksheet->write(0, 3, 'Provincia', $header);
			$worksheet->write(0, 4, 'Localidad', $header);

			# Write some numbers

			foreach($psicologos as $i=> $item) {
				$worksheet->write($i+1, 0,  iconv('UTF-8', 'ISO-8859-1', $item['id']));        
				$worksheet->write($i+1, 1,  iconv('UTF-8', 'ISO-8859-1',$item['apellido'].', '.$item['nombre']) );   
				$worksheet->write($i+1, 2,  iconv('UTF-8', 'ISO-8859-1', $item['opciones']));    
				$worksheet->write($i+1, 3,  iconv('UTF-8', 'ISO-8859-1', $item['provincia'])); 
				$worksheet->write($i+1, 4, iconv('UTF-8', 'ISO-8859-1', $item['localidad'])); 

			}
			$workbook->close();

			header("Content-Type: application/x-msexcel; name=\"psicoterapeutas.xls\"");
			header("Content-Disposition: inline; filename=\"psicoterapeutas.xls\"");
			$fh=fopen($fname, "rb");
			fpassthru($fh);
			unlink($fname);				
		}

	}


	public function news()
	{
		$id = Router::getParam(0);

		if($id>0){
			$this->tpl->assign($this->model->getDatosAlbaran($id));
			$this->tpl->assign('productos', $this->model->getProductosAlbaran($id));
			$paginado = '';
			$this->tpl->assign('products', $this->model->getProductos($_SESSION['s_sql'], 1, $paginado));
			$this->tpl->assign('paginado', $paginado);
		}

		$this->tpl->assign('menu', array("2"=>' class="selected"'));
		$this->tpl->assign('provincias', $this->model->getProvincias());
		$this->tpl->assign('opciones', $this->model->getOpciones());
		$this->tpl->assign('id_opcion', array(0=>5,1=>3,2=>8,3=>7));
		$this->tpl->assign('cantMiembros', array(0=>array("id_opcion"=>5),1=>array("id_opcion"=>8),2=>array("id_opcion"=>3)));
		print_r(array(0=>array("id_opcion"=>5),1=>array("id_opcion"=>8),2=>array("id_opcion"=>3)));
		echo $this->renderAction("psicoterapeutas/news");
	}


}