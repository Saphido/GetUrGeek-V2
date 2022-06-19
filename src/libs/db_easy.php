<?php

//-----------------------------------------------------------------------------------------------------------------
/**
 *      Author : Julien Jacobs
 *      Modified by : Julien Gatisseur
 */
//-----------------------------------------------------------------------------------------------------------------

/**
 * SELECT `id`, `pseudo`, `mail`, `pass` FROM `joueur` WHERE id=1
 * PDO, table, element[], nameForWhere[], valueForWhere[].
 * Pour les WHERE, ajouter "' ... '" S'il s'agit d'un string.
 */
function selectInTable($pdo, $table, $elements, $wheresName, $wheresValue, $LogicOperator)
{
    $sql = 'SELECT ';
    if (count($elements) > 0) {
        for ($i = 0; $i < count($elements); $i++) {
            $sql = $sql . '`' . $elements[$i] . '`';
            if ($i < count($elements) - 1) {
                $sql = $sql . ', ';
            }
        }
    } else {
        $sql = $sql . ' * ';
    }
    $sql = $sql . ' FROM `' . $table . '`';
    if (count($wheresName) > 0) {
        $sql = $sql . ' where ';
        for ($i = 0; $i < count($wheresName); $i++) {
            $sql = $sql . $wheresName[$i] . ' = ' . $wheresValue[$i];
            if ($i < count($wheresName) - 1) {
                $sql = $sql . ' ' . $LogicOperator . ' ';
            }
        }
    } else {
        $sql = $sql . ' WHERE 1';
    }
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    } catch (PDOException $e) {
        die($e->getMessage());
    }
    return $stmt;
}

function selectInTableWithCount($pdo, $table, $count, $as, $wheresName, $wheresValue, $LogicOperator)
{
    $sql = 'SELECT COUNT(' . $count . ') as ' . $as . ' ';
    $sql = $sql . ' FROM `' . $table . '`';
    if (count($wheresName) > 0) {
        $sql = $sql . ' where ';
        for ($i = 0; $i < count($wheresName); $i++) {
            $sql = $sql . $wheresName[$i] . ' = ' . $wheresValue[$i];
            if ($i < count($wheresName) - 1) {
                $sql = $sql . ' ' . $LogicOperator[$i] . ' ';
            }
        }
    } else {
        $sql = $sql . ' WHERE 1';
    }
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    } catch (PDOException $e) {
        die($e->getMessage());
    }
    return $stmt;
}

function selectInTableWithOrderAndLimit($pdo, $table, $elements, $wheresName, $wheresValue, $LogicOperator, $orderName, $sortBy, $limitNumber)
{
    $sql = 'SELECT ';
    if (count($elements) > 0) {
        for ($i = 0; $i < count($elements); $i++) {
            $sql = $sql . '`' . $elements[$i] . '`';
            if ($i < count($elements) - 1) {
                $sql = $sql . ', ';
            }
        }
    } else {
        $sql = $sql . ' * ';
    }
    $sql = $sql . ' FROM `' . $table . '`';
    if (count($wheresName) > 0) {
        $sql = $sql . ' where ';
        for ($i = 0; $i < count($wheresName); $i++) {
            $sql = $sql . $wheresName[$i] . ' = ' . $wheresValue[$i];
            if ($i < count($wheresName) - 1) {
                $sql = $sql . ' ' . $LogicOperator . ' ';
            }
        }
    } else {
        $sql = $sql . ' WHERE 1';
    }


    if (!empty($orderName)) {
        $sql = $sql . ' ORDER BY ' . $orderName . ' ' . $sortBy;
    }
    if (!empty($limitNumber)) {
        $sql = $sql . ' LIMIT ' . $limitNumber;
    }
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    } catch (PDOException $e) {
        die($e->getMessage());
    }
    return $stmt;
}

function selectInTableOperator($pdo, $table, $elements, $wheresName, $wheresValue, $LogicOperator)
{
    $sql = 'SELECT ';
    if (count($elements) > 0) {
        for ($i = 0; $i < count($elements); $i++) {
            $sql = $sql . '`' . $elements[$i] . '`';
            if ($i < count($elements) - 1) {
                $sql = $sql . ', ';
            }
        }
    } else {
        $sql = $sql . ' * ';
    }
    $sql = $sql . ' FROM `' . $table . '`';
    if (count($wheresName) > 0) {
        $sql = $sql . ' where ';
        for ($i = 0; $i < count($wheresName); $i++) {
            $sql = $sql . $wheresName[$i] . ' = ' . $wheresValue[$i];
            if ($i < count($wheresName) - 1) {
                $sql = $sql . ' ' . $LogicOperator[$i] . ' ';
            }
        }
    } else {
        $sql = $sql . ' WHERE 1';
    }
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    } catch (PDOException $e) {
        die($e->getMessage());
    }
    return $stmt;
}


