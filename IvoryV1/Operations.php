<?php
include("config.php");
require_once("WhereConditions.php");

class Operations{

    private $dbConection;
    private $whereConditions; 

    public function __construct()
    {
        $this->dbConection = $GLOBALS['db'];
        $this->whereConditions =  new WhereConditions();
    }

    public function select(array $fields, array $tableNames)
    {

        $strFields = "";
        foreach ($fields as $key => $value) {
           $strFields .= $value;
           if ($key == count($fields)-1) {
                break;
           }
           $strFields .= ", ";
        }

        $tables = "";
        foreach ($tableNames as $key => $value) {
            $tables .= $value;
            if ($key == count($tableNames)-1) {
                break;
            }
            $tables .= ", ";
        }
        return " SELECT $strFields FROM $tables ";

    }

    public function update(string $tableName, array $properties)
    {

        $updateFields = "";
        $count = 0;

        foreach ($properties as $key => $value) {
            $count += 1;
            if (is_string($value)) {
                $value = "'$value'";
            }
            
            $updateFields .= "$key = $value";
            if ($count == count($properties)) {
                break;
            }
            $updateFields .= ", ";
        }
        return " UPDATE $tableName SET $updateFields ";
    }

    public function delete(string $tableName)
    {

        return " DELETE FROM $tableName ";
    }

    public function insert(string $tableName, array $fieldsAndValues)
    {
        $strFields = "";
        $strValues = "";
        $count = 0;

        foreach ($fieldsAndValues as $key => $value) {

            $count += 1;
            if (is_string($value)) {
                $value = "'$value'";
            }

            $strFields .= "$key";
            $strValues .= "$value";

            if ($count == count($fieldsAndValues)) {
                break;
            }

            $strFields .= ", ";
            $strValues .= ", ";
        }

        return " INSERT INTO $tableName ($strFields) VALUES ($strValues)";
    }

    public function where(string $statement, array $whereProperties)
    {
        $whereCondition = "";
        foreach ($whereProperties as $key => $value) {
           
            $currentCondition = $this->whereConditions->findCondition($value);

            $and = $this->whereConditions->isAnd($value);

            $whereCondition .= $currentCondition; 
            
            if ($key == count($whereProperties)-1) {
                break;
            }

            $whereCondition .= $and;

        }

        return $statement . " WHERE $whereCondition ";
    }

    public function order(string $query,string $fieldName,bool $isDesc = true)
    {
        $orderType = "DESC";
        if (!$isDesc) {
            $orderType = "ASC";
        }
        return $query . " ORDER BY $fieldName $orderType ";
    }

    public function limit(string $query,int $maxRegisters)
    {
        return $query . " LIMIT $maxRegisters ";
    }

    public function groupBy(string $query, array $columnNames)
    {
        $strColumns = "";
        foreach ($columnNames as $key => $value) {
            $strColumns .= $value;

            if ($key == count($columnNames)-1) {
                break;
            }

            $strColumns .= ", ";
        }
        return $query . " GROUP BY $strColumns ";
    }

    public function having(string $query, string $havingCondition)
    {
        return $query . " HAVING $havingCondition ";
    }

    public function innerJoin(string $query, string $tableNameJoin, string $propertyNameTable1, string $propertyNameTable2)
    {
        return $this->join("INNER", $query, $tableNameJoin, $propertyNameTable1, $propertyNameTable2);
    }

    public function leftJoin(string $query, string $tableNameJoin, string $propertyNameTable1, string $propertyNameTable2)
    {
        return $this->join("LEFT", $query, $tableNameJoin, $propertyNameTable1, $propertyNameTable2);
    }

    public function rightJoin(string $query, string $tableNameJoin, string $propertyNameTable1, string $propertyNameTable2)
    {
        return $this->join("RIGHT", $query, $tableNameJoin, $propertyNameTable1, $propertyNameTable2);
    }

    public function fullOuterJoin(string $query, string $tableNameJoin, string $propertyNameTable1, string $propertyNameTable2)
    {
        return $this->join("FULL OUTER", $query, $tableNameJoin, $propertyNameTable1, $propertyNameTable2);
    }

    private function join(string $typeJoin, string $query, string $tableNameJoin, string $propertyNameTable1, string $propertyNameTable2)
    {
        return $query . " $typeJoin JOIN $tableNameJoin ON $propertyNameTable1 = $propertyNameTable2 ";
    }

    public function runQuery($query)
    {
        if(preg_match("/\bselect\b/i", $query)){
            $result = $this->dbConection->query($query);

        }else{
            
            $preparedStatement = $this->dbConection->prepare($query);
            $preparedStatement->execute();
            $result = false;
            if ($this->dbConection->errorInfo()) {
                $result = true;
            }
        }
        
        return $result;
    }
}

?>