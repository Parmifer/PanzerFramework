<?php

/*
 * Copyright (C) 2015 Yann
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

class PanzerDALGenerator
{
    private $attributes;
    private $requestableFieldCount;

    public function generateOneDAL($table, $relations, $folder)
    {
        // Usefull vars
        $className = PanzerStringUtils::convertTableEnNomClasse($table);
        $dalName = $className . 'DAL';
        $classCamelCase = PanzerStringUtils::convertBddEnCamelCase($table);
        $this->attributes = BaseSingleton::select('describe ' . $table);
        $pkField = $this->getPrimaryKeyFieldName();

        if (!empty($relations))
        {
            foreach ($relations as $uneRelation)
            {
                $this->attributes[] = array(
                    'Field' => $uneRelation['table'],
                    'Type' => PanzerStringUtils::convertTableEnNomClasse($uneRelation['table']),
                    'storage' => $uneRelation['storage'],
                    'Key' => (isset($uneRelation['clef_externe']) ? $uneRelation['clef_externe'] : null),
                    'DAL' => (isset($uneRelation['DAL']) ? $uneRelation['DAL'] : null)
                );
            }
        }

        $generatedClassFile = $folder . $dalName . '.php';
        $handle = fopen($generatedClassFile, 'w') or die('Cannot open file:  ' . $generatedClassFile);

        $newDAL = '<?php

/*
 * Copyright (C) ' . date('Y') . '
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

require_once(PanzerConfiguration::getProjectRoot().\'model/class/'.$className.'.php\');

class ' . $dalName . ' extends PanzerDAL
{
    /**
     * Returns the '.$className.' which the id match with the given id.
     *
     * @param int $id The id of the searched '.$className.'.
     * @return mixed A '.$className.'. Null if not found.
     */
    public static function findById($id)
    {
        $params = array(\'i\', &$id);
        $dataset = BaseSingleton::select(\'SELECT '.$this->getRequestableFields(true, true).' FROM '.$table.' WHERE '.$pkField.' = ?\', $params);

        return self::handleResults($dataset);
    }

    /**
     * Returns all the '.$className.'.
     *
     * @return mixed From zero to many '.$className.'.
     */
    public static function findAll()
    {
        $dataset = BaseSingleton::select(\'SELECT '.$this->getRequestableFields(true, true).' FROM '.$table.'\');

        return self::handleResults($dataset);
    }

    /**
     * Create or edit a '.$className.'.
     *
     * @param '.$className.' $'.$classCamelCase.'
     * @return int id of the '.$className.' inserted/edited in base. False if it didn\'t work.
     */
    public static function persist($'.$classCamelCase.')
    {';
        foreach($this->attributes as $attribut)
        {
            $nomObjet = PanzerStringUtils::convertBddEnCamelCase($attribut['Field']);

            if($attribut['Key'] !== 'MUL' && !isset($attribut['storage']))
            {
                $newDAL .= '
        $'.$nomObjet . ' = $'.$classCamelCase.'->get'.PanzerStringUtils::convertTableEnNomClasse($attribut['Field']) . '();';
            }
            else if(isset($attribut['storage']) && $attribut['storage'] == 'object')
            {
                $getPKfunction = 'get'. PanzerStringUtils::convertTableEnNomClasse($this->getPrimaryKeyFromDb($attribut['Field'])).'()';
                $newDAL .= '
        $'. $nomObjet . ' = $'.$classCamelCase.'->get'.PanzerStringUtils::convertTableEnNomClasse($attribut['Field']).'()->'.$getPKfunction.';';
            }
        }

        $newDAL .= '

        if ($'.PanzerStringUtils::convertBddEnCamelCase($pkField).' > 0)
        {
            $sql = \'UPDATE '.$table.' SET \'';

        $paramTypeString = '';
        $fieldToUpdate = '';

        foreach($this->attributes as $attribut)
        {
            if($attribut['Key'] === '' || $attribut['Key'] === 'MUL')
            {
                $fieldToUpdate .= '
                    .\''.$table.'.'.$attribut['Field'].' = ?, \'';
                $paramTypeString .= substr(PanzerSQLUtils::getPhpType($attribut['Type']), 0, 1);
            }
        }

        $fieldToUpdate = substr($fieldToUpdate, 0, -3);
        $fieldToUpdate .= ' \'';

        $newDAL.= $fieldToUpdate;
        $newDAL .= '
                    .\'WHERE '.$table.'.'.$pkField.' = ?\';';
        $newDAL .= '

            $params = array(\''.$paramTypeString.'i\',';

        foreach($this->attributes as $attribut)
        {
            $nomObjet = PanzerStringUtils::convertBddEnCamelCase($attribut['Field']);

            if($attribut['Key'] === '' || (isset($attribut['storage']) && $attribut['storage'] === 'object'))
            {
                $newDAL .= '
                &$'.$nomObjet.',';
            }
        }

        $newDAL .= '
                &$'.PanzerStringUtils::convertBddEnCamelCase($pkField).'
            );
        }
        else
        {
            $sql = \'INSERT INTO '.$table.' \'
                    . \'('.$this->getRequestableFields(false, false).') \'
                    . \'VALUES ('.$this->getNeededTokensString().')\';

            $params = array(\''.$paramTypeString.'\',';

        $variableList = '';

        foreach($this->attributes as $attribut)
        {
            $nomObjet = PanzerStringUtils::convertBddEnCamelCase($attribut['Field']);

            if($attribut['Key'] === '' || (isset($attribut['storage']) && $attribut['storage'] === 'object'))
            {
                $variableList .= '
                &$'.$nomObjet.',';
            }
        }

        $variableList = substr($variableList, 0, -1);
        $newDAL .= $variableList;

        $newDAL .= '
            );
        }

        $idInsert = BaseSingleton::insertOrEdit($sql, $params);

        if($idInsert !== false && $'.PanzerStringUtils::convertBddEnCamelCase($pkField).' > 0)
        {
            $idInsert = $'.PanzerStringUtils::convertBddEnCamelCase($pkField).';
        }

        return $idInsert;
    }

    /**
     * Delete the row corresponding to the given id.
     *
     * @param int $id The id of the '.$className.' you want to delete.
     * @return bool True if the row has been deleted. False if not.
     */
    public static function delete'.$className.'($id)
    {
        $deleted = BaseSingleton::delete(\'delete from '.$table.' where '.$pkField.' = ?\', array(\'i\', &$id));

        return $deleted;
    }

}';
        // Writing the file.
        fwrite($handle, $newDAL);
        fclose($handle);
    }

    private function getRequestableFields($idIsNeeded, $addAlias)
    {
        $attributesList = "";
        $this->requestableFieldCount = 0;

        foreach ($this->attributes as $attribut)
        {
            if ($attribut['Key'] === '' || ($attribut['Key'] === 'PRI' && $idIsNeeded))
            {
                $attributesList .= $attribut['Field'] . ', ';
                $this->requestableFieldCount++;
            }
            else if(isset($attribut['storage']) && $attribut['storage'] === 'object')
            {
                $attributesList .= $attribut['Key'];
                if($addAlias)
                {
                    $attributesList .= ' as ' . $attribut['Field'];
                }
                $attributesList .= ', ';

                $this->requestableFieldCount++;
            }
        }

        if($attributesList !== "")
        {
            $attributesList = substr($attributesList, 0, -2);
        }

        return $attributesList;
    }

    private function getPrimaryKeyFieldName()
    {
        $pkFieldName = "";
        $i = -1;

        do
        {
            if($this->attributes[++$i]['Key'] === 'PRI')
            {
                $pkFieldName = $this->attributes[$i]['Field'];
            }
        }
        while($pkFieldName === "");

        return $pkFieldName;
    }

    private function getPrimaryKeyFromDb($tableName)
    {
        return BaseSingleton::select('SHOW KEYS FROM ' . $tableName . ' WHERE Key_name = \'PRIMARY\'')[0]['Column_name'];
    }

    private function getNeededTokensString()
    {
        $tokenString = '';

        for($i = 0; $i < $this->requestableFieldCount; $i++)
        {
            $tokenString .= '? ,';
        }

        return substr($tokenString, 0, -2);
    }
}