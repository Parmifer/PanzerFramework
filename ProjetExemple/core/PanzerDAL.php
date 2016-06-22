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

abstract class PanzerDAL
{
    protected static function hydrate($row)
    {
        if(!empty($row) && is_array($row))
        {
            $className = substr(get_called_class(), 0, -3); 
            $createObjectCode = '$object = new ' . $className . '();';
            eval($createObjectCode);
            foreach($row as $field => $value)
            {    
                $fillCode = '$object->set' . PanzerStringUtils::convertToClassName($field) . '($value);';        
                eval($fillCode);
            }
        
            return $object;
        }
        else
        {
            throw new InvalidArgumentException('PanzerDAL::hydrate($row) function only accept not empty arrays.');
        }
    }
    
    protected static function handleResults($dataSet)
    {               
        switch (count($dataSet))
        {
            case 0:
                return null;
                break;
            
            case 1:
                return self::hydrate($dataSet[0]);
                break;
            
            default:
                $objects = array();
                
                foreach($dataSet as $row)
                {
                    $objects[] = self::hydrate($row);
                }
                
                return $objects;
                break;
        }
    }
}