/**
 * 
 */
function selectInTableImprovedSigned($pdo, $table, $elements, $wheresName, $wheresValue, $sign)
{
    $sql = 'SELECT ';
    if (count($elements) > 0) {
        for ($i = 0; $i < count($elements); $i++) {
            $sql = $sql . '`' . $elements[$i] . '`';
            if ($i < count($elements) - 1) {
                $sql = $sql . ', ';
            }
        }
    } else {
        $sql = $sql . '* ';
    }
    $sql = $sql . ' FROM `' . $table . '`';
    if (count($wheresName) > 0) {
        $sql = $sql . ' where ';
        for ($i = 0; $i < count($wheresName); $i++) {
            if ($sign[$i] == '=') {
                $sql = $sql . $wheresName[$i] . ' IN (';
                for ($j = 0; $j < count($wheresValue[$i]); $j++) {
                    $sql = $sql . $wheresValue[$i][$j];
                    if ($j < count($wheresValue[$i]) - 1) {
                        $sql = $sql . ', ';
                    }
                }
                $sql = $sql . ') ';
            } else {
                $sql = $sql . $wheresName[$i] . $sign[$i] . $wheresValue[$i][0] . ' ';
            }
            if ($i < count($wheresName) - 1) {
                $sql = $sql . 'AND ';
            }
        }
    } else {
        $sql = $sql . ' WHERE 1';
    }
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    } catch (PDOException $e) {
        die($e->getMessage());
    }
    return $stmt;
}

/**
 * 
 */
function selectInTableImproved($pdo, $table, $elements, $wheresName, $wheresValue)
{

    $sql = 'SELECT ';
    if (count($elements) > 0) {
        for ($i = 0; $i < count($elements); $i++) {
            $sql = $sql . '`' . $elements[$i] . '`';
            if ($i < count($elements) - 1) {
                $sql = $sql . ', ';
            }
        }
    } else {
        $sql = $sql . '* ';
    }
    $sql = $sql . ' FROM `' . $table . '`';
    if (count($wheresName) > 0) {
        $sql = $sql . ' where ';
        for ($i = 0; $i < count($wheresName); $i++) {
            $sql = $sql . $wheresName[$i] . ' IN (';
            for ($j = 0; $j < count($wheresValue[$i]); $j++) {
                $sql = $sql . $wheresValue[$i][$j];
                if ($j < count($wheresValue[$i]) - 1) {
                    $sql = $sql . ', ';
                }
            }
            $sql = $sql . ') ';
            if ($i < count($wheresName) - 1) {
                $sql = $sql . 'AND ';
            }
        }
    } else {
        $sql = $sql . ' WHERE 1';
    }
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    } catch (PDOException $e) {
        die($e->getMessage());
    }
    return $stmt;
}


/**
 * INSERT INTO `joueur`(`id`, `pseudo`, `mail`, `pass`) VALUES ('[value-1]','[value-2]','[value-3]','[value-4]')
 * PDO, table, elementsName[], elementsValue[]
 */
function insertInTable($pdo, $table, $elementsName, $elementsValue)
{
    $sql = 'INSERT INTO `' . $table . '` (';
    for ($i = 0; $i < count($elementsName); $i++) {
        $sql = $sql . '`' . $elementsName[$i] . '`';
        if ($i < count($elementsName) - 1) {
            $sql = $sql . ', ';
        }
    }
    $sql = $sql . ') VALUES (';
    for ($i = 0; $i < count($elementsValue); $i++) {
        $sql = $sql . "'" . $elementsValue[$i] . "'";
        if ($i < count($elementsValue) - 1) {
            $sql = $sql . ', ';
        }
    }
    $sql = $sql . ')';

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    } catch (PDOException $e) {
        die($e->getMessage());
    }
}

/**
 * UPDATE `joueur` SET `id`='[value-1]',`pseudo`='[value-2]',`mail`='[value-3]',`pass`='[value-4]' WHERE id=1
 * PDO, table, elementsName[], elementsValue[], nameForWhere[], valueForWhere[]
 * Pour les WHERE, ajouter "' ... '" S'il s'agit d'un string.
 */
function updateInTable($pdo, $table, $elementsName, $elementsValue, $wheresName, $whereValue)
{
    $sql = 'UPDATE ' . '`' . $table . '` SET ';
    for ($i = 0; $i < count($elementsName); $i++) {
        $sql = $sql . '`' . $elementsName[$i] . '`=\'' . $elementsValue[$i] . '\'';
        if ($i < count($elementsName) - 1) {
            $sql = $sql . ', ';
        }
    }
    $sql = $sql . ' WHERE ';
    if (count($wheresName) > 0) {
        for ($i = 0; $i < count($wheresName); $i++) {
            $sql = $sql . $wheresName[$i] . '=' . $whereValue[$i];
            if ($i < count($wheresName) - 1) {
                $sql = $sql . ' AND ';
            }
        }
    } else {
        $sql = $sql . '0';
    }
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    } catch (PDOException $e) {
        die($e->getMessage());
    }
}

