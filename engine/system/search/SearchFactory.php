<?php

set_include_path($_SERVER["DOCUMENT_ROOT"]."/engine/system/zend_search/");
require_once 'Zend/Search/Lucene.php';

abstract class SearchFactory 
{
	protected $_sIndexDirPath = "engine/indexes/";
	protected $_index;
	protected $_arIndexFields = array();
	protected $_arTableIndexName;
	protected $_sIndexIdField = "pk";
	protected $_iHitsCount;
	protected $_arSearchResults = array();
	
	private static $_locale="ru_RU";
	private static $_encoding="utf-8";
	
	/**
	 * 
	 * Çàäàòü ëîêàëèþ
	 * @param string $locale Íàçâàíèå ëîêàëèè
	 */
	public static function setLocale($locale)
	{
		self::$_locale=$locale;
	}
	
	/**
	 * 
	 * Çàäàòü êîäèðîâêó
	 * @param string $encoding Íàçâàíèå êîäèðîâêè
	 */
	public static function setEncoding($encoding)
	{
		self::$_encoding=$encoding;
	}
	
	/**
	 * 
	 * Óñòàíîâèòü èìÿ òàáëèöû èíäåêñà
	 * @param unknown_type $tableName
	 */
	public function setTableName($tableName)
	{
		$this->_arTableIndexName=$tableName;
	}
	
	public function __construct() 
	{
		$this->_sIndexDirPath .= $this->_arTableIndexName;
		$this->_index = $this->GetSearchIndex();
	}
	
	public function getSearchIndex() 
	{
		setlocale(LC_ALL, self::$_locale.'.'.self::$_encoding); 
		Zend_Search_Lucene_Analysis_Analyzer::setDefault(
		    new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8_CaseInsensitive()
		);
		Zend_Search_Lucene_Search_QueryParser::setDefaultEncoding(self::$_encoding);
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
		$iPk = (int)$iPk;
		$doc->addField(Zend_Search_Lucene_Field::UnIndexed("pk", $arFields[$this->_arIndexFields[0]], self::$_encoding));
		foreach ($this->_arIndexFields as $key => $fieldName)
		{
			if ($fieldName != $this->_arIndexFields[0])
			{
				//precho($fieldName);
				$doc->addField(Zend_Search_Lucene_Field::unStored($fieldName, mb_convert_encoding($arFields[$fieldName],self::$_encoding), self::$_encoding));
			}
		}
		$this->_index->addDocument($doc);
		$this->_index->commit();
	}
	
	/**
	 * ÐŸÐ¾Ð¸ÑÐº Ð² Ð¸Ð½Ð´ÐµÐºÑÐµ
	 * @param string $sSearch
	 * @param array $arFields
	 */
	public function search($sSearch, $arFields = null) 
	{
		if ($arFields != null) 
		{//Ð¿Ð¾Ð¸ÑÐº Ð¿Ð¾ Ð²Ñ‹Ð±Ñ€Ð°Ð½Ð½Ñ‹Ð¼ Ð¿Ð¾Ð»ÑÐ¼
			foreach ($arFields as $field)
			{
				$queryString .= $field.':('.$sSearch.') ';
			}
			$query = Zend_Search_Lucene_Search_QueryParser::parse($queryString, self::$_encoding);
		}
		else
		{//Ð¿Ð¾Ð¸ÑÐº Ð¿Ð¾ Ð²ÑÐµÐ¼ Ð¿Ð¾Ð»ÑÐ¼
			$query = Zend_Search_Lucene_Search_QueryParser::parse($sSearch, self::$_encoding);
		}
		var_dump($queryString);
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
	 * ÐŸÐ¾Ð»ÑƒÑ‡Ð¸Ñ‚ÑŒ Ð½Ð°Ð·Ð²Ð°Ð½Ð¸Ðµ Ð¿Ð°Ð¿ÐºÐ¸/Ñ‚Ð°Ð±Ð»Ð¸Ñ†Ñ‹ Ð¸Ð½Ð´ÐµÐºÑÐ°
	 */
	public function getTableIndexName()
	{
		return $this->_arTableIndexName;
	}
	
	/**
	 * ÐŸÐ¾Ð»ÑƒÑ‡Ð¸Ñ‚ÑŒ ÐºÐ¾Ð»Ð¸Ñ‡ÐµÑÑ‚Ð²Ð¾ Ñ€ÐµÐ·ÑƒÐ»ÑŒÑ‚Ð°Ñ‚Ð¾Ð² Ð¿Ð¾Ð¸ÑÐºÐ°
	 */
	public function getCountHits() 
	{
		return $this->_iHitsCount;
	}
	
	public function delete() 
	{
		;
	}
	
	public function serviceAddAll($arData) 
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
/*
function precho($s,$die = 0) 
{
	echo "<pre>";
	var_dump($s);
	echo "</pre>";
	$die == 1 ? die(): FALSE;
}
*/
?>