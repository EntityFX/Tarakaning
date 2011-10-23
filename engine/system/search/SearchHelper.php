<?php
	require_once 'SearchFields.php';

	class SearchHelper
	{
		private static $_locale="ru_RU";
		private static $_encoding="UTF-8";
		
		private $_indexPath;
		
		private $_searchFields;
		
		public function __construct($fields=null,$searchPath=null)
		{

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
		    	return Zend_Search_Lucene::open($_SERVER['DOCUMENT_ROOT']."/".$this->getIndexPath());
			} 
			catch(Exception $e) 
			{
			    return Zend_Search_Lucene::create($_SERVER['DOCUMENT_ROOT']."/".$this->getIndexPath());
			}
		}
		
		/**
		 * 
		 * ������ �������
		 * @param string $locale �������� �������
		 */
		public static function setLocale($locale)
		{
			self::$_locale=$locale;
		}
		
		/**
		 * 
		 * ������ ���������
		 * @param string $encoding �������� ���������
		 */
		public static function setEncoding($encoding)
		{
			self::$_encoding=$encoding;
		}
		
		public function addToIndex(&$values)
		{
			if ($values!=null)
			{
				$doc = new Zend_Search_Lucene_Document();
				foreach ($values as $field => $value)
				{
					/*if (is_string($value))
					{
						var_dump(mb_detect_encoding($value));
						$value=mb_convert_encoding($value,self::$_encoding,'CP1251');
					}*/
					switch ($this->_searchFields[$field])
					{
						case SearchFieldTypeENUM::BINARY:
							$zendField=Zend_Search_Lucene_Field::binary($field, $value);
							break;
						case SearchFieldTypeENUM::KEYWORD:
							$zendField=Zend_Search_Lucene_Field::keyword($field, $value, self::$_encoding);
							break;
						case SearchFieldTypeENUM::TEXT:
							$zendField=Zend_Search_Lucene_Field::text($field, $value, self::$_encoding);
							break;
						case SearchFieldTypeENUM::UNINDEXED:
							$zendField=Zend_Search_Lucene_Field::unIndexed($field, $value, self::$_encoding);
							break;
						case SearchFieldTypeENUM::UNSTORED:
							$zendField=Zend_Search_Lucene_Field::unStored($field, $value, self::$_encoding);
							break;
					}
					$doc->addField($zendField);
				}
				$index=$this->getSearchIndex();
				$index->addDocument($doc);
			}
		}
		
		public function deleteFromIndex()
		{
			
		}
		
		public function updateIndex()
		{
			
		}
		
		public function search($searchQuery)
		{
			if (is_string($searchQuery))
			{
				$query = Zend_Search_Lucene_Search_QueryParser::parse($searchQuery, self::$_encoding);
			}
			else
			{
				$query = $searchQuery;
			}
			$index=$this->getSearchIndex();
			$hits=$index->find($query);
			return $hits;
		}
		
		/**
		 * Поиск в индексе
		 * @param string $sSearch
		 * @param array $arFields
		 */
	/*	public function search($sSearch, $arFields = null) 
		{
			if ($arFields != null) 
			{//поиск по выбранным полям
				foreach ($arFields as $field)
				{
					$queryString .= $field.':('.$sSearch.') ';
				}
				$query = Zend_Search_Lucene_Search_QueryParser::parse($queryString, self::$_encoding);
			}
			else
			{//поиск по всем полям
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
		}*/
		
		public function indexSize()
		{
			$index=$this->getSearchIndex();
			return $index->count();
		}
		
		public function setFields(SearchFields $fields)
		{
			$this->_searchFields=$fields;
		}
		
		public function getFields()
		{
			return $this->_searchFields;
		}
		
		public function setPath($indexPath)
		{
			$this->_indexPath=(string)$indexPath;
		}
		
		public function getIndexPath()
		{
			return $this->_indexPath;
		}
		
	}