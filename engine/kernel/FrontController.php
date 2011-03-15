<?php
/**
 * Файл с классом Kernel. Основной класс, который необходим для работы сайта.
 * @package kernel
 * @author Solopiy Artem
 * @version 0.9 Beta
 * @copyright Developers Team (Solopiy Artem, Jusupziyanov Timur) © 2010
 */
/**
 * Подключает загрузчик модулей
 * @filesource engine/kernel/ModuleLoader.php
 */
require_once SOURCE_PATH."engine/kernel/ModuleLoader.php";

/**
 * Подключает класс для работы с БД
 * @filesource engine/libs/mysql/MySQL.php
 */
require_once SOURCE_PATH."engine/libs/db/mysql/MySQL.php";

require_once SOURCE_PATH."engine/libs/db/DBConnector.php";

/**
 * Подключает Smarty с настройками
 * @filesource engine/kernel/SmartyExst.php
 */
require_once SOURCE_PATH."engine/kernel/SmartyExst.php";

/**
 * Подключает класс выводимых ссылок
 * @filesource engine/kernel/Menu.php
 */
require_once SOURCE_PATH."engine/kernel/Menu.php";

require_once "ISingleton.php";

/**
 * Используется для разбора URL, распознавания параметров, загрузки модулей, перенаправления на страницы
 * @package kernel
 * @author Solopiy Artem
 */
class FrontController extends DBConnector implements ISingleton
{
	private $url;


	/**
		* Содержит разбитый по знаку "/" URL
		*
		* @var Array
		*/
	private $_urlArray;

	/**
		* Параметры
		*
		* @var Array[String]
		*/
	private $_parameters;

	private $_useParameters;

	/**
		* Выходные данные для вывода
		*
		* @var Array[Mixed]
		*/
	private $_out;

	private $_arr;

	static private $_encoding="win-1251";

	static private $_instance=null;

	/**
		* Конструктор класс. Парсит URL адрес и испраляет ошибочный URL.
		*/
	protected function __construct()
	{
		parent::__construct();
		$this->url=substr($_SERVER['REQUEST_URI'],1);
		$this->_urlArray[]="/";
		$this->_urlArray=array_merge($this->_urlArray,explode("/",$this->url));
		$endStr=end($this->_urlArray);
		$questionPos=strpos($endStr,"?");
		$lastNode=$this->_urlArray[count($this->_urlArray)-1];
		//var_dump($questionPos,$newURL,$endStr,$this->_urlArray);
		if (($lastNode=="" && $questionPos===false) || $questionPos===0)
		{
			array_pop($this->_urlArray);
		}
		else
		{
			$startStr=substr($endStr, 0,$questionPos);
			$endStr=substr($endStr, $questionPos);
			if ($questionPos!==false)
			{
				$this->_urlArray[count($this->_urlArray)-1]=$startStr;
			}
			else 
			{
				$endStr="";
			}
			
			$newURL="/".implode("/",array_slice($this->_urlArray,1))."/";
			//var_dump($questionPos,$newURL,$endStr,$this->_urlArray);
			header("Location: $newURL$endStr");
		}
	}

	/**
	 * Получение одиночного объекта
	 *
	 */
	static public function &getInstance()
	{
		if (self::$_instance==null)
		{
			self::$_instance=new FrontController();
			return self::$_instance;
		}
		return self::$_instance;
	}

	/**
		* Запускает выполнение перехода на разделы, а также поиск и запуск соответствующего модуля
		*/
	public function run()
	{
		try
		{
			$this->urlScaner();
		}
		catch(Exception $ex)
		{
			if ($ex->getCode()==404)
			{
				$moduleType=0;
			}
		}
		header("Content-type: text/html; charset=\"".self::$_encoding."\"");
		$moduleType=(int)$this->_arr["module"];
		$data=$this->makeGet();
		$module=new ModuleLoader($moduleType,$data);
		$this->_out=$module->getOutput();
		if ($this->_out["title"]=="")
		{
			$this->_out["title"]=SITE_NAME.": ".$this->_out["title"].$this->_arr["title_tag"];
		}
		else
		{
			$this->_out["title"]=SITE_NAME.": ".$this->_out["title"];
		}
	}

	/**
		* Выводит дамп URL строки ввиде массива элементов пути
		*
		*/
	public function showURL()
	{
		var_dump($this->urlArray);
	}

	/**
		* Отображает сайт на главном шаблоне
		*
		* @param Sring $templatePath Выводимый модуль в папке engine/templates
		*/
	public function view($templatePath)
	{
		$smarty=new SmartyExst();
		//$smarty->caching=true;
		//$smarty->debugging=true;
		//var_dump($out);
		$smarty->assign("TEXT_VAR",$this->_out["text"]);
		$smarty->assign("TITLE",$this->_out["title"]);
		$menu=new LinksList($this->_arr["id"]);
		$smarty->assign("MENU",$menu->makeMenu());
		$smarty->assign("CHILDREN_MENU",$menu->getMenuChildren());
		$smarty->assign("PATH",$menu->getPath());
		$subSections=$menu->getSubSection();
		$smarty->assign("SUB_SECTIONS",$subSections);
		try
		{
			$smarty->display($templatePath);
		}
		catch (Exception $ex)
		{
			echo("PROBLEM SMARTY >> KERNEL >> Can't load $templatePath");
		}
	}

	private function &makeGet()
	{
		$data["id"]=$this->_arr["id"];
		$data["pid"]=$this->_arr["pid"];
		$data["urlArray"]=&$this->_urlArray;
		$data["parameters"]=&$this->_parameters;
		$data["url"]="/".$this->url;
		$data["isParameters"]=$this->_parameters;
		$data["title"]=$this->_arr["title"];
		return $data;
	}

	/**
		* Сканирует URL как разделы.
		* @throws Exception Раздел не найден
		*/
	private function urlScaner()
	{
		$pid=0;
		$flag=false;
		foreach($this->_urlArray as $key => $value)
		{
			if (!$flag)
			{
				$queryWhere="`link`='$value' AND `pid`=$pid";
				$this->_sql->SelAllWhere("URL",$queryWhere);
				$arr=$this->_sql->getTable();
				$this->_arr=$arr[0];
				$pid=$arr[0]["id"];
				if ($arr==NULL)
				{
					throw new Exception("Error",404);
				}
				else if ($arr[0]["use_parameters"]==1)
				{
					$flag=true;
				}
			}
			else
			{
				$this->_parameters[]=$value;
			}
		}
		$this->_useParameters=$flag;
	}

	public static function setGlobalEncoding($encoding)
	{
		self::$_encoding=$encoding;
	}
}
?>
