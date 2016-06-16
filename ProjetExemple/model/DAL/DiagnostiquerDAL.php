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
require_once(PanzerConfiguration::getProjectRoot().'model/class/Chat.php');
require_once(PanzerConfiguration::getProjectRoot().'model/DAL/VeterinaireDAL.php');
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

        return self::handleResults($dataset);
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

        return self::handleResults($dataset);
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

        return self::handleResults($dataset);
    }

    /**
     * Returns all the Diagnostiquer.
     *
     * @return mixed From zero to many Diagnostiquer.
     */
    public static function findAll()
    {
        $dataset = BaseSingleton::select('SELECT chat_id, veterinaire_id, date, diagnostic FROM diagnostiquer');

        return self::handleResults($dataset);
    }
}