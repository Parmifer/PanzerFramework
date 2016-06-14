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

    const LEVEL_INFO = 'INFO';
    const LEVEL_DEBUG = 'DEBUG';
    const LEVEL_WARNING = 'WARNING';
    const LEVEL_ERROR = 'ERROR';

    const LEVELS = [
        LEVEL_INFO,
        LEVEL_DEBUG,
        LEVEL_WARNING,
        LEVEL_ERROR
    ];

    static public function log($level, $message)
    {
        if (in_array($level, self::LEVELS))
        {
            $date = new Date('Y-m-d H:i:s');
            $calledBy = debug_backtrace()[1]['function'];
            $log = printf('[%s] [%s] %s : %s', $date, $calledBy, $level, $message);

            self::saveLog($level, $log);
        }
    }

    static public function logInfo($message)
    {
        self::log(self::LEVEL_INFO, $message);
    }

    static public function logDebug($message)
    {
        self::log(self::LEVEL_DEBUG, $message);
    }

    static public function logWarning($message)
    {
        self::log(self::LEVEL_WARNING, $message);
    }

    static public function logError($message)
    {
        self::log(self::LEVEL_ERROR, $message);
    }

    static private function saveLog($level, $log)
    {
        $filePath = PanzerConfiguration::getLogConfiguration($level);
        $logFile = fopen($filePath, "a+");
        fputs($logFile, $log);
        fclose($logFile);
    }

}
