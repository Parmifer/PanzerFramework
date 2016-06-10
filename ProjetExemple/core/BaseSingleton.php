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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 */
class BaseSingleton
{
    private static $instance;
    private $mysqli;
    private $statement;
    
    private $dbHost;
    private $dbUser;
    private $dbPassword;
    private $dbName;
    
    
    private function __construct()
    {
        $this->mysqli = null;
        $this->statement = null;
        
        $this->dbHost     = $_SESSION[PanzerConfiguration::CONFIG][PanzerConfiguration::FILE_DATABASE][PanzerConfiguration::DATABASE_HOST];
        $this->dbUser     = $_SESSION[PanzerConfiguration::CONFIG][PanzerConfiguration::FILE_DATABASE][PanzerConfiguration::DATABASE_USER];
        $this->dbPassword = $_SESSION[PanzerConfiguration::CONFIG][PanzerConfiguration::FILE_DATABASE][PanzerConfiguration::DATABASE_PASSWORD];
        $this->dbName     = $_SESSION[PanzerConfiguration::CONFIG][PanzerConfiguration::FILE_DATABASE][PanzerConfiguration::DATABASE_NAME];
    }
    private static function connect()
    {
        if (is_null(self::$instance))
        {
            self::$instance = new BaseSingleton();
        }
        
        self::$instance->mysqli = new mysqli(self::$instance->dbHost, self::$instance->dbUser, self::$instance->dbPassword, self::$instance->dbName);
    }
    private function disconnect()
    {
        self::$instance->mysqli->close();
        self::$instance = null;
    }
    /**
     * Data access function.
     *
     * @param string $sql
     *        	The SQL query.
     * @param mixed $params
     *        	The parameters to replace (and their data types) in an array. Eg : array("i", &$id);
     * @return mixed Result of the select statement. Data are formated in an array.
     */
    public static function select($sql, $params = null)
    {
        $data = array();
        self::connect();
        // S'il n'y a pas d'erreur de connection.
        if (!self::$instance->mysqli->connect_error)
        {
            // On prépare la requête.
            if (self::$instance->statement = self::$instance->mysqli->prepare($sql))
            {
                // Si la requête a des paramètres.
                if (!is_null($params))
                {
                    $bindParamsMethod = new ReflectionMethod('mysqli_stmt', 'bind_param');
                    $bindParamsMethod->invokeArgs(self::$instance->statement, $params);
                }
                try
                {
                    // Récupération des résultats.
                    self::$instance->statement->execute();
                    $resultat = self::$instance->statement->get_result();
                }
                catch (Exception $e)
                {
                    // Handle exception.
                    $_SESSION['message']['danger'] = $e->getMessage();
                }
                finally
                {
                    self::$instance->disconnect();
                }
                $data = self::fetchResult($resultat);
            }
            else
            {
                $_SESSION['message']['danger'] = 'An error has occured when preparing the SQL query.';
                // TODO : Replace this with logs
                $_SESSION['message']['info'] = self::$instance->mysqli->error;
            }
        }
        else
        {
            $_SESSION['message']['danger'] = 'Database connect failed.';
            $_SESSION['message']['info'] = self::$instance->mysqli->connect_error;
        }
        return $data;
    }
    /**
     * Try to delete one or multiple rows according to the SQL query.
     *
     * @param string $sql
     *        	The SQL query.
     * @param mixed $params
     *        	The parameters to replace (and their data types) in an array. Eg : array("i", $id);
     * @return bool True if the query has been executed. False if it failed.
     */
    public static function insertOrEdit($sql, $params = null)
    {
        $idInserted = false;
        self::connect();
        // S'il n'y a pas d'erreur de connection.
        if (!self::$instance->mysqli->connect_error)
        {
            // On prépare la requête.
            if (self::$instance->statement = self::$instance->mysqli->prepare($sql))
            {
                try
                {
                    // Si la requête a des paramètres.
                    if (!is_null($params))
                    {                        
                        $bindParamsMethod = new ReflectionMethod('mysqli_stmt', 'bind_param');
                        $bindParamsMethod->invokeArgs(self::$instance->statement, $params);
                    }
                    // Execution de la requête
                    if(!self::$instance->statement->execute())
                    {
                        $_SESSION['message']['danger'] = 'The query failed.';
                        // TODO : Replace this with logs
                        $_SESSION['message']['info'] = self::$instance->mysqli->error;
                    }
                    else
                    {
                        $idInserted = self::$instance->statement->insert_id;
                    }
                }
                catch (Exception $e)
                {
                    // Handle exception.
                    $_SESSION['message']['danger'] = $e->getMessage();
                }
                finally
                {
                    self::$instance->disconnect();
                }
            }
            else
            {
                $_SESSION['message']['danger'] = 'An error has occured when preparing the SQL query.';
                // TODO : Replace this with logs
                $_SESSION['message']['info'] = self::$instance->mysqli->error;
            }
        }
        else
        {
            $_SESSION['message']['danger'] = 'Database connect failed.';
            $_SESSION['message']['info'] = self::$instance->mysqli->connect_error;
        }
        return $idInserted;
    }
    /**
     * Try to delete one or multiple rows according to the SQL query.
     *
     * @param string $sql
     *        	The SQL query.
     * @param mixed $params
     *        	The parameters to replace (and their data types) in an array. Eg : array("i", $id);
     * @return bool True if the query has been executed. False if it failed.
     */
    public static function delete($sql, $params = null)
    {
        $deleted = false;
        self::connect();
        // S'il n'y a pas d'erreur de connection.
        if (!self::$instance->mysqli->connect_error)
        {
            // On prépare la requête.
            if (self::$instance->statement = self::$instance->mysqli->prepare($sql))
            {
                try
                {
                    // Si la requête a des paramètres.
                    if (!is_null($params))
                    {
                        $bindParamsMethod = new ReflectionMethod('mysqli_stmt', 'bind_param');
                        $bindParamsMethod->invokeArgs(self::$instance->statement, $params);
                    }
                    // Execution de la requête
                    $deleted = self::$instance->statement->execute();
                }
                catch (Exception $e)
                {
                    // Handle exception.
                    $_SESSION['message']['danger'] = $e->getMessage();
                }
                finally
                {
                    self::$instance->disconnect();
                }
            }
            else
            {
                $_SESSION['message']['danger'] = 'An error has occured when preparing the SQL query.';
                // TODO : Replace this with logs
                $_SESSION['message']['info'] = self::$instance->mysqli->error;
            }
        }
        else
        {
            $_SESSION['message']['danger'] = 'Database connect failed.';
            $_SESSION['message']['info'] = self::$instance->mysqli->connect_error;
        }
        return $deleted;
    }
    /**
     * Utilitary function.
     *
     * @param mixed $resultat
     *        	Raw dataset from the mysql statement.
     * @return mixed Formated dataset.
     */
    private static function fetchResult($resultat)
    {
        $data = array();
        // S'il n'y a pas eu d'erreur lors de la requête.
        if (isset($resultat) && $resultat)
        {
            // On fetch les résultats.
            while ($row = $resultat->fetch_array(MYSQLI_ASSOC))
            {
                $data [] = $row;
            }
        }
        return $data;
    }
}