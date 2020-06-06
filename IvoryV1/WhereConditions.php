<?php

class WhereConditions {

    public function __construct()
    {
        
    }

    public function findCondition($value)
    {
        $currentCondition = $this->isEqual($value);

        if (isset($value['isNot']) && $value['isNot']) {
            $currentCondition = $this->isNot($value);
        }

        if (isset($value['isIn']) && is_array($value['isIn'])) {
            $currentCondition = $this->isIn($value);
        }
        
        if (isset($value['like']) && $value['like']) {
            $currentCondition = $this->like($value);
        }

        if (isset($value['isBetween']) && $value['isBetween'] 
        && isset($value['first-comparative']) && isset($value['second-comparative'])) {
            $currentCondition = $this->between($value);
        }

        if (isset($value['isAny']) && is_string($value['isAny'])) {
            $currentCondition = $this->isAny($value);
        }

        if (isset($value['isAll']) && is_string($value['isAll'])) {
            $currentCondition = $this->isAll($value);
        }

        if (isset($value['exists']) && is_string($value['exists'])) {
            $currentCondition = $this->exists($value);
        }

        return $currentCondition;
    }

    public function isEqual($value)
    {
        $equal = " = ";

        if (isset($value['equal']) && !$value['equal']) {
            $equal = " != ";
        }

        return $value['propName'] . $equal . $value['fieldValue'];
    }

    public function isAnd($value)
    {
        $and = " AND ";
        if (isset($value['isAnd']) && !$value['isAnd']) {
            $and = " OR ";
        }
        return $and;
    }

    public function isNot($value)
    {
        return $value['propName'] . " NOT " . $value['fieldValue'];
    }

    public function isIn($value){
        $strIn = "";
        foreach ($value['isIn'] as $k => $inElement) {
            $strIn .= $inElement;
            if ($k == count($value['isIn'])-1) {
                break;
            }
            $strIn .= ", ";
        }

        return $value['propName'] . " IN ($strIn) ";
    }

    public function like($value)
    {
        $pattern = "%or%";
        if (isset($value['pattern'])) {
            $pattern = $value['pattern'];
        }

        return $value['propName'] . " LIKE " . $pattern;
    }

    public function between($value)
    {
        $firstComparative = $value['first-comparative'];
        $secondComparative = $value['second-comparative'];
        $comparison = " BETWEEN $firstComparative AND $secondComparative ";
        return $value['propName'] . $comparison;
    }

    public function isAny($value)
    {
        return $value['propName'] . " = ANY (" . $value['isAny'] . " )";;
    }

    public function isAll($value)
    {
        return $value['propName'] . " = ALL (" . $value['isAll'] . " )";
    }

    public function exists($value)
    {
        return " EXISTS (" . $value['exists'] . " )";
    }
}
?>