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
    
require_once(PanzerConfiguration::getProjectRoot().'model/class/RessourcesHumaines.php');

class RessourcesHumainesDAL extends PanzerDAL
{
    /**
     * Returns the RessourcesHumaines which the id match with the given id.
     *
     * @param int $id The id of the searched RessourcesHumaines.
     * @return mixed A RessourcesHumaines. Null if not found.
     */
    public static function findById($id)
    {
        $params = array('i', &$id);
        $dataset = BaseSingleton::select('SELECT id, nom, prenom, adresse, salaire, user_id FROM ressources_humaines WHERE id = ?', $params);

        return self::handleResults($dataset);
    }

    /**
     * Returns all the RessourcesHumaines.
     *
     * @return mixed From zero to many RessourcesHumaines.
     */
    public static function findAll()
    {
        $dataset = BaseSingleton::select('SELECT id, nom, prenom, adresse, salaire, user_id FROM ressources_humaines');

        return self::handleResults($dataset);
    }
                    
    /**
     * Returns the RessourcesHumaines which the user_id match with the given id.
     *
     * @param int $idUser The id of the User.
     * @return mixed A RessourcesHumaines. Null if not found.
     */
    public static function findByIdUser($idUser)
    {
        $params = array('i', &$idUser);
        $dataset = BaseSingleton::select('SELECT id, nom, prenom, adresse, salaire, user_id FROM ressources_humaines WHERE user_id = ?', $params);

        return self::handleResults($dataset);
    }

    /**
     * Create or edit a RessourcesHumaines.
     *
     * @param RessourcesHumaines $ressourcesHumaines
     * @return int id of the RessourcesHumaines inserted/edited in base. False if it didn't work.
     */
    public static function persist($ressourcesHumaines)
    {
        $id = $ressourcesHumaines->getId();
        $nom = $ressourcesHumaines->getNom();
        $prenom = $ressourcesHumaines->getPrenom();
        $adresse = $ressourcesHumaines->getAdresse();
        $salaire = $ressourcesHumaines->getSalaire();
        $userId = $ressourcesHumaines->getUserId();

        if ($id > 0)
        {
            $sql = 'UPDATE ressources_humaines SET '
                    .'ressources_humaines.nom = ?, '
                    .'ressources_humaines.prenom = ?, '
                    .'ressources_humaines.adresse = ?, '
                    .'ressources_humaines.salaire = ?, '
                    .'ressources_humaines.user_id = ? '
                    .'WHERE ressources_humaines.id = ?';

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
            $sql = 'INSERT INTO ressources_humaines '
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

        if($idInsert !== false && $id > 0)
        {
            $idInsert = $id;
        }

        return $idInsert;
    }

    /**
     * Delete the row corresponding to the given id.
     *
     * @param int $id The id of the RessourcesHumaines you want to delete.
     * @return bool True if the row has been deleted. False if not.
     */
    public static function deleteRessourcesHumaines($id)
    {
        $deleted = BaseSingleton::delete('delete from ressources_humaines where id = ?', array('i', &$id));

        return $deleted;
    }
}