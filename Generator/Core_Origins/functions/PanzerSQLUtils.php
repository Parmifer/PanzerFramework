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
        'YEAR'          => 'int'
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
}
