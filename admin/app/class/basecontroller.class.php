<?
class baseController {

	private $includes = array();
	public $tpl = null;

	public function __construct($baseDir) {
		$controllerName = $this->getControllerName();

		$this->baseDir = $baseDir;

		#Model Load
		$modelFile = 'app/models/'.$controllerName.'_model.php';

		if (is_file($modelFile)) {
			require $modelFile;
			$modelClass = $controllerName.'Model';
			$this->model = new $modelClass();
		} /*else {
			//Si no hay modelo, cargamos unicamente la config del sitio
			$config = new ConfigClass();
			$this->model->db->cfg = $config->cfg;
		}*/

		#bootstrappers Load
		//$this->include_all_once('app/bootstraps/*.php');

		#Helper Load
		$helperFile = 'app/helpers/'.$controllerName.'_helper.php';
		if (file_exists($helperFile)) require $helperFile;

		#Template Load
		$this->tpl = new RainTPL();
		#Set Global Variables
		$this->tpl->configure( 'base_url', 'http://' . $_SERVER['HTTP_HOST'] . $this->baseDir .'/');
		$this->tpl->assign(array(
			'title' => ucwords(str_replace('-', ' ', Router::getControllerSeo())),
			'controller' => Router::getControllerSeo(),
			'action'	=> Router::getActionSeo(),
			'domain'	 => $_SERVER['HTTP_HOST'],
			'base_path'	 => $this->baseDir
		));

		return true;
	}

	public function getControllerName() {
		return str_replace('Controller', '', get_class($this));
	}
	public function renderAction($action)
	{
		return $this->tpl->draw($action, $return_string=true);
	}
	/*function renderIncludes($template) {

		foreach($this->includes as $inc => $file) {
			$includeContent = (file_exists($file)) ? @file_get_contents($file) : '_missing_template_';
			$template = str_replace('#[include_'.$inc.'];', $includeContent, $template);
		}

		return $template;

	}*/

	/*function renderAction($action) {
		$actionTemplateFile = 'app/views/'.$action.'.tpl';
		$actionTemplate = file_get_contents($actionTemplateFile);

		$res = $this->renderIncludes($actionTemplate);
		$c = 0;
		while (preg_match('/#\{[^}]*\}/si', $res) && $c < 3) {
			$res = $this->evaluateObject($this->data, $res);
			$c++;
		}

		preg_match_all('/(src|action|href){1}="([^"]*)"/sim', $res, $result, PREG_PATTERN_ORDER);

		for ($i = 0; $i < count($result[2]); $i++) {
			if (!preg_match('%https?://%im', $result[2][$i]))
				$res = str_replace("\"".$result[2][$i]."\"", "\"".$this->baseDir.'/'.$result[2][$i]."\"", $res);
		}
		return $res;
		//return preg_replace('/<link(.*)href="([^"]*)"([^>]*)>/i', '<link$1href="'.$this->baseDir.'/$2"$3>', $res);
	}
	*/
	function evaluateObject($obj, $template) {
		if (!is_object($obj)) return 'a';

		#iterate all Bool values
		foreach($obj as $varName => $value) {
			if (is_bool($value)) $template = $this->evalConditionals($varName, $value, $template);
		}

		#iterate all Array values
		foreach($obj as $varName => $value) {
			if (is_array($value)) $template = $this->evalArray($varName, $value, $template);
		}

		#Then the single values
		foreach($obj as $varName => $value) {
			$template = str_replace('#{'.$varName.'}', $value, $template);
		}

		return $template;
	}

	function evalConditionals($varName, $value, $template)
	{

		if (preg_match('/<%\?'.$varName.'\?(.*?)<%\?else\?%>(.*?)\?%>/s', $template, $regs)) {
			if ($value === true)
				$template = str_replace($regs[0], $regs[1], $template);
			 else
				$template = str_replace($regs[0], $regs[2], $template);
		}

		return $template;
	}

	function evaluateArray($array_replace, $template) {
		if (!is_array($array_replace)) return false;

		#iterate all Array values
		foreach($array_replace as $varName => $value) {
			if (is_array($value)) {
				//echo $varName;

				$template = $this->evalArray($varName, $value, $template);
				//echo '<textarea name="" id="" cols="30" rows="10">'.$template.'</textarea>';
			}
		}

		#iterate all Bool values
		foreach($array_replace as $varName => $value) {
			if (is_bool($value)) $template = $this->evalConditionals($varName, $value, $template);
		}

		#Then the single values
		foreach($array_replace as $varName => $value) {
			$template = str_replace('#{'.$varName.'}', $value, $template);
		}

		return $template;
	}

	function evalArray($arrayName, $data, $template) {
		//echo '<textarea name="" id="" cols="30" rows="10">'.$template.'</textarea>';
		/*if (preg_match('/<%'.$arrayName.'(\\|([0-9]*))?%(.*?)%>/s', $template, $regs)) {
		if (preg_match('/<%'.$arrayName.'(\\|([0-9]*))?%((?>[^%<>]+)|(?R))*%>/sm', $template, $regs)) {*/
		if (preg_match(
			'/\('.$arrayName.'(\|([0-9]+))?%(
			        (
			            ([^()]+)
			        |
			            (
			             \([\w]+(\|([0-9]+))?%([^()]+)\)
			            )
			        )*
			)\)
			/sx',
			$template, $regs)) {

			//echo $arrayName;
			$miniTemplate = $regs[3];
			$limitTemplate = (!empty($regs[2])) ? $regs[2] : false;
			$template = preg_replace('/\('.$arrayName.'(\|([0-9]+))?%(
			        (
			            ([^()]+)
			        |
			            (
			             \([\w]+(\|([0-9]+))?%([^()]+)\)
			            )
			        )*
			)\)
			/sx', '#{'.$arrayName.'Result}', $template);
		} else {
			#if couldn't parse the array on the template the return it untouched
			return $template;
		}

		$arrayResult = '';
		$i = 0;
		foreach($data as $key => $value) {
			if ($limitTemplate  && ($limitTemplate == $i++)) break;
			$arrayResult.= $this->evaluateArray($value, $miniTemplate);
		}

		$res = $this->evaluateArray(array($arrayName.'Result' => $arrayResult), $template);
		return $res;
	}
	/*
	function include_all_once($pattern) {
	    foreach (glob($pattern) as $file) { // remember the { and } are necessary!
	        require_once $file;
	    }
	}
	*/



}