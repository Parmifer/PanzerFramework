<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SQLUtils
{
    private $typeEquivalents = array (
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
        'YEAR'          => 'int'
    );


          
    public static function getPhpType($sqlType)
    {
        
    }
}
