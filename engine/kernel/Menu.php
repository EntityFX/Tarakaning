<?
/**
* Файл с классом LinksList.
* @package kernel  
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Developers Team (Solopiy Artem, Jusupziyanov Timur) © 2010 
*/
	
	/**
	* Выполняет роль построения списка ссылок основного меню, список ссылок от корневого раздела к текущему,
	* список подразделов
	* @package kernel
	* @author Solopiy Artem   
	* @final
	*/
	final class Menu extends DBConnector
	{
		public function __construct()
		{
			parent::__construct();
		}
		
		public function addLink($url,$title)
		{
			$title=htmlspecialchars($title);	
			$this->_sql->query("CALL addToMainMenu('$url','$title',".$this->getIdByURL($url).",'LAST',NULL);");
		}
		
		public function deleteLink($id)
		{
			$id=(int)$id;
			$this->_sql->query("CALL deleteFromMainMenu($id);");
		}
		
		public function deleteBySection($sectionID)
		{
			$sectionID=(int)$sectionID;
			$this->_sql->delete("MainMenu", "sectionID=$sectionID");
		}
		
		public function editBySectionID($sectionId,$newUrl)
		{
			$sectionId=(int)$sectionId;
			$this->_sql->update("MainMenu","sectionID=$sectionId",
					new ArrayObject(array(
						"url" => $newUrl,
					)) 
				);
		}
		
		public function editLink($id,$newUrl,$newTitle)
		{
			$id=(int)$id;
			$newTitle=htmlspecialchars($newTitle);
			if ($this->checkIfExsist($id))
			{
				$this->_sql->update("MainMenu","id=$id",
					new ArrayObject(array(
						"url" => $newUrl,
						"title"  => $newTitle,
						"sectionID" => $this->getIdByURL($newUrl)
					)), 
				$newFieldValues);
			}
			else
			{
				throw new Exception("Menu is not exsist");	
			}	
		}
		
		public function getAll()
		{
			$this->_sql->setOrder(new MenuFieldsOrder(MenuFieldsOrder::POSITION), new MySQLOrderENUM(MySQLOrderENUM::ASC));
			$this->_sql->selAll("MainMenu");
			$this->_sql->clearOrder();
			return $this->_sql->getTable();	
		}
		
		public function getByID($id)
		{
			if ($this->checkIfExsist($id))
			{
				$this->_sql->selAllWhere("MainMenu","id=$id");
				$resArr=$this->_sql->getTable();
				return $resArr[0];
			}
			else
			{
				throw new Exception("Menu is not exsist");
			}
		}
		
		public function moveUp($id)
		{
			$id=(int)$id;
			$this->_sql->query("CALL moveMenuUp($id)");
		}
		
		public function moveDown($id)
		{
			$id=(int)$id;
			$this->_sql->query("CALL moveMenuDown($id)");
		}
		
		public function checkIfExsist($id)
		{
			$id=(int)$id;
			$count=$this->_sql->countQuery("MainMenu","id=$id");
			return (boolean)$count;
		}
		
	    public function getIdByURL($url)
        {
			$arrURL[]="/";
			$arrURL=array_merge($arrURL,explode("/",$url));
			$pid=0;
			$flag=false;
			foreach($arrURL as $key => $value)
			{
				if (!$flag && $value!=="")
				{
					$queryWhere="`link`='$value' AND `pid`=$pid";
					$this->_sql->SelAllWhere("URL",$queryWhere);
					$arr=$this->_sql->getTable();
					$pid=$arr[0]["id"];
					if ($arr==NULL)
					{
						return null;
					}
					else if ($arr[0]["use_parameters"]==1)
					{
						return (int)$pid;
					}
				}
			}
			return (int)$pid;
        }
		
	}
	
	final class MenuFieldsOrder extends AEnum
	{
		const POSITION="position";
	}
?>