/**
 * DELETE FROM `joueur` WHERE id=0
 * PDO, table, nameForWhere[], valueForWhere[]
 * Pour les WHERE, ajouter "' ... '" S'il s'agit d'un string.
 */
function deleteInTable($pdo, $table, $wheresName, $wheresValue)
{
    $sql = 'DELETE FROM `' . $table . '` WHERE ';
    if (count($wheresName) > 0) {
        for ($i = 0; $i < count($wheresName); $i++) {
            $sql = $sql . $wheresName[$i] . '=' . $wheresValue[$i];
            if ($i < count($wheresName) - 1) {
                $sql = $sql . ' AND ';
            }
        }
    } else {
        $sql = $sql . '0';
    }
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    } catch (PDOException $e) {
        die($e->getMessage());
    }
}

/**
 * Retourne un élément HTML générer en String.
 * typeBalise : 'p', 'h1', 'button'...
 * textInside : String
 * attributeName : ['attr1', 'attr2'];
 * attributeValue : ['val1', 'val2'];
 */
function generateHTML($typeBalise, $textInside, $attributeName, $attributeValue)
{
    $balise = '<' . $typeBalise . ' ';
    if (count($attributeName) > 0) {
        for ($i = 0; $i < count($attributeName); $i++) {
            $balise = $balise . $attributeName[$i] . '="' . $attributeValue[$i] . '"';
        }
    }
    $balise = $balise . '>' . $textInside . '</' . $typeBalise . '>';
    return $balise;
}


function valeursEntre($valeur, $min, $max)
{
    for ($i = 0; $i < sizeof($valeur); $i++) {
        if ($valeur[$i] > $max || $valeur[$i] < $min) {
            return false;
        }
    }
    return true;
}

function verifyLiked($pdo, $userId, $userOtherId)
{
    $likes = selectInTable($pdo, 'liked', ['id'], ['idUserLike', 'idUserLiked'], [$userId, $userOtherId], 'AND');
    return $likes->fetch() != null;
}

function verifyMatch($pdo, $id, $id2)
{
    return verifyLiked($pdo, $id, $id2) && verifyLiked($pdo, $id2, $id);
}

function verifyAlreadyMatched($pdo, $userId, $userOtherId) {
    $verifyMatch = selectInTableOperator($pdo, 'matches', ['id'], ['idUser1', 'idUser2', 'idUser1', 'idUser2'], [$userId, $userOtherId, $userOtherId, $userId], ['AND', 'OR', 'AND']);
    return $verifyMatch->fetch() != null;
}
function convertStringToDB($string)
{
    return str_replace("'", "\'", $string);
}

function calculDateToString($datediff)
{
    $years = $datediff->format('%Y');

    $month = $datediff->format('%m');
    $day = $datediff->format('%d');

    $hours = $datediff->format('%H');
    $minutes = $datediff->format('%i');
    $seconds = $datediff->format('%s');

    if ($years != '0') {
        if ($years == '1') {
            return "Match " . $years . " year ago";
        }
        return "Match " . $years . " years ago";
    }

    if ($month != '0') {
        if ($month == '1') {
            return "Match " . $month . " month ago";
        }
        return "Match " . $month . " months ago";
    }

    if ($day != '0') {
        if ($day == '1') {
            return "Match " . $day . " day ago";
        }
        return "Match " . $day . " days ago";
    }

    if ($hours != '0') {
        if ($hours == '1') {
            return "Match " . $hours . " hour ago";
        }
        return "Match " . $hours . " hours ago";
    }
    if ($minutes != '0') {
        if ($minutes == '1') {
            return "Match " . $minutes . " minute ago";
        }
        return "Match " . $minutes . " minutes ago";
    }

    if ($seconds != '0') {
        if ($seconds == '1') {
            return "Match " . $seconds . " second ago";
        }
        return "Match " . $seconds . " seconds ago";
    }
}

function dateMessage($date)
{
    $aujourdhui = date("Y-m-d H:i:s");
    $dates = date_create($date);
    $datediff = date_diff($dates, date_create($aujourdhui));

    $years = $datediff->format('%Y');
    $month = $datediff->format('%m');
    $day = $datediff->format('%d');

    if ($years == '0' && $month == '0' && $day == '0') {
        return $dates->format('H:i') . ', Today';
    } else if ($years == '0' && $month == '0' && $day == '1') {
        return $dates->format('H:i') . ', Yesterday';
    } else {
        return $dates->format('H:i d-m-Y');
    }
}
