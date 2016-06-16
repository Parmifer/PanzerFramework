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
    private $objectsNotInClass;
    private $objectsInClass;
    private $requestableFieldCount;
    private $savableFieldCount;
    private $paramsTypeString;

    public function generateOneDAL($table, $attributes, $folder)
    {
        $this->attributes = $attributes;

        // Usefull vars
        $className = PanzerStringUtils::convertToClassName($table);
        $dalName = $className . 'DAL';
        $classCamelCase = PanzerStringUtils::convertBddEnCamelCase($table);
        $pkFields = $this->getPrimaryKeyFields();
        $isManyToMany = $this->tableIsAnAssociation();
        $isManyToManyAndFields = ($isManyToMany && count($attributes) > 2);
        $this->setObjectsInClassVariables();

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
 */';
        if (!$isManyToMany)
        {
            $pkField = $pkFields[0];

            $newDAL .=
                    '

require_once(PanzerConfiguration::getProjectRoot().\'model/class/' . $className . '.php\');

class ' . $dalName . ' extends PanzerDAL
{
    /**
     * Returns the ' . $className . ' which the id match with the given id.
     *
     * @param int $id The id of the searched ' . $className . '.
     * @return mixed A ' . $className . '. Null if not found.
     */
    public static function findById($id)
    {
        $params = array(\'i\', &$id);
        $dataset = BaseSingleton::select(\'SELECT ' . $this->getRequestableFields() . ' FROM ' . $table . ' WHERE ' . $pkField['fieldName'] . ' = ?\', $params);

        return self::handleResults($dataset);
    }

    /**
     * Returns all the ' . $className . '.
     *
     * @return mixed From zero to many ' . $className . '.
     */
    public static function findAll()
    {
        $dataset = BaseSingleton::select(\'SELECT ' . $this->getRequestableFields() . ' FROM ' . $table . '\');

        return self::handleResults($dataset);
    }';

            if ($table == 'user')
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
        $dataset = BaseSingleton::select(\'SELECT ' . $this->getRequestableFields() . ' FROM user WHERE pseudo = ?\', $params);

        return self::handleResults($dataset);
    }

    /**
     * Verify if the user exist and if his password is correct, return the user if true, null otherwise
     *
     * @param String $login Login given by the user
     * @param String(SHA512) $password Password given by the user
     * @return User|null|false User if login and password correct, null if not, false in case of error
     */
    public static function verifyConnection($login, $password)
    {
        $params = [\'ss\', &$login, &$password];
        $dataset = BaseSingleton::select(\'SELECT * FROM user where pseudo = ? AND password = ?\', $params);

        return self::handleResults($dataset);
    }';
            }

            if (count($this->objectsNotInClass) > 0)
            {
                foreach ($this->objectsNotInClass as $foreignKey)
                {
                    $referencedClass = PanzerStringUtils::convertToClassName($foreignKey['referencedTable']);
                    $isOneToOne = ($foreignKey['relationType'] === PanzerSQLUtils::ONE_TO_ONE);
                    if($isOneToOne)
                    {
                        $newDAL .= '

    /**
     * Returns the ' . $className . ' which the ' . $foreignKey['fieldName'] . ' match with the given id.
     *
     * @param int $id' . $referencedClass . ' The id of the ' . $referencedClass . '.
     * @return mixed A ' . $className . '. Null if not found.
     */';
                    }
                    else
                    {
                        $newDAL .= '

    /**
     * Returns all the ' . $className . ' where the ' . $foreignKey['fieldName'] . ' match with the given id.
     *
     * @param int $id' . $referencedClass . ' The id of the ' . $referencedClass . '.
     * @return array One or many ' . $className . '. Null if not found.
     */';
                    }
                    $newDAL .= '
    public static function findById' . $referencedClass . '($id' . $referencedClass . ')
    {
        $params = array(\'i\', &$id' . $referencedClass . ');
        $dataset = BaseSingleton::select(\'SELECT ' . $this->getRequestableFields() . ' FROM ' . $table . ' WHERE ' . $foreignKey['fieldName'] . ' = ?\', $params);

        return self::handleResults($dataset);
    }';
                }
            }

            $newDAL .= '

    /**
     * Create or edit a ' . $className . '.
     *
     * @param ' . $className . ' $' . $classCamelCase . '
     * @return int id of the ' . $className . ' inserted/edited in base. False if it didn\'t work.
     */
    public static function persist($' . $classCamelCase . ')
    {';
            foreach ($this->attributes as $attribut)
            {
                if ($attribut['isSavable'] && !$attribut['isForeignKey'])
                {
                    $newDAL .= '
        $' . $attribut['attributName'] . ' = $' . $classCamelCase . '->get' . $attribut['toUpperName'] . '();';
                }
                else if ($attribut['isSavable'] && in_array($attribut, $this->objectsNotInClass))
                {
                    $newDAL .= '
        $' . $attribut['attributName'] . ' = $' . $classCamelCase . '->get' . $attribut['toUpperName'] . '();';
                }
                else if ($attribut['isSavable'] && in_array($attribut, $this->objectsInClass))
                {
                    $tableUpper = PanzerStringUtils::convertToClassName($attribut['referencedTable']);
                    $clefUpper = PanzerStringUtils::convertToClassName($attribut['referencedColumn']);
                    $newDAL .= '
        $' . $attribut['attributName'] . ' = $' . $classCamelCase . '->get' . $tableUpper . '()->get' . $clefUpper . '();';
                }
            }

            $newDAL .= '

        if ($' . $pkField['attributName'] . ' > 0)
        {
            $sql = \'UPDATE ' . $table . ' SET \'';

            $paramTypeString = '';
            $fieldList = '';


            foreach ($this->attributes as $attribut)
            {
                if ($attribut['isSavable'] && !$attribut['isPrimaryKey'])
                {
                    $fieldList .= '
                    .\'' . $table . '.' . $attribut['fieldName'] . ' = ?, \'';
                    $paramTypeString .= $attribut['paramsMapping'];
                }
            }

            $fieldToUpdate = substr($fieldList, 0, -3);
            $fieldToUpdate .= ' \'';

            $newDAL.= $fieldToUpdate;
            $newDAL .= '
                    .\'WHERE ' . $table . '.' . $pkField['fieldName'] . ' = ?\';';
            $newDAL .= '

            $params = array(\'' . $paramTypeString . 'i\',';

            foreach ($this->attributes as $attribut)
            {
                if ($attribut['isSavable'] && !$attribut['isPrimaryKey'])
                {
                    $newDAL .= '
                &$' . $attribut['attributName'] . ',';
                }
            }

            $newDAL .= '
                &$' . $pkField['attributName'] . '
            );
        }
        else
        {
            $sql = \'INSERT INTO ' . $table . ' \'
                    . \'(' . $this->getSavableFields(false) . ') \'
                    . \'VALUES (' . $this->getPersistTokensString() . ')\';

            $params = array(\'' . $this->paramsTypeString . '\',';

            $variableList = '';

            foreach ($this->attributes as $attribut)
            {
                if ($attribut['isSavable'] && !$attribut['isPrimaryKey'])
                {
                    $variableList .= '
                &$' . $attribut['attributName'] . ',';
                }
            }

            $variableToInsert = substr($variableList, 0, -1);
            $newDAL .= $variableToInsert;

            $newDAL .= '
            );
        }

        $idInsert = BaseSingleton::insertOrEdit($sql, $params);';

            foreach ($this->attributes as $attribut)
            {
                if ($attribut['storage'] === 'array')
                {
                    $toPersist = PanzerStringUtils::convertBddEnCamelCase($attribut['fieldName']) . 'ToPersist';
                    $newDAL .= '

        $' . $toPersist . ' = $' . $classCamelCase . '->get' . PanzerStringUtils::premiereLettreMaj($attribut['attributName']) . '();
        foreach($'.$toPersist.' as $' . PanzerStringUtils::convertBddEnCamelCase($attribut['fieldName']) . ')
        {
            ' . PanzerStringUtils::convertToClassName($attribut['fieldName']) . 'DAL::persist($' . PanzerStringUtils::convertBddEnCamelCase($attribut['fieldName']) . ');
        }';
                }
                else if($attribut['storage'] === 'object' && !$attribut['foreignKeyInClass'])
                {
                    $newDAL .= '

        $'.$attribut['attributName'].' = $' . $classCamelCase . '->get' . $attribut['toUpperName'] . '();
        '. $attribut['toUpperName'] . 'DAL::persist($' . $attribut['attributName'] . ');';
                }
            }

            $newDAL .= '

        if($idInsert !== false && $' . $pkField['attributName'] . ' > 0)
        {
            $idInsert = $' . $pkField['attributName'] . ';
        }

        return $idInsert;
    }

    /**
     * Delete the row corresponding to the given id.
     *
     * @param int $id The id of the ' . $className . ' you want to delete.
     * @return bool True if the row has been deleted. False if not.
     */
    public static function delete' . $className . '($id)
    {
        $deleted = BaseSingleton::delete(\'delete from ' . $table . ' where ' . $pkField['fieldName'] . ' = ?\', array(\'i\', &$id));

        return $deleted;
    }';
        }
        else if ($isManyToManyAndFields)
        {
            $pk1 = $pkFields[0];
            $pk2 = $pkFields[1];
            $pk1ClassName = PanzerStringUtils::convertToClassName($pk1['referencedTable']);
            $pk2ClassName = PanzerStringUtils::convertToClassName($pk2['referencedTable']);
            $newDAL .=
                    '

require_once(PanzerConfiguration::getProjectRoot().\'model/DAL/' . $pk1ClassName . 'DAL.php\');
require_once(PanzerConfiguration::getProjectRoot().\'model/class/' . $pk1ClassName . '.php\');
require_once(PanzerConfiguration::getProjectRoot().\'model/DAL/' . $pk2ClassName . 'DAL.php\');
require_once(PanzerConfiguration::getProjectRoot().\'model/class/' . $pk2ClassName . '.php\');

class ' . $dalName . ' extends PanzerDAL
{
    /**
     * Returns all the ' . $className . ' for which the ' . $pk1ClassName . 'Id match with the given id.
     *
     * @param int $id' . $pk1ClassName . ' The id of the ' . $pk1ClassName . '.
     * @return mixed One to many ' . $className . '. Null if not found.
     */
    public static function findBy' . $pk1ClassName . 'Id($id' . $pk1ClassName . ')
    {
        $params = array(\'i\', &$id' . $pk1ClassName . ');
        $dataset = BaseSingleton::select(\'SELECT ' . $this->getRequestableFields() . ' FROM ' . $table . ' WHERE ' . $pk1['fieldName'] . ' = ?\', $params);

        return self::handleResults($dataset);
    }

    /**
     * Returns all the ' . $className . ' for which the ' . $pk2ClassName . 'Id match with the given id.
     *
     * @param int $id' . $pk2ClassName . ' The id of the ' . $pk2ClassName . '.
     * @return mixed One to many ' . $className . '. Null if not found.
     */
    public static function findBy' . $pk2ClassName . 'Id($id' . $pk2ClassName . ')
    {
        $params = array(\'i\', &$id' . $pk2ClassName . ');
        $dataset = BaseSingleton::select(\'SELECT ' . $this->getRequestableFields() . ' FROM ' . $table . ' WHERE ' . $pk2['fieldName'] . ' = ?\', $params);

        return self::handleResults($dataset);
    }

    /**
     * Returns the ' . $className . ' for which both id\'s match.
     *
     * @param int $id' . $pk1ClassName . ' The id of the ' . $pk1ClassName . '.
     * @param int $id' . $pk2ClassName . ' The id of the ' . $pk2ClassName . '.
     * @return mixed A ' . $className . '. Null if not found.
     */
    public static function findBy' . $pk1ClassName . 'IdAnd' . $pk2ClassName . 'Id($id' . $pk1ClassName . ', $id' . $pk2ClassName . ')
    {
        $params = array(\'ii\', &$id' . $pk1ClassName . ', &$id' . $pk2ClassName . ');
        $dataset = BaseSingleton::select(\'SELECT ' . $this->getRequestableFields() .
                    ' FROM ' . $table .
                    ' WHERE ' . $pk1['fieldName'] . ' = ?' .
                    ' AND ' . $pk2['fieldName'] . ' = ?' .
                    '\', $params);

        return self::handleResults($dataset);
    }

    /**
     * Returns all the ' . $className . '.
     *
     * @return mixed From zero to many ' . $className . '.
     */
    public static function findAll()
    {
        $dataset = BaseSingleton::select(\'SELECT ' . $this->getRequestableFields() . ' FROM ' . $table . '\');

        return self::handleResults($dataset);
    }';
        }
        else
        {
            $pk1 = $pkFields[0];
            $pk2 = $pkFields[1];
            $pk1ClassName = PanzerStringUtils::convertToClassName($pk1['referencedTable']);
            $pk2ClassName = PanzerStringUtils::convertToClassName($pk2['referencedTable']);
            $newDAL .=
                    '

class ' . $dalName . ' extends PanzerDAL
{

    /**
     * Create a new association between one ' . $pk1ClassName . ' and one ' . $pk2ClassName . '.
     *
     * @param int $id' . $pk1ClassName . ' The id of the ' . $pk1ClassName . '.
     * @param int $id' . $pk2ClassName . ' The id of the ' . $pk2ClassName . '.
     * @return bool True is the creation worked. False otherwise.
     */
    public static function createAssociation($id' . $pk1ClassName . ', $id' . $pk2ClassName . ')
    {
        $sql = \'INSERT INTO ' . $table . ' \'
                . \'(' . $pk1['fieldName'] . ', ' . $pk2['fieldName'] . ')\'
                . \'VALUES (?, ?)\';
        $params = array(\'ii\', &$id' . $pk1ClassName . ', &$id' . $pk2ClassName . ');
        $itWorked = BaseSingleton::insertOrEdit($sql, $params);

        return $itWorked !== false;
    }

    /**
     * Delete an association between one ' . $pk1ClassName . ' and one ' . $pk2ClassName . '.
     *
     * @param int $id' . $pk1ClassName . ' The id of the ' . $pk1ClassName . '.
     * @param int $id' . $pk2ClassName . ' The id of the ' . $pk2ClassName . '.
     * @return bool True is the association was deleted. False otherwise.
     */
    public static function deleteAssociation($id' . $pk1ClassName . ', $id' . $pk2ClassName . ')
    {
        $sql = \'DELETE FROM ' . $table . ' WHERE ' . $pk1['fieldName'] . ' = ? AND ' . $pk2['fieldName'] . ' = ?\';
        $params = array(\'ii\', &$id' . $pk1ClassName . ', &$id' . $pk2ClassName . ');
        $deleted = BaseSingleton::delete($sql, $params);

        return $deleted;
    }';
        }

        $newDAL .= '
}';

        // Writing the file.
        fwrite($handle, $newDAL);
        fclose($handle);
    }

    private function getRequestableFields()
    {
        $attributesList = '';
        $this->requestableFieldCount = 0;

        foreach ($this->attributes as $attribut)
        {
            if ($attribut['isRequestable'])
            {
                $attributesList .= $attribut['fieldName'] . ', ';
                $this->requestableFieldCount++;
            }
        }

        if ($attributesList !== '')
        {
            $attributesList = substr($attributesList, 0, -2);
        }

        return $attributesList;
    }

    private function getSavableFields($isIdNeeded)
    {
        $attributesList = '';
        $this->paramsTypeString = '';
        $this->savableFieldCount = 0;

        foreach ($this->attributes as $attribut)
        {
            if ($attribut['isSavable'] && $isIdNeeded)
            {
                $attributesList .= $attribut['fieldName'] . ', ';
                $this->paramsTypeString .= $attribut['paramsMapping'];
                $this->savableFieldCount++;
            }
            else if ($attribut['isSavable'] && !$isIdNeeded && !$attribut['isPrimaryKey'])
            {
                $attributesList .= $attribut['fieldName'] . ', ';
                $this->paramsTypeString .= $attribut['paramsMapping'];
                $this->savableFieldCount++;
            }
        }

        if ($attributesList !== '')
        {
            $attributesList = substr($attributesList, 0, -2);
        }

        return $attributesList;
    }

    private function getPrimaryKeyFields()
    {
        $primaryKeyFields = array();

        foreach ($this->attributes as $attribut)
        {
            if ($attribut['isPrimaryKey'])
            {
                $primaryKeyFields[] = $attribut;
            }
        }

        return $primaryKeyFields;
    }

    private function getPrimaryKeyFromDb($tableName, $moreThanOneId)
    {
        $result = BaseSingleton::select('SHOW KEYS FROM ' . $tableName . ' WHERE Key_name = \'PRIMARY\'');
        if ($moreThanOneId)
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

        for ($i = 0; $i < $this->requestableFieldCount; $i++)
        {
            $tokenString .= '? ,';
        }

        return substr($tokenString, 0, -2);
    }

    private function getPersistTokensString()
    {
        $tokenString = '';

        for ($i = 0; $i < $this->savableFieldCount; $i++)
        {
            $tokenString .= '? ,';
        }

        return substr($tokenString, 0, -2);
    }

    private function tableIsAnAssociation()
    {
        $isAnAssociation = false;

        foreach ($this->attributes as $attribut)
        {
            if ($attribut['relationType'] == PanzerSQLUtils::MANY_TO_MANY)
            {
                $isAnAssociation = true;
                break;
            }
        }

        return $isAnAssociation;
    }

    private function setObjectsInClassVariables()
    {
        $this->objectsNotInClass = array();
        $this->objectsInClass = array();

        foreach ($this->attributes as $attribut)
        {
            if ($attribut['isForeignKey'])
            {
                $isInClass = false;

                foreach ($this->attributes as $potentielObjet)
                {
                    if ($potentielObjet['relatedForeignKey'] === $attribut['fieldName'])
                    {
                        $isInClass = true;
                        break;
                    }
                }

                if ($isInClass)
                {
                    $this->objectsNotInClass[] = $attribut;
                }
                else
                {
                    $this->objectsNotInClass[] = $attribut;
                }
            }
        }
    }
}
