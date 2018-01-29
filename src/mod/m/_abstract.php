<?php
namespace m;

abstract class _abstract
{
	private $_dataconnector;
	private $_id;
	public $key;
	
	public static function fetch($Filter) {
		echo __CLASS__;
		// $return = new __CLASS__();
		// $return->score = 15;
		// return $return;
	}
	
	public function __construct ($filter)
	{
		$this->_dataconnector = new connectors\pdoMysqlite();
		// echo __CLASS__;
		$this->_id = $filter;
	}
}