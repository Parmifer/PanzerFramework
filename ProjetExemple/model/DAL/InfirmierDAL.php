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
 

require_once(PanzerConfiguration::getProjectRoot().'model/DAL/UserDAL.php');
require_once(PanzerConfiguration::getProjectRoot().'model/DAL/ChatDAL.php');
require_once(PanzerConfiguration::getProjectRoot().'model/class/Infirmier.php');

class InfirmierDAL extends PanzerDAL
{
    /**
     * Returns the Infirmier which the id match with the given id.
     *
     * @param int $id The id of the searched Infirmier.
     * @return mixed A Infirmier. Null if not found.
     */
    public static function findById($id)
    {
        $params = array('i', &$id);
        $dataset = BaseSingleton::select('SELECT id, nom, prenom, adresse, salaire, user_id FROM infirmier WHERE id = ?', $params);

        $toReturn = self::handleResults($dataset);
        
        return $toReturn;
    }

    /**
     * Returns all the Infirmier.
     *
     * @return mixed From zero to many Infirmier.
     */
    public static function findAll()
    {
        $dataset = BaseSingleton::select('SELECT id, nom, prenom, adresse, salaire, user_id FROM infirmier');

        $toReturn = self::handleResults($dataset);
        
        return $toReturn;
    }

    /**
     * Returns all the Infirmier where the user_id match with the given id.
     *
     * @param int $idUser The id of the User.
     * @return array One or many Infirmier. Null if not found.
     */
    public static function findByIdUser($idUser)
    {
        $params = array('i', &$idUser);
        $dataset = BaseSingleton::select('SELECT id, nom, prenom, adresse, salaire, user_id FROM infirmier WHERE user_id = ?', $params);

        $toReturn = self::handleResults($dataset);
        
        return $toReturn;
    }

    /**
     * Create or edit a Infirmier.
     *
     * @param Infirmier $infirmier
     * @return int id of the Infirmier inserted/edited in base. False if it didn't work.
     */
    public static function persist($infirmier)
    {
        $id = $infirmier->getId();
        $nom = $infirmier->getNom();
        $prenom = $infirmier->getPrenom();
        $adresse = $infirmier->getAdresse();
        $salaire = $infirmier->getSalaire();
        $userId = $infirmier->getUserId();

        if ($id > 0)
        {
            $sql = 'UPDATE infirmier SET '
                    .'infirmier.nom = ?, '
                    .'infirmier.prenom = ?, '
                    .'infirmier.adresse = ?, '
                    .'infirmier.salaire = ?, '
                    .'infirmier.user_id = ? '
                    .'WHERE infirmier.id = ?';

            $params = array('sssdii',
                &$nom,
                &$prenom,
                &$adresse,
                &$salaire,
                &$userId,
                &$id
            );
        }
        else
        {
            $sql = 'INSERT INTO infirmier '
                    . '(nom, prenom, adresse, salaire, user_id) '
                    . 'VALUES (? ,? ,? ,? ,?)';

            $params = array('sssdi',
                &$nom,
                &$prenom,
                &$adresse,
                &$salaire,
                &$userId
            );
        }

        $idInsert = BaseSingleton::insertOrEdit($sql, $params);

        $chatToPersist = $infirmier->getLesChat();
        foreach($chatToPersist as $chat)
        {
            ChatDAL::persist($chat);
            PrendreEnChargeDAL::createAssociation($id, $chat->getId());
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
     * @param int $id The id of the Infirmier you want to delete.
     * @return bool True if the row has been deleted. False if not.
     */
    public static function deleteInfirmier($id)
    {
        $deleted = BaseSingleton::delete('delete from infirmier where id = ?', array('i', &$id));

        return $deleted;
    }
}