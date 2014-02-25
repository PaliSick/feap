<?

/**
 * Autoload Class for auto-loading classes files.
 */
class AutoLoad {

	public static $loader;
	private $paths = array('app/class/', 'app/inc/');
	private $filePatterns = array('%s.class.php', 'class.%s.php');

	public static function init() {

		if ($loader == NULL)
			$loader = new self();

		return self::$loader;
	}

	public function __construct() {

		spl_autoload_register(array($this, 'autoLoad'));
	}

	public function autoLoad($className) {
		foreach ($this->paths as $path) {
			foreach ($this->filePatterns as $fileName) {
				$fName = strtolower($path . sprintf($fileName, $className));

				if (is_file($fName)) {


					require_once ($fName);
					break;
				}
			}
		}
	}

}

//We should init it to this get to work
AutoLoad::init();