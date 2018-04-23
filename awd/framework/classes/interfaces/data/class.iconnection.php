<?
/*
Project: AWD Framework
Created By Adam Duncan
Date: 04/16/2018

iConnection
this is an interface for all database connections to follow
*/
namespace AWD\Interfaces\Data{
	interface iConnection{
		
		public function SelectDb($dbname);
		
		public function AddParameter($value);
		
		public function SetResults($sql);
		
		public function FetchRow();
		
		public function LoadTableColumns($table, $show_settings)
		
		public function ExecuteSql($sql);
		
		public function ExecuteScalar($sql);
		
		public function ReturnIdentity();
		
		public function Close();
		
	}
}
?>