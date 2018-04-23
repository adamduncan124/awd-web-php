<?
/*
Project: AWD Framework
Created By Adam Duncan
Date: 04/18/2018

Sqlbuilders
this is a class that allows for the sql being passed to connection to be correct based on the type
*/
namespace AWD\Interfaces{
	interface iSqlBuilder{
		public $tableName;
		public $statusFilter = "status = '1'";		
		public $tableJoins;
		public $selectList; //used to filter only a specific list (null ignore and pass star)
		public $alterList; //used to filter only a specific list
		public $orderBy;		
		public $limit;
		public $offset;
		
		public function InsertSql($aAlteredDataRow){
			$statementFields = "";
			$statementValues = "";
					
			foreach($aAlteredDataRow as $key => $value){
				if((isset($this->alterList)) && (is_array($this->alterList)) && (!in_array($key, $this->alterList)))
						continue;
				
				if($statementFields != ""){
					$statementFields .= ",";
					$statementValues .= ",";
				}
				
				$statementFields .= "`$key`";
				$statementValues .= $value;
			}
			
			return sprintf("insert into %s (%s) values (%s)", $tableName, $statementFields, $statementValues);
		}
		
		public function UpdateSql($aAlteredDataRow, $id){
			$statementValues = "";
			
			foreach($aAlteredDataRow as $key => $value){
				if((isset($this->alterList)) && (is_array($this->alterList)) && (!in_array($key, $this->alterList)))
						continue;				
				
				if($statementFields != ""){
					$statementFields .= ",";
				}
				
				$statementFields .= "`$key`=$value";
			}
			
			return sprintf("update %s set %s where id = '%s'", $this->tableName, $statementFields, $id);
		}
		
		public function DeleteSql(){
			return "update `" . $this->tableName . "` set status='0' where id = '$id'";
		}
		
		public function SelectSql($obj){
			$add_sql = "";
			
			if(isset($obj)){
				if ($obj instanceof TableFilters){
					$add_sql = $this->BuildSqlByFilter($obj);
				elseif(is_int($obj))
					$add_sql = "id = '$id' and ";
				else
					return $obj; //it must be custom sql if it gets here
			}
			
			return sprintf("select %s from %s where %s%s%s"
					,$this->BuildSelectStatementByTables()
					,$this->BuildSqlTableStatement()
					,$this->statusFilter
					,$add_sql
					,$this->OrderBy());
		}
		
		
		private function BuildTableJoins(){
			$sql = "";
			
			if(isset($this->tableJoins)){
				if(is_array($this->tableJoins)){	
					foreach($this->tableJoins as $key => $join){
						if($join instanceof TableJoin)
							$sql .= " " . $join->ReturnJoinString();
						else
							$sql .= " " . $join;
					}
				}else{
					$sql .= " " . $this->tableJoins->ReturnJoinString();	
					if($this->tableJoins instanceof TableJoin)
						$sql .= " " . $this->tableJoins->ReturnJoinString();
					else
						$sql .= " " . $join;
				}
			}
			
			return $sql;
		}
			
		private function BuildSqlTableStatement(){
			return "`" . $this->tableName . "`" . $this->BuildTableJoins();
		}
		
		private function BuildSelectStatementByTables(){
			$sql = "";
			
			if(isset($this->selectList)){
				foreach($this->selectList as $key => $select){
					if($sql!="")
						$sql .= ",";
					
					$sql .= "`" . $this->tableName . "`." . $select;
				}
			}else{
				$sql = "`" . $this->tableName . "`.*";
			}
			
			$sql .= $this->BuildTableJoins()
			
			return $sql;	
		}
		
		private function OrderBy(){
			$orderClause = " order by ";	
			$defaultOrderBy = "id desc";
			
			if($this->orderBy instanceof TableSorting){
				if($this->orderBy->HasSorting())
					$orderClause .= $this->orderBy->ReturnSortingString();
				else
					$orderClause .= $defaultOrderBy;
			}elseif(is_array($this->orderBy)){
				$orderClause .= implode(", ", $this->orderBy);
			}else{
				$orderClause .= isset($this->orderBy) ? $this->orderBy : $defaultOrderBy;	
			}				
			
			if(isset($this->limit))
				$orderClause .= " limit " . $this->limit;
			
			if(isset($this->offset))
				$orderClause .= " offset " . $this->offset;
			
			return $orderClause;
		}
	}
}