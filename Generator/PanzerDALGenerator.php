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
    private $savableFieldCount;

    public function generateOneDAL($table, $attributes, $folder)
    {
        $this->attributes = $attributes;
        
        // Usefull vars
        $className = PanzerStringUtils::convertToClassName($table);
        $dalName = $className . 'DAL';
        $classCamelCase = PanzerStringUtils::convertBddEnCamelCase($table);
        $pkField = $this->getPrimaryKeyFieldName();
        

        $generatedDALFile = $folder . $dalName . '.php';
        $handle = fopen($generatedDALFile, 'w') or die('Cannot open file:  ' . $generatedDALFile);

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
        $dataset = BaseSingleton::select(\'SELECT '.$this->getRequestableFields(true).' FROM '.$table.' WHERE '.$pkField[0]['fieldName'].' = ?\', $params);

        return self::handleResults($dataset);
    }

    /**
     * Returns all the '.$className.'.
     *
     * @return mixed From zero to many '.$className.'.
     */
    public static function findAll()
    {
        $dataset = BaseSingleton::select(\'SELECT '.$this->getRequestableFields(true).' FROM '.$table.'\');

        return self::handleResults($dataset);
    }';

        if($table == 'user')
        {
            $newDAL .= '
                
    /**
     * Returns the User matching with the given pseudo.
     *
     * @params string $pseudo The searched User\'s pseudo.
     * @return User The matched User.
     */
    public static function findByPseudo($pseudo)
    {
        $params = array(\'s\', &$pseudo);
        $dataset = BaseSingleton::select(\'SELECT '.$this->getRequestableFields(true).' FROM user WHERE pseudo = ?\', $params);

        return self::handleResults($dataset);
    }';
        }

        $newDAL .= '

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
        $'.$nomObjet . ' = $'.$classCamelCase.'->get'.PanzerStringUtils::convertToClassName($attribut['Field']) . '();';
            }
            else if(isset($attribut['storage']) && $attribut['storage'] == 'object')
            {
                $getPKfunction = 'get'. PanzerStringUtils::convertToClassName($this->getPrimaryKeyFromDb($attribut['Field'], false)).'()';
                $newDAL .= '
        $'. $nomObjet . ' = $'.$classCamelCase.'->get'.PanzerStringUtils::convertToClassName($attribut['Field']).'()->'.$getPKfunction.';';
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
       
    private function getRequestableFields()
    {
        $attributesList = "";
        $this->requestableFieldCount = 0;

        foreach ($this->attributes as $attribut)
        {
            if ($attribut['isRequestable'])
            {
                $attributesList .= $attribut['fieldName'] . ', ';
                $this->requestableFieldCount++;
            }
        }

        if($attributesList !== "")
        {
            $attributesList = substr($attributesList, 0, -2);
        }

        return $attributesList;
    }
    
    private function getSavableFields()
    {
        $attributesList = "";
        $this->savableFieldCount = 0;

        foreach ($this->attributes as $attribut)
        {
            if ($attribut['isSavable'] )
            {
                $attributesList .= $attribut['fieldName'] . ', ';
                $this->savableFieldCount++;
            }
        }

        if($attributesList !== "")
        {
            $attributesList = substr($attributesList, 0, -2);
        }

        return $attributesList;
    }

    private function getPrimaryKeyFields()
    {
        $primaryKeyFields = array();

        foreach($this->attributes as $attribut)
        {
            if($attribut['isPrimaryKey'])
            {
                $primaryKeyFields[] = $attribut;
            }
        }

        return $primaryKeyFields;
    }

    private function getPrimaryKeyFromDb($tableName, $moreThanOneId)
    {
        $result = BaseSingleton::select('SHOW KEYS FROM ' . $tableName . ' WHERE Key_name = \'PRIMARY\'');
        if($moreThanOneId)
        {
            return array($result[0]['Column_name'], $result[1]['Column_name']);
        }
        else
        {
            return $result[0]['Column_name'];
        }
        
    }

    private function getRequestTokensString()
    {
        $tokenString = '';

        for($i = 0; $i < $this->requestableFieldCount; $i++)
        {
            $tokenString .= '? ,';
        }

        return substr($tokenString, 0, -2);
    }
    
    private function getPersistTokensString()
    {
        $tokenString = '';

        for($i = 0; $i < $this->savableFieldCount; $i++)
        {
            $tokenString .= '? ,';
        }

        return substr($tokenString, 0, -2);
    }
    
    private function tableIsAnAssociation()
    {
        $isAnAssociation = false;
        
        foreach($this->attributes as $attribut)
        {
            if($attribut['relationType'] == PanzerSQLUtils::MANY_TO_MANY)
            {
                $isAnAssociation = true;
                break;
            }
        }
        
        if($isAnAssociation)
        {
            
        }
        
        return $isAnAssociation;
    }
}