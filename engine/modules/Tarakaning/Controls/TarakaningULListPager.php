<?php
Loader::LoadSystem('controls','Pager/ULListPager');

class TarakaningULListPager extends ULListPager
{
	const SIZE=5;
	const PAGINATOR_SIZE=5;
	
	public function __construct($count,$get="page")
	{
		parent::__construct($count,$get,self::SIZE,self::PAGINATOR_SIZE);
        $this->setCurrentTag('a');
        $this->setCurrentLiClass('active');
	}
}