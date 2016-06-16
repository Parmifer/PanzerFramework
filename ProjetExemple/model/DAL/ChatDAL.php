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
 

require_once(PanzerConfiguration::getProjectRoot().'model/DAL/SalleDAL.php');
require_once(PanzerConfiguration::getProjectRoot().'model/DAL/DiagnostiquerDAL.php');
require_once(PanzerConfiguration::getProjectRoot().'model/DAL/OpererDAL.php');
require_once(PanzerConfiguration::getProjectRoot().'model/class/Chat.php');

class ChatDAL extends PanzerDAL
{
    /**
     * Returns the Chat which the id match with the given id.
     *
     * @param int $id The id of the searched Chat.
     * @return mixed A Chat. Null if not found.
     */
    public static function findById($id)
    {
        $params = array('i', &$id);
        $dataset = BaseSingleton::select('SELECT id, nom, adresse, reference, etat, salle_id FROM chat WHERE id = ?', $params);

        $toReturn = self::handleResults($dataset);
        
        return $toReturn;
    }

    /**
     * Returns all the Chat.
     *
     * @return mixed From zero to many Chat.
     */
    public static function findAll()
    {
        $dataset = BaseSingleton::select('SELECT id, nom, adresse, reference, etat, salle_id FROM chat');

        $toReturn = self::handleResults($dataset);
        
        return $toReturn;
    }

    /**
     * Returns all the Chat where the salle_id match with the given id.
     *
     * @param int $idSalle The id of the Salle.
     * @return array One or many Chat. Null if not found.
     */
    public static function findByIdSalle($idSalle)
    {
        $params = array('i', &$idSalle);
        $dataset = BaseSingleton::select('SELECT id, nom, adresse, reference, etat, salle_id FROM chat WHERE salle_id = ?', $params);

        $toReturn = self::handleResults($dataset);
        
        return $toReturn;
    }

    /**
     * Create or edit a Chat.
     *
     * @param Chat $chat
     * @return int id of the Chat inserted/edited in base. False if it didn't work.
     */
    public static function persist($chat)
    {
        $id = $chat->getId();
        $nom = $chat->getNom();
        $adresse = $chat->getAdresse();
        $reference = $chat->getReference();
        $etat = $chat->getEtat();
        $salleId = $chat->getSalleId();

        if ($id > 0)
        {
            $sql = 'UPDATE chat SET '
                    .'chat.nom = ?, '
                    .'chat.adresse = ?, '
                    .'chat.reference = ?, '
                    .'chat.etat = ?, '
                    .'chat.salle_id = ? '
                    .'WHERE chat.id = ?';

            $params = array('ssssii',
                &$nom,
                &$adresse,
                &$reference,
                &$etat,
                &$salleId,
                &$id
            );
        }
        else
        {
            $sql = 'INSERT INTO chat '
                    . '(nom, adresse, reference, etat, salle_id) '
                    . 'VALUES (? ,? ,? ,? ,?)';

            $params = array('ssssi',
                &$nom,
                &$adresse,
                &$reference,
                &$etat,
                &$salleId
            );
        }

        $idInsert = BaseSingleton::insertOrEdit($sql, $params);

        $diagnostiquerToPersist = $chat->getLesDiagnostiquer();
        foreach($diagnostiquerToPersist as $diagnostiquer)
        {
            DiagnostiquerDAL::persist($diagnostiquer);
        }

        $opererToPersist = $chat->getLesOperer();
        foreach($opererToPersist as $operer)
        {
            OpererDAL::persist($operer);
        }

        if($idInsert !== false && $id > 0)
        {
            $idInsert = $id;
        }

        return $idInsert;
    }

    /**
     * Delete the row corresponding to the given id.
     *
     * @param int $id The id of the Chat you want to delete.
     * @return bool True if the row has been deleted. False if not.
     */
    public static function deleteChat($id)
    {
        $deleted = BaseSingleton::delete('delete from chat where id = ?', array('i', &$id));

        return $deleted;
    }
}