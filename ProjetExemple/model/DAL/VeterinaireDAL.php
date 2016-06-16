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

require_once(PanzerConfiguration::getProjectRoot().'model/class/Veterinaire.php');

class VeterinaireDAL extends PanzerDAL
{
    /**
     * Returns the Veterinaire which the id match with the given id.
     *
     * @param int $id The id of the searched Veterinaire.
     * @return mixed A Veterinaire. Null if not found.
     */
    public static function findById($id)
    {
        $params = array('i', &$id);
        $dataset = BaseSingleton::select('SELECT id, nom, prenom, adresse, salaire, user_id FROM veterinaire WHERE id = ?', $params);

        return self::handleResults($dataset);
    }

    /**
     * Returns all the Veterinaire.
     *
     * @return mixed From zero to many Veterinaire.
     */
    public static function findAll()
    {
        $dataset = BaseSingleton::select('SELECT id, nom, prenom, adresse, salaire, user_id FROM veterinaire');

        return self::handleResults($dataset);
    }

    /**
     * Returns all the Veterinaire where the user_id match with the given id.
     *
     * @param int $idUser The id of the User.
     * @return array One or many Veterinaire. Null if not found.
     */
    public static function findByIdUser($idUser)
    {
        $params = array('i', &$idUser);
        $dataset = BaseSingleton::select('SELECT id, nom, prenom, adresse, salaire, user_id FROM veterinaire WHERE user_id = ?', $params);

        return self::handleResults($dataset);
    }

    /**
     * Create or edit a Veterinaire.
     *
     * @param Veterinaire $veterinaire
     * @return int id of the Veterinaire inserted/edited in base. False if it didn't work.
     */
    public static function persist($veterinaire)
    {
        $id = $veterinaire->getId();
        $nom = $veterinaire->getNom();
        $prenom = $veterinaire->getPrenom();
        $adresse = $veterinaire->getAdresse();
        $salaire = $veterinaire->getSalaire();
        $userId = $veterinaire->getUserId();

        if ($id > 0)
        {
            $sql = 'UPDATE veterinaire SET '
                    .'veterinaire.nom = ?, '
                    .'veterinaire.prenom = ?, '
                    .'veterinaire.adresse = ?, '
                    .'veterinaire.salaire = ?, '
                    .'veterinaire.user_id = ? '
                    .'WHERE veterinaire.id = ?';

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
            $sql = 'INSERT INTO veterinaire '
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
     * @param int $id The id of the Veterinaire you want to delete.
     * @return bool True if the row has been deleted. False if not.
     */
    public static function deleteVeterinaire($id)
    {
        $deleted = BaseSingleton::delete('delete from veterinaire where id = ?', array('i', &$id));

        return $deleted;
    }
}