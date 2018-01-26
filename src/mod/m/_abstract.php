<?php
namespace m;

abstract class _abstract
{
	private $id;
	public $key;
	
	public static function fetch($Filter) {
		echo __CLASS__;
		// $return = new __CLASS__();
		// $return->score = 15;
		// return $return;
	}
	
	public function __construct ($filter)
	{
		// echo __CLASS__;
		// $this->id = $filter;
	}
}