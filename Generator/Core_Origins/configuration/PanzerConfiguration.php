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
     * Charge les données de configuration dans des variables de session.
     */
    public static function loadConfiguration()
    {
        $environnement = parse_ini_file(self::FILE_ENVIRONNEMENT . ".ini");        
        $_SESSION[self::CONFIG][self::FILE_ENVIRONNEMENT] = $environnement;
        
        // Si la racine du projet ne fini pas pas '/', on le rajoute.
        PanzerStringUtils::addEndingSlashIfNeeded($_SESSION[self::CONFIG][self::FILE_ENVIRONNEMENT][self::PROJECT_ROOT]);
        
        $log = parse_ini_file(FILE_LOG . ".ini");
        $_SESSION[self::CONFIG][self::FILE_LOG] = $log;
        
        $database = parse_ini_file(self::FILE_DATABASE . ".ini");   
        $_SESSION[self::CONFIG][self::FILE_DATABASE] = $database;        
    }
    
    public static function getProjectRoot()
    {
        $projectRoot = null;
        
        if(isset($_SESSION[self::CONFIG][self::FILE_ENVIRONNEMENT][self::PROJECT_ROOT]))
        {
            $projectRoot = $_SESSION[self::CONFIG][self::FILE_ENVIRONNEMENT][self::PROJECT_ROOT];
        }
        
        return $projectRoot;
    }
}