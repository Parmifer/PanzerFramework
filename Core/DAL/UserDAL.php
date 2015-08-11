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
require_once('BaseSingleton.php');
require_once('PanzerDAL.php');
require_once('User.php');

class UserDAL extends PanzerDAL
{
    public static function findById($id)
    {
        $params = array('i', &$id);
        $dataset = BaseSingleton::select('SELECT * FROM user WHERE id = ?', $params);
        
        return self::hydrate($dataset[0]);
    }
    
    public static function findAll()
    {
        $dataset = BaseSingleton::select('SELECT * FROM user');
        $users = array();
        foreach($dataset as $row)
        {
            $users[] = self::hydrate($row);
        }
        
        return $users;
    }
}