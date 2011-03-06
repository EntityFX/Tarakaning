<?
/**
* Файл с классом ModuleLoader. Основной класс, который необходим для работы сайта.
* @package kernel
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur)
*/


require_once 'PageController.php'; 

require_once 'classLoader.php';   

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
		private $_moduleID;
        
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
            try
            {
                $this->_sql=MySQL::getInstance(DB_SERVER,DB_USER,DB_PASSWORD); 
                $this->_sql->selectDB(DB_NAME);
                $result=$this->_sql->query("SELECT `path`,`moduleId` FROM `Modules` WHERE `moduleid`=$type"); 
            }
            catch (Exception $dbError)
            {
                throw new Exception("MODULE LOADER ERROR: CHECK DB CONNECTION");
            }
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
			$array=$this->_sql->fetchArr();
			$this->_moduleID=$array["moduleId"];
			$fullPath=ModuleLoader::MODULE_PATH.$array["path"]."/".$array["path"]."Controller.php";           
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
				throw new Exception("ENGINE: File with class ".$className." FOR $fullPath MODULE IS NOT EXSIST");
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
            return array("name" => $dat[0]["name"],"descr" => $dat[0]["descr"],"moduleID" => $dat[0]["moduleId"]);  
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