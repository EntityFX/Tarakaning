<?
/**
* Файл с классом ModuleLoader. Основной класс, который необходим для работы сайта.
* @package kernel
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Developers Team (Solopiy Artem, Jusupziyanov Timur)
*/


require_once 'ModuleController.php'; 

require_once 'Loader.php';   

require_once SOURCE_PATH."engine/system/db/DBConnector.php"; 

	/**
	* Выполняет загрузку модулей и передаёт управление им
	* @package kernel
	* @author Solopiy Artem 
	*/

	class ModuleLoader
	{
		/**
		* Константа содержит по-умолчанию путь к модулям
		*/
		const MODULE_PATH="engine/modules/";
		
		/**
		* Выходные данные
		* 
		* @var mixed
		*/
		private $_output;
		
		/**
		* Входные данные
		* 
		* @var mixed
		*/
		private $_data;
		
		/**
		* ID модуля
		* 
		* @var Integer
		*/
		private $_moduleID=null;
	
		private $_sql;
		
		
		/**
		* Конструктор
		* 
		* @param Integer $type Тип модуля
		* @param Array $data Массив с передаваемыми данными в модуль
		* @return ModuleLoader
		*/
		public function __construct($type,&$data=NULL)
		{
            $dbConnection=DBConnector::getInstance();
			$this->_sql=$dbConnection->getDB();
            $this->loadModule($type,$data);
		}
		
		/**
		* Загружает модуль и передаёт ему управление
		* 
		* @param Integer $type ID модуля
		* @param Array $data передаваемые данные
		* @throws Exception Ффйл модуля не существует
		*/
		public function loadModule($type,&$data)    
		{
			$type=(int)$type;
			$this->_sql->selAllWhere("Modules", "moduleId=$type");
			$array=$this->_sql->getTable();
			$array=$array[0];
			if ($array==null)
			{
				throw new Exception("ENGINE: Module (id=$type) is not declared");
			}
			$this->_moduleID=$array["moduleId"];
			$fullPath=SOURCE_PATH.ModuleLoader::MODULE_PATH.$array["path"]."/".$array["path"]."Controller.php";           
            Loader::setModulePath(SOURCE_PATH."engine/modules/".$array["path"].'/');
            $className=$array["path"]."Controller"; 
			if (file_exists($fullPath))
			{
				require_once($fullPath);
                $modInfo=$this->getModuleData();
                $data=array_merge($data,$modInfo);
                $controller=new $className($data);
			}
			else
			{
				throw new Exception("ENGINE: Module (id=$this->_moduleID) controller with class ".$className." FOR $fullPath IS NOT EXSIST");
			}
			$this->_output=$output;
			return $output;
		}
		
		/**
		* Возвращает ID моуля
		* 
		* @return Integer
		*/
		public function getModuleID()
		{
			return (int)$this->_moduleID;    
		}
        
        private function getModuleData()
        {
            $this->_sql->SelAllWhere("Modules","moduleId=".$this->_moduleID);
            $dat=$this->_sql->getTable();
            return array("name" => $dat[0]["name"],"descr" => $dat[0]["descr"],"moduleID" => $dat[0]["moduleId"],"type" =>$dat[0]["path"]);  
        }
		
		/**
		* Возвращает массив данных после работы модуля
		*
		* @return Array[Mixed] 
		*/
		public function getOutput() 
		{
			return $this->_output;
		}
	}
?>
