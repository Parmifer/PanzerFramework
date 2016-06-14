<?php

/*
 * Copyright (C) 2016 lucile
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
 * Classe de log : permet, en utilisant la configuration, d'enregistrer des lignes dans des fichiers de log
 * Format des lignes : [YYYY-MM-DD HH:MM:SS] [Fonction d'appel] NIVEAU DE LOG : Message
 *
 * @author lucile
 */
class PanzerLogger
{

    /**
     * All log levels available
     */
    const LEVEL_INFO = 'INFO';
    const LEVEL_DEBUG = 'DEBUG';
    const LEVEL_WARNING = 'WARNING';
    const LEVEL_ERROR = 'ERROR';

    /**
     * List of all alert levels
     */
    const LEVELS = [
        self::LEVEL_INFO,
        self::LEVEL_DEBUG,
        self::LEVEL_WARNING,
        self::LEVEL_ERROR,
    ];

    /**
     * Log a new line in the appropriate file, using the $level parameter.
     * @param PanzerLogger::LEVEL $level Level for the log
     * @param String $message Message to log
     */
    static public function log($level, $message)
    {
        if (in_array($level, self::LEVELS))
        {
            $date = date('Y-m-d H:i:s');
            $calledBy = debug_backtrace()[2]['function'];
            $log = sprintf('[%s] [%s] %s : %s\r\n', $date, $calledBy, $level, $message);

            self::saveLog($level, $log);
        }
    }

    /**
     * Log a new info log
     * @param String $message Message to log
     */
    static public function logInfo($message)
    {
        self::log(self::LEVEL_INFO, $message);
    }

    /**
     * Log a new debug log
     * @param String $message Message to log
     */
    static public function logDebug($message)
    {
        self::log(self::LEVEL_DEBUG, $message);
    }

    /**
     * Log a new warning log
     * @param String $message Message to log
     */
    static public function logWarning($message)
    {
        self::log(self::LEVEL_WARNING, $message);
    }

    /**
     * Log a new error log
     * @param String $message Message to log
     */
    static public function logError($message)
    {
        self::log(self::LEVEL_ERROR, $message);
    }

    /**
     * Write in the appropriate file a log message
     * @param PanzerLogger::LEVEL $level Level for the log
     * @param String $log Complete message to log (with date and name function)
     */
    static private function saveLog($level, $log)
    {
        $filePath = PanzerConfiguration::getLogConfiguration($level);
        $logFile = fopen($filePath, "a+");
        fputs($logFile, $log);
        fclose($logFile);
    }

}
