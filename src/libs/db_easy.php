<?php

//-----------------------------------------------------------------------------------------------------------------
/**
 *      Author : Julien Jacobs
 *      Modified by : Julien Gatisseur :3
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
    for ($i = 0; $i < count($elements); $i++) {
        $sql = $sql . '`' . $elements[$i] . '`';
        if ($i < count($elements) - 1) {
            $sql = $sql . ', ';
        }
    }
    $sql = $sql . ' FROM `' . $table . '`';
    if (count($wheresName) > 0) {
        $sql = $sql . ' where ';
        for ($i = 0; $i < count($wheresName); $i++) {
            $sql = $sql . $wheresName[$i] . ' = ' . $wheresValue[$i];
            if ($i < count($wheresName) - 1) {
                $sql = $sql . $LogicOperator . ' ';
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


function valeursEntre($valeur, $min, $max){
    for($i = 0; $i < sizeof($valeur); $i++){
        if($valeur[$i]> $max || $valeur[$i]<$min){
            return false;
        }
    }
    return true;
}