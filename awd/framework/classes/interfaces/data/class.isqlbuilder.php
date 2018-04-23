<?
/*
Project: AWD Framework
Created By Adam Duncan
Date: 04/18/2018

iSqlBuilder
this is an interface that forces all sql builder styles to follow. it goes together with iConnection
*/
namespace AWD\Interfaces{
	interface iSqlBuilder{
		public $tableName;
		public $statusFilter;		
		public $tableJoins;
		public $selectList;
		public $alterList;
		public $orderBy;		
		public $limit;
		public $offset;
		
		public function InsertSql();
		
		public function UpdateSql();
		
		public function DeleteSql();
		
		public function SelectSql($obj);
	}
}
?>