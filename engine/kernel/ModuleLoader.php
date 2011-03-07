<?
/**
* ���� � ������� ModuleLoader. �������� �����, ������� ��������� ��� ������ �����.
* @package kernel
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur)
*/


require_once 'PageController.php'; 

require_once 'classLoader.php';   

require_once SOURCE_PATH."engine/libs/db/DBConnector.php"; 

	/**
	* ��������� �������� ������� � ������� ���������� ��
	* @package kernel
	* @author Solopiy Artem 
	*/

	class ModuleLoader extends DBConnector
	{
		/**
		* ��������� �������� ��-��������� ���� � �������
		*/
		const MODULE_PATH="engine/modules/";
		
		/**
		* �������� ������
		* 
		* @var mixed
		*/
		private $_output;
		
		/**
		* ������� ������
		* 
		* @var mixed
		*/
		private $_data;
		
		/**
		* ID ������
		* 
		* @var Integer
		*/
		private $_moduleID=null;
		
		/**
		* �����������
		* 
		* @param Integer $type ��� ������
		* @param Array $data ������ � ������������� ������� � ������
		* @return ModuleLoader
		*/
		public function __construct($type,&$data=NULL)
		{
            parent::__construct();
            $this->loadModule($type,$data);
		}
		
		/**
		* ��������� ������ � ������� ��� ����������
		* 
		* @param Integer $type ID ������
		* @param Array $data ������������ ������
		* @throws Exception ���� ������ �� ����������
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
				throw new Exception("ENGINE: Module (id=$this->_moduleID) controller with class ".$className." FOR $fullPath MODULE IS NOT EXSIST");
			}
			$this->_output=$output;
			return $output;
		}
		
		/**
		* ���������� ID �����
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
		* ���������� ������ ������ ����� ������ ������
		*
		* @return Array[Mixed] 
		*/
		public function getOutput() 
		{
			return $this->_output;
		}
	}
?>