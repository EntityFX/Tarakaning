<?php
	require_once SOURCE_PATH.'engine/system/AEnum.php';
	
	class SearchFieldTypeENUM extends AEnum
	{
		const BINARY 		= "binary";
		const KEYWORD 		= "keyword";
		const TEXT 			= "text";
		const UNINDEXED 	= "unIndexed";
		const UNSTORED 		= "unStored";
	}