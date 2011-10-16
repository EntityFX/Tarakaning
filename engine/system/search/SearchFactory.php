<?php

set_include_path($_SERVER["DOCUMENT_ROOT"]."/engine/system/zend_search/");
require_once 'Zend/Search/Lucene.php';

class SearchFactory 
{
	protected $_sIndexDirPath = "engine/indexes/";
	protected $_index;
	protected $_arIndexFields = array();
	protected $_arTableIndexName = "";
	protected $_sIndexIdField = "pk";
	protected $_iHitsCount;
	protected $_arSearchResults = array();
	
	
	public function __construct() 
	{
		$this->_sIndexDirPath .= $this->_arTableIndexName;
		$this->_index = $this->GetSearchIndex();
		//$path="engine/modules/".$this->controller->_moduleType.'/'.ModuleController::XML_CONFIG_NAME;
		//$xmlConfig=new Zend_Config_Xml($path,$node);
		
	}
	
	public function getSearchIndex() 
	{
		setlocale(LC_ALL, 'ru_RU.utf-8'); 
		Zend_Search_Lucene_Analysis_Analyzer::setDefault(
		    new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8_CaseInsensitive()
		);
		Zend_Search_Lucene_Search_QueryParser::setDefaultEncoding('utf-8');
		try 
		{
	    	return Zend_Search_Lucene::open($_SERVER['DOCUMENT_ROOT']."/".$this->_sIndexDirPath);
		} 
		catch( Exception $e) 
		{
		    return Zend_Search_Lucene::create($_SERVER['DOCUMENT_ROOT']."/".$this->_sIndexDirPath);
		}
	}
	
	/**
	 * Adding fields and ones values to index
	 * @param integer $iPk
	 * @param array $arFields
	 */
	public function add($arFields) 
	{
		$doc = new Zend_Search_Lucene_Document();
		$iPk = intval($iPk);
		$doc->addField(Zend_Search_Lucene_Field::UnIndexed("pk", $arFields[$this->_arIndexFields[0]], 'utf-8'));
		foreach ($this->_arIndexFields as $key => $fieldName)
		{
			if ($fieldName != $this->_arIndexFields[0])
			{
				//precho($fieldName);
				$doc->addField(Zend_Search_Lucene_Field::unStored($fieldName, mb_convert_encoding($arFields[$fieldName],'utf-8'), 'utf-8'));
			}
		}
		$this->_index->addDocument($doc);
		$this->_index->commit();
	}
	
	/**
	 * Поиск в индексе
	 * @param string $sSearch
	 * @param array $arFields
	 */
	public function search($sSearch, $arFields = null) 
	{
		if ($arFields != null) 
		{//поиск по выбранным полям
			foreach ($arFields as $field)
			{
				$query .= $field.':('.$sSearch.') ';
			}
			$query = Zend_Search_Lucene_Search_QueryParser::parse($query, "utf-8");
		}
		else
		{//поиск по всем полям
			$query = Zend_Search_Lucene_Search_QueryParser::parse($sSearch, "utf-8");
		}
		$hits = $this->_index->find($query);
		$this->_iHitsCount = count($hits);
		if ($this->_iHitsCount > 0)
		{
			foreach ($hits as $hit)
			{
				$document = $hit->getDocument();
				$this->_arSearchResults[] = $document->pk;
			}
		}
	}
	
	/**
	 * Получить название папки/таблицы индекса
	 */
	public function getTableIndexName()
	{
		return $this->_arTableIndexName;
	}
	
	/**
	 * Получить количество результатов поиска
	 */
	public function getCountHits() 
	{
		return $this->_iHitsCount;
	}
	
	public function delete() 
	{
		;
	}
	
	public function serviseAddAll($arData) 
	{
		$i =0;
		foreach ($arData as $key => $value) 
		{
			$this->Add($value); $i++; 
			//if ($i == 5) break;
		}
		//$this->_index->optimize();
	}
}
function precho($s,$die = 0) 
{
	echo "<pre>";
	var_dump($s);
	echo "</pre>";
	$die == 1 ? die(): FALSE;
}
?>