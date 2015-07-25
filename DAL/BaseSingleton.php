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

class BaseSingleton
{
    private static $instance;
    
    private $mysqli;
    
    private $statement;
    
    private function __construct()
    {
        $this->mysqli = null;    
        $this->statement = null;
    }
            
    private static function connect()
    {
        if(is_null(self::$instance))
        {
            self::$instance = new BaseSingleton();
        }
        
        self::$instance->mysqli = new mysqli('localhost', 'root', '', 'PanzerTest');
    }
    
    private function disconnect()
    {
        self::$instance->mysqli->close();
        self::$instance = null;    
    }
    
    public static function select($sql, $params = null)
    {
        $data = array();
        
        self::connect();
        
        // S'il n'y a pas d'erreur de connection.
        if(!self::$instance->mysqli->connect_error)
        {
            try
            {
                // On prépare la requête.
                self::$instance->statement = self::$instance->mysqli->prepare($sql);
                
                // Si la requête a des paramètres.
                if(!is_null($params))
                {                   
                    $bindParamsMethod = new ReflectionMethod('mysqli_stmt', 'bind_param');
                    $bindParamsMethod->invokeArgs(self::$instance->statement, $params);
                }
                
                // Récupération des résultats.
                self::$instance->statement->execute();
                $resultat = self::$instance->statement->get_result();
            }
            catch (Exception $e)
            {
                // Handle exception.
                echo $e->getMessage();
            }
            finally
            {
                self::$instance->disconnect();
            }
            
            $data = self::fetchResult($resultat);
        }
        else
        {
            echo 'La connexion a échouée.';
            echo self::$instance->mysqli->connect_error;
        }
        
        return $data;
    }
    
    /**
     * Fonction utilitaire.
     * 
     * @param mixed $resultat
     * @return mixed Un tableau de données. 
     */
    private static function fetchResult($resultat)
    {
        $data = array();
        
        // S'il n'y a pas eu d'erreur lors de la requête.
        if(isset($resultat) && $resultat)
        {            
            // On fetch les résultats.
            while ($row = $resultat->fetch_array())
            {
                $data[] = $row;
            }
        }
        
        return $data;
    }
}