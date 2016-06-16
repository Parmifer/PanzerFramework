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
 

require_once(PanzerConfiguration::getProjectRoot().'model/class/Chat.php');
require_once(PanzerConfiguration::getProjectRoot().'model/class/Veterinaire.php');

class OpererDAL extends PanzerDAL
{
    /**
     * Returns all the Operer for which the ChatId match with the given id.
     *
     * @param int $idChat The id of the Chat.
     * @return mixed One to many Operer. Null if not found.
     */
    public static function findByChatId($idChat)
    {
        $params = array('i', &$idChat);
        $dataset = BaseSingleton::select('SELECT chat_id, veterinaire_id, date FROM operer WHERE chat_id = ?', $params);

        $toReturn = self::handleResults($dataset);
        
        return $toReturn;
    }

    /**
     * Returns all the Operer for which the VeterinaireId match with the given id.
     *
     * @param int $idVeterinaire The id of the Veterinaire.
     * @return mixed One to many Operer. Null if not found.
     */
    public static function findByVeterinaireId($idVeterinaire)
    {
        $params = array('i', &$idVeterinaire);
        $dataset = BaseSingleton::select('SELECT chat_id, veterinaire_id, date FROM operer WHERE veterinaire_id = ?', $params);

        $toReturn = self::handleResults($dataset);
        
        return $toReturn;
    }

    /**
     * Returns the Operer for which both id's match.
     *
     * @param int $idChat The id of the Chat.
     * @param int $idVeterinaire The id of the Veterinaire.
     * @return mixed A Operer. Null if not found.
     */
    public static function findByChatIdAndVeterinaireId($idChat, $idVeterinaire)
    {
        $params = array('ii', &$idChat, &$idVeterinaire);
        $dataset = BaseSingleton::select('SELECT chat_id, veterinaire_id, date FROM operer WHERE chat_id = ? AND veterinaire_id = ?', $params);

        $toReturn = self::handleResults($dataset);
        
        return $toReturn;
    }

    /**
     * Returns all the Operer.
     *
     * @return mixed From zero to many Operer.
     */
    public static function findAll()
    {
        $dataset = BaseSingleton::select('SELECT chat_id, veterinaire_id, date FROM operer');

        $toReturn = self::handleResults($dataset);
        
        return $toReturn;
    }

    /**
     * Create or edit a Operer.
     *
     * @param Operer $operer
     * @return int id of the Operer inserted/edited in base. False if it didn't work.
     */
    public static function persist($operer)
    {
        $chatId = $operer->getChatId();
        $veterinaireId = $operer->getVeterinaireId();
        $date = $operer->getDate();

        if ($chatId > 0 && $veterinaireId > 0)
        {
            $sql = 'UPDATE operer SET '
                    .'operer.date = ? '
                    .'WHERE operer.chat_id = ?' 
                    .'AND operer.veterinaire_id = ?';

            $params = array('sii',
                &$date,
                &$chatId,
                &$veterinaireId
            );
        }
        else
        {
            $sql = 'INSERT INTO operer '
                    . '(chat_id, veterinaire_id, date) '
                    . 'VALUES (? ,? ,?)';

            $params = array('iis',
                &$chatId,
                &$veterinaireId,
                &$date
            );
        }

        $hasWorked = BaseSingleton::insertOrEdit($sql, $params);

        $veterinaire = $operer->getVeterinaire();
        VeterinaireDAL::persist($veterinaire);

        return $hasWorked !== false;
    }
}