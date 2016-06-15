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

class PanzerSQLUtils
{
    const ONE_TO_ONE = '1,1';
    const ONE_TO_MANY = '1,n';
    const MANY_TO_MANY = 'n,n';
    
    private static $typeEquivalents = array (
        'CHAR'          => 'string',
        'VARCHAR'       => 'string',
        'TINYTEXT'      => 'string',
        'TEXT'          => 'string',       
        'MEDIUMTEXT'    => 'string',        
        'LONGTEXT'      => 'string',
        'ENUM'          => 'string',
        'BLOB'          => 'string',
        'LONGBLOB'      => 'string',
        'MEDIUMBLOB'    => 'string',        
        'TINYINT'       => 'int',
        'SMALLINT'      => 'int',
        'MEDIUMINT'     => 'int',
        'INT'           => 'int',
        'BIGINT'        => 'int',
        'FLOAT'         => 'float',
        'DOUBLE'        => 'float',
        'DECIMAL'       => 'float',
        'DATE'          => 'DateTime',
        'DATETIME'      => 'DateTime',
        'TIMESTAMP'     => 'DateTime',
        'TIME'          => 'DateTime',
        'YEAR'          => 'int',
        'ENUM'          => 'string',
    );
    
    private static $typeMapping = array (
        'CHAR'          => 's',
        'VARCHAR'       => 's',
        'TINYTEXT'      => 's',
        'TEXT'          => 's',       
        'MEDIUMTEXT'    => 's',        
        'LONGTEXT'      => 's',
        'ENUM'          => 's',
        'BLOB'          => 's',
        'LONGBLOB'      => 's',
        'MEDIUMBLOB'    => 's',        
        'TINYINT'       => 'i',
        'SMALLINT'      => 'i',
        'MEDIUMINT'     => 'i',
        'INT'           => 'i',
        'BIGINT'        => 's',
        'FLOAT'         => 'd',
        'DOUBLE'        => 'd',
        'DECIMAL'       => 'd',
        'DATE'          => 's',
        'DATETIME'      => 's',
        'TIMESTAMP'     => 's',
        'TIME'          => 's',
        'YEAR'          => 'i',
        'ENUM'          => 's',
    );
          
    public static function getPhpType($rawType)
    {
        $toRemove = strrchr($rawType, '(');        
        $sqlType = strtoupper(str_replace($toRemove, '', $rawType));
        
        if(in_array($sqlType, array_keys(self::$typeEquivalents)))
        {
            return self::$typeEquivalents[$sqlType];
        }
        else
        {
            return $rawType;
        }
    }
    
    public static function getParamsMapping($rawType)
    {
        $toRemove = strrchr($rawType, '(');        
        $sqlType = strtoupper(str_replace($toRemove, '', $rawType));
        
        if(in_array($sqlType, array_keys(self::$typeEquivalents)))
        {
            return self::$typeMapping[$sqlType];
        }
        else
        {
            return $rawType;
        }
    }
    
    public static function isEnum($rawType)
    {
        $toRemove = strrchr($rawType, '(');        
        $sqlType = strtoupper(str_replace($toRemove, '', $rawType));

        return $sqlType === 'ENUM';
    }
    
    public static function getEnumList($rawType)
    {
        $enumListString = substr($rawType, 6);
        $enumListStringTrimed = str_replace('\')', '', $enumListString);
        $enumList = explode('\',\'', strtolower($enumListStringTrimed));
        
        return $enumList;
    }


    public static function getNiveauRoleFromCode($code)
    {
        $niveauByCode = array(
            'S' => 3,
            'A' => 2,
            'U' => 1,
            'V' => 0,
        );

        return $niveauByCode[$code];
    }
    
    public static function getFieldMappingInfo($table, $field)
    {
        $sql = 'select TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
                from KEY_COLUMN_USAGE
                where TABLE_SCHEMA = ? 
                and TABLE_NAME = ?
                and COLUMN_NAME = ?
                and referenced_column_name is not NULL;';
        
        $databaseName = $_SESSION[PanzerConfiguration::CONFIG][PanzerConfiguration::FILE_DATABASE][PanzerConfiguration::DATABASE_NAME];        
        $params = array('sss', &$databaseName, &$table, &$field);
        
        $result = BaseSingleton::select($sql, $params, 'INFORMATION_SCHEMA')[0];
        
        $infos = array (
            'referencedTable' => $result['REFERENCED_TABLE_NAME'],
            'referencedColumn' => $result['REFERENCED_COLUMN_NAME'],
            'relationType' => self::getRelationType($table, $result['REFERENCED_TABLE_NAME']),
        );
        
        return $infos;
    }
    
