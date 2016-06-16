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
    
require_once(PanzerConfiguration::getProjectRoot().'model/class/User.php');

class UserDAL extends PanzerDAL
{
    /**
     * Returns the User which the id match with the given id.
     *
     * @param int $id The id of the searched User.
     * @return mixed A User. Null if not found.
     */
    public static function findById($id)
    {
        $params = array('i', &$id);
        $dataset = BaseSingleton::select('SELECT id, pseudo, password, creation_date, role_id FROM user WHERE id = ?', $params);

        return self::handleResults($dataset);
    }

    /**
     * Returns all the User.
     *
     * @return mixed From zero to many User.
     */
    public static function findAll()
    {
        $dataset = BaseSingleton::select('SELECT id, pseudo, password, creation_date, role_id FROM user');

        return self::handleResults($dataset);
    }
                
    /**
     * Returns the User matching with the given pseudo.
     *
     * @params string $pseudo The searched User's pseudo.
     * @return User The matched User.
     */
    public static function findByPseudo($pseudo)
    {
        $params = array('s', &$pseudo);
        $dataset = BaseSingleton::select('SELECT id, pseudo, password, creation_date, role_id FROM user WHERE pseudo = ?', $params);

        return self::handleResults($dataset);
    }
                    
    /**
     * Returns the User which the role_id match with the given id.
     *
     * @param int $idRole The id of the Role.
     * @return mixed A User. Null if not found.
     */
    public static function findByIdRole($idRole)
    {
        $params = array('i', &$idRole);
        $dataset = BaseSingleton::select('SELECT id, pseudo, password, creation_date, role_id FROM user WHERE role_id = ?', $params);

        return self::handleResults($dataset);
    }

    /**
     * Create or edit a User.
     *
     * @param User $user
     * @return int id of the User inserted/edited in base. False if it didn't work.
     */
    public static function persist($user)
    {
        $id = $user->getId();
        $pseudo = $user->getPseudo();
        $password = $user->getPassword();
        $creationDate = $user->getCreationDate();
        $roleId = $user->getRoleId();

        if ($id > 0)
        {
            $sql = 'UPDATE user SET '
                    .'user.pseudo = ?, '
                    .'user.password = ?, '
                    .'user.creation_date = ?, '
                    .'user.role_id = ? '
                    .'WHERE user.id = ?';

            $params = array('sssii',
                &$pseudo,
                &$password,
                &$creationDate,
                &$roleId,
                &$id
            );
        }
        else
        {
            $sql = 'INSERT INTO user '
                    . '(pseudo, password, creation_date, role_id) '
                    . 'VALUES (? ,? ,? ,?)';

            $params = array('sssi',
                &$pseudo,
                &$password,
                &$creationDate,
                &$roleId
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
     * @param int $id The id of the User you want to delete.
     * @return bool True if the row has been deleted. False if not.
     */
    public static function deleteUser($id)
    {
        $deleted = BaseSingleton::delete('delete from user where id = ?', array('i', &$id));

        return $deleted;
    }
}