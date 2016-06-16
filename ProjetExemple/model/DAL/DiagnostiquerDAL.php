<?php

/*
 * Copyright (C) 2016
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
 

require_once(PanzerConfiguration::getProjectRoot().'model/DAL/ChatDAL.php');
require_once(PanzerConfiguration::getProjectRoot().'model/DAL/VeterinaireDAL.php');
require_once(PanzerConfiguration::getProjectRoot().'model/DAL/VeterinaireDAL.php');
require_once(PanzerConfiguration::getProjectRoot().'model/class/Chat.php');
require_once(PanzerConfiguration::getProjectRoot().'model/class/Veterinaire.php');

class DiagnostiquerDAL extends PanzerDAL
{
    /**
     * Returns all the Diagnostiquer for which the ChatId match with the given id.
     *
     * @param int $idChat The id of the Chat.
     * @return mixed One to many Diagnostiquer. Null if not found.
     */
    public static function findByChatId($idChat)
    {
        $params = array('i', &$idChat);
        $dataset = BaseSingleton::select('SELECT chat_id, veterinaire_id, date, diagnostic FROM diagnostiquer WHERE chat_id = ?', $params);

        $toReturn = self::handleResults($dataset);
        
        return $toReturn;
    }

    /**
     * Returns all the Diagnostiquer for which the VeterinaireId match with the given id.
     *
     * @param int $idVeterinaire The id of the Veterinaire.
     * @return mixed One to many Diagnostiquer. Null if not found.
     */
    public static function findByVeterinaireId($idVeterinaire)
    {
        $params = array('i', &$idVeterinaire);
        $dataset = BaseSingleton::select('SELECT chat_id, veterinaire_id, date, diagnostic FROM diagnostiquer WHERE veterinaire_id = ?', $params);

        $toReturn = self::handleResults($dataset);
        
        return $toReturn;
    }

    /**
     * Returns the Diagnostiquer for which both id's match.
     *
     * @param int $idChat The id of the Chat.
     * @param int $idVeterinaire The id of the Veterinaire.
     * @return mixed A Diagnostiquer. Null if not found.
     */
    public static function findByChatIdAndVeterinaireId($idChat, $idVeterinaire)
    {
        $params = array('ii', &$idChat, &$idVeterinaire);
        $dataset = BaseSingleton::select('SELECT chat_id, veterinaire_id, date, diagnostic FROM diagnostiquer WHERE chat_id = ? AND veterinaire_id = ?', $params);

        $toReturn = self::handleResults($dataset);
        
        return $toReturn;
    }

    /**
     * Returns all the Diagnostiquer.
     *
     * @return mixed From zero to many Diagnostiquer.
     */
    public static function findAll()
    {
        $dataset = BaseSingleton::select('SELECT chat_id, veterinaire_id, date, diagnostic FROM diagnostiquer');

        $toReturn = self::handleResults($dataset);
        
        return $toReturn;
    }

    /**
     * Create or edit a Diagnostiquer.
     *
     * @param Diagnostiquer $diagnostiquer
     * @return int id of the Diagnostiquer inserted/edited in base. False if it didn't work.
     */
    public static function persist($diagnostiquer)
    {
        $chatId = $diagnostiquer->getChatId();
        $veterinaireId = $diagnostiquer->getVeterinaireId();
        $date = $diagnostiquer->getDate();
        $diagnostic = $diagnostiquer->getDiagnostic();

        if ($chatId > 0 && $veterinaireId > 0)
        {
            $sql = 'UPDATE diagnostiquer SET '
                    .'diagnostiquer.date = ?, '
                    .'diagnostiquer.diagnostic = ? '
                    .'WHERE diagnostiquer.chat_id = ?' 
                    .'AND diagnostiquer.veterinaire_id = ?';

            $params = array('ssii',
                &$date,
                &$diagnostic,
                &$chatId,
                &$veterinaireId
            );
        }
        else
        {
            $sql = 'INSERT INTO diagnostiquer '
                    . '(chat_id, veterinaire_id, date, diagnostic) '
                    . 'VALUES (? ,? ,? ,?)';

            $params = array('iiss',
                &$chatId,
                &$veterinaireId,
                &$date,
                &$diagnostic
            );
        }

        $hasWorked = BaseSingleton::insertOrEdit($sql, $params);

        $veterinaire = $diagnostiquer->getVeterinaire();
        VeterinaireDAL::persist($veterinaire);

        return $hasWorked !== false;
    }
}