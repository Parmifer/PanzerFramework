<?php

/* 
 * Copyright (C) 2015 lucile
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

class BDD
{
    private $mysqli;
    
    private $statement;
            
    public static function connect()
    {
        $this->mysqli = new mysqli('localhost', 'my_user', 'my_password', 'my_db');
    }
    
    public static function disconnect()
    {
        $this->mysqli->close();
    }
    
    public function select($sql, $params)
    {
        $this->connect();
        $this->statement = $this->mysqli->prepare($sql);
        
        $bindParamsMethod = new ReflectionMethod('mysqli_stmt', 'bind_param');
        $bindParamsMethod->invokeArgs($this->statement,$params);
        
        $resultat = $this->statement->get_result();
        
        $data = array();
        while ($row = $resultat->fetch_array())
        {
            $data[] = $row;
        }
        
        return $data;
    }
}