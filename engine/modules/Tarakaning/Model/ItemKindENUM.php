<?php
    require_once SOURCE_PATH."engine/system/AEnum.php";
    
    final class ItemKindENUM extends AEnum
    {
    	const ALL        = "All";
    	const DEFECT     = "Defect";
        const TASK       = "Task";
    }  