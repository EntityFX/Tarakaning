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
			$url=urlencode($url);
			$title=htmlspecialchars($title);
			$this->_sql->insert("MainMenu",
				new ArrayObject(array(
					0,
					$url,
					$title
				)) 
			);	
		}
		
		public function deleteLink($id)
		{
			$this->_sql->delete("MainMenu", "id=$id");	
		}
		
		public function editLink($id,$newUrl,$newTitle)
		{
			$id=(int)$id;
			$newUrl=urlencode($url);
			$newTitle=htmlspecialchars($newTitle);
			$this->_sql->update("MainMenu","id=$id",
				new ArrayObject(array(
					"url" => $newUrl,
					"title"  => $newTitle
				)), 
			$newFieldValues);	
		}
		
		public function getAll()
		{
			$this->_sql->selAll("MainMenu");
			return $this->_sql->getTable();	
		}
		
		public function checkIfExsist($id)
		{
			$id=(int)$id;
			$count=$this->_sql->countQuery("MainMenu","id=$id");
			return (boolean)$count;
		}
		
	}
?>