    public static function getTableMappingInfo($table, $referencedTable)
    {
        $sql = 'select TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
                from KEY_COLUMN_USAGE
                where TABLE_SCHEMA = ?
                and TABLE_NAME = ?
                and REFERENCED_TABLE_NAME = ?
                and referenced_column_name is not NULL';
        
        $databaseName = $_SESSION[PanzerConfiguration::CONFIG][PanzerConfiguration::FILE_DATABASE][PanzerConfiguration::DATABASE_NAME];        
        $params = array('sss', &$databaseName, &$table, &$referencedTable);
        
        $result = BaseSingleton::select($sql, $params, 'INFORMATION_SCHEMA');
        
        $foreignKeyInClass = count($result) !== 0;
        $relatedForeignKey = null;
        if($foreignKeyInClass)
        {
            $relatedForeignKey = $result[0]['COLUMN_NAME'];
        }
        
        $infos = array (
            'foreignKeyInClass' => $foreignKeyInClass,
            'relatedForeignKey' => $relatedForeignKey,
        );
        
        return $infos;
    }
    
    public static function getManyToManyMappingInfo($table, $referencedTable)
    {
        $sql = 'select K.REFERENCED_TABLE_NAME, K.REFERENCED_COLUMN_NAME
                from KEY_COLUMN_USAGE K, COLUMNS C
                where C.COLUMN_NAME = K.COLUMN_NAME
                and C.TABLE_NAME = K.TABLE_NAME
                and K.TABLE_SCHEMA = ?
                and C.COLUMN_KEY = \'PRI\'
                and C.TABLE_NAME = ?
                and K.REFERENCED_TABLE_NAME <> ?
                and K.referenced_column_name is not NULL';
        
        $databaseName = $_SESSION[PanzerConfiguration::CONFIG][PanzerConfiguration::FILE_DATABASE][PanzerConfiguration::DATABASE_NAME];        
        $params = array('sss', &$databaseName, &$table, &$referencedTable);
        
        $result = BaseSingleton::select($sql, $params, 'INFORMATION_SCHEMA')[0];
        
        $infos = array (
            'referencedTable' => $result['REFERENCED_TABLE_NAME'],
            'referencedColumn' => $result['REFERENCED_COLUMN_NAME']
        );
        
        return $infos;
    }    
    
    public static function getRelationType($table, $referencedTable)
    {
        $relationType = null;
        
        $sql = 'select K.TABLE_NAME, K.COLUMN_NAME, C.COLUMN_KEY, K.REFERENCED_TABLE_NAME
                from KEY_COLUMN_USAGE K, COLUMNS C
                where C.COLUMN_NAME = K.COLUMN_NAME
                and C.TABLE_NAME = K.TABLE_NAME
                and K.TABLE_SCHEMA = ?
                and K.TABLE_NAME = ?
                and K.REFERENCED_TABLE_NAME = ?
                and K.referenced_column_name is not NULL;';
        
        $databaseName = $_SESSION[PanzerConfiguration::CONFIG][PanzerConfiguration::FILE_DATABASE][PanzerConfiguration::DATABASE_NAME];        
        $params = array('sss', &$databaseName, &$table, &$referencedTable);
        
        $result = BaseSingleton::select($sql, $params, 'INFORMATION_SCHEMA')[0];
        
        if($result['COLUMN_KEY'] === 'UNI')
        {
            $relationType = self::ONE_TO_ONE;
        }
        else if($result['COLUMN_KEY'] === 'PRI' && self::isRelationManyToMany($table))
        {
            $relationType = self::MANY_TO_MANY;
        }
        else if($result['COLUMN_KEY'] === 'PRI')
        {
            $relationType = self::ONE_TO_ONE;
        }
        else
        {
            $relationType = self::ONE_TO_MANY;
        }
        
        return $relationType;
    }
    
    public static function isAForeignKey($table, $field)
    {
        $sql = 'select count(COLUMN_NAME) as isAForeignKey
                from KEY_COLUMN_USAGE
                where TABLE_SCHEMA = ?
                and TABLE_NAME = ? 
                and COLUMN_NAME = ?
                and referenced_column_name is not NULL;';
        
        $databaseName = $_SESSION[PanzerConfiguration::CONFIG][PanzerConfiguration::FILE_DATABASE][PanzerConfiguration::DATABASE_NAME];        
        $params = array('sss', &$databaseName, &$table, &$field);
        
        $isAForeignKey = BaseSingleton::select($sql, $params, 'INFORMATION_SCHEMA')[0]['isAForeignKey'];
        
        return $isAForeignKey === 1;
    }
    
    private static function isRelationManyToMany($table)
    {
        $sql = 'select count(K.TABLE_NAME) as nbRelations
                from KEY_COLUMN_USAGE K, COLUMNS C
                where C.COLUMN_NAME = K.COLUMN_NAME
                and C.TABLE_NAME = K.TABLE_NAME
                and K.TABLE_SCHEMA = ? 
                and K.TABLE_NAME = ?
                and C.COLUMN_KEY = \'PRI\'
                and K.referenced_column_name is not NULL';
        
        $databaseName = $_SESSION[PanzerConfiguration::CONFIG][PanzerConfiguration::FILE_DATABASE][PanzerConfiguration::DATABASE_NAME];        
        $params = array('ss', &$databaseName, &$table);
        
        $nbRelations = BaseSingleton::select($sql, $params, 'INFORMATION_SCHEMA')[0]['nbRelations'];
        
        return $nbRelations >= 2;
    }
}
