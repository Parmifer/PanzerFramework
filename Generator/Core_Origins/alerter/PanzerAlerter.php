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
 * System allowing to create front messages
 *
 * @author lucile
 */
class PanzerAlerter
{

    /**
     * Used to store alerts in the session
     */
    const ALERT = 'alert';
    const LEVEL = 'level';
    const MESSAGE = 'message';

    /**
     * All alert levels available
     */
    const LEVEL_SUCCESS = 'alert-success';
    const LEVEL_INFO = 'alert-info';
    const LEVEL_WARNING = 'alert-warning';
    const LEVEL_ERROR = 'alert-danger';

    /**
     * List of all alert levels
     */
    const LEVELS = [
        self::LEVEL_SUCCESS,
        self::LEVEL_INFO,
        self::LEVEL_WARNING,
        self::LEVEL_ERROR,
    ];

    /**
     * Create an alert for the level given
     * @param PanzerAlerter::LEVEL $level Level of the alert wanted
     * @param String $message Message what will be displayed in the browser
     */
    static public function alert($level, $message)
    {
        $alert = new PanzerAlert($level, $message);
        self::createAlert($alert);
    }

    /**
     * Save an alert which will be displayed at an other time
     * @param PanzerAlerter::LEVEL $level Level of the alert wanted
     * @param PanzerAlert $message Message what will be displayed in the browser
     */
    static private function createAlert($alert)
    {
        if (in_array($alert->getLevel(), self::LEVELS))
        {
            $_SESSION[self::ALERT][] = $alert;
        }
        else
        {
            $message = printf('It is not possible to create an alert with the level [%s].', $alert->getLevel());
            PanzerLogger::logError($message);
        }
    }

    /**
     * Get all stored alerts if exists, null otherwise
     * @return PanzerAlert[] All alerts
     */
    static public function getAlerts()
    {
        $alerts = null;
        if (isset($_SESSION[self::ALERT]))
        {
            $alerts = $_SESSION[self::ALERT];
            unset($_SESSION[self::ALERT]);
        }
        return $alerts;
    }

    /**
     * Delete all alerts stored
     */
    static public function resetAlerts()
    {
        PanzerLogger::logError('Resetting alerts...');
        unset($_SESSION[self::ALERT]);
        PanzerLogger::logError('Alerts resetted');
    }

}
