<?php
/*
 * Copyright (C) 2016 Yann Butscher
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

/**
 * Classe d'aide à la configuration. Contient les différentes constantes liées à
 * celle-ci ainsi qu'une méthode qui charge les données en session.
 *
 * @author Yann Butscher
 */
class PanzerConfiguration
{
    const CONFIG                = 'config';
    
    const FILE_ENVIRONNEMENT    = 'environnement';
    const PROJECT_ROOT          = 'projectRoot';
    const ENVIRONNEMENT_MODE    = 'mode';
    
    const FILE_LOG              = 'log';
    const LOG_INFO_LOCATION     = 'log_info_location';
    const LOG_DEBUG_LOCATION    = 'log_debug_location';
    const LOG_WARNING_LOCATION  = 'log_warning_location';
    const LOG_ERROR_LOCATION    = 'log_error_location';
    const LOG_DATABASE_LOCATION = 'log_database_location';
    
    const FILE_DATABASE         = 'database';
    const DATABASE_HOST         = 'database_host';
    const DATABASE_USER         = 'database_user';
    const DATABASE_PASSWORD     = 'database_password';
    const DATABASE_NAME         = 'database_name';
    
    /**
     * Inform you if the configuration is loaded or not.
     * 
     * @return boolean True if the configuration is loaded. False otherwise.
     */
    public static function isLoaded()
    {
        return (isset($_SESSION[self::CONFIG]) ? true : false);
    }

    /**
     * Load the configuration files infos in session.
     */
    public static function loadConfiguration()
    {
        $environnement = parse_ini_file(self::FILE_ENVIRONNEMENT . ".ini");        
        $_SESSION[self::CONFIG][self::FILE_ENVIRONNEMENT] = $environnement;
        
        // If the project's root doesn't end with '/', it's added.
        PanzerStringUtils::addEndingSlashIfNeeded($_SESSION[self::CONFIG][self::FILE_ENVIRONNEMENT][self::PROJECT_ROOT]);
        
        $log = parse_ini_file(self::FILE_LOG . ".ini");
        $_SESSION[self::CONFIG][self::FILE_LOG] = $log;
        
        $database = parse_ini_file(self::FILE_DATABASE . ".ini");   
        $_SESSION[self::CONFIG][self::FILE_DATABASE] = $database;        
    }
    
    /**
     * Reset the configuration.
     */
    public static function resetConfiguration()
    {
        unset($_SESSION[self::CONFIG]);
    }
    
    /**
     * Return the project's root for more convinient includes.
     * 
     * @return string The path to the project's root.
     */
    public static function getProjectRoot()
    {
        $projectRoot = null;
        
        if(isset($_SESSION[self::CONFIG][self::FILE_ENVIRONNEMENT][self::PROJECT_ROOT]))
        {
            $projectRoot = $_SESSION[self::CONFIG][self::FILE_ENVIRONNEMENT][self::PROJECT_ROOT];
        }
        
        return $projectRoot;
    }

    /**
     * Return the log file's path given a log's level.
     * 
     * @param string $level The log's level.
     * @return string The log file's path.
     */
    public static function getLogConfiguration($level)
    {
        switch ($level)
        {
            case PanzerLogger::LEVEL_INFO:
                if (isset($_SESSION[self::CONFIG][self::FILE_LOG][self::LOG_INFO_LOCATION]))
                {
                    return $_SESSION[self::CONFIG][self::FILE_LOG][self::LOG_INFO_LOCATION];
                }
                return null;

            case PanzerLogger::LEVEL_DEBUG:
                if (isset($_SESSION[self::CONFIG][self::FILE_LOG][self::LOG_DEBUG_LOCATION]))
                {
                    return $_SESSION[self::CONFIG][self::FILE_LOG][self::LOG_DEBUG_LOCATION];
                }
                return null;

            case PanzerLogger::LEVEL_WARNING:
                if (isset($_SESSION[self::CONFIG][self::FILE_LOG][self::LOG_WARNING_LOCATION]))
                {
                    return $_SESSION[self::CONFIG][self::FILE_LOG][self::LOG_WARNING_LOCATION];
                }
                return null;

            case PanzerLogger::LEVEL_ERROR:
                if (isset($_SESSION[self::CONFIG][self::FILE_LOG][self::LOG_ERROR_LOCATION]))
                {
                    return $_SESSION[self::CONFIG][self::FILE_LOG][self::LOG_ERROR_LOCATION];
                }
                return null;

            default:
                return false;
        }
    }
}