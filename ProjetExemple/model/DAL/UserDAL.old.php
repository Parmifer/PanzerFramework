<?php
/*
 * Copyright (C) 2015 Yann
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
     * Retourne l'utilisateur dont l'id est passé en paramètre.
     *
     * @param int $id L'id de l'utilisateur qu'on souhaite récupérer.
     * @return mixed De zéro à un utilisateur.
     */
    public static function findById($id)
    {
        $params = array('i', &$id);
        $dataset = BaseSingleton::select('SELECT * FROM user WHERE id = ?', $params);

        return  self::handleResults($dataset);
    }

    /**
     * Retourne tous les utilisateurs.
     *
     * @return mixed De zéro à un utilisateur.
     */
    public static function findAll()
    {
        $dataset = BaseSingleton::select('SELECT * FROM user');

        return self::handleResults($dataset);
    }

    /**
     * Retourne tous les utilisateurs ayant le rôle dont l'id est passé en paramètre.
     *
     * @param int $idRole L'id du rôle dont ont cherche les utilisateurs.
     * @return mixed De zéro à un utilisateur.
     */
    public static function findByRoleId($idRole)
    {
        $params = array('i', &$idRole);
        $dataset = BaseSingleton::select('SELECT * FROM user where ext_role = ?', $params);

        return self::handleResults($dataset);
    }

    /**
     * Create or edit a User.
     * 
     * @param User $user
     * @return int id of the User inserted in base. False if it didn't work.
     */
    public static function persist($user)
    {
        $userId = $user->getId();
        $login = $user->getLogin();
        $password = $user->getPassword();
        $mail = $user->getMail();
        $inscriptionDate = $user->getInscriptionDate();
        $firstName = $user->getFirstName();
        $lastName = $user->getLastName();
        $birthDate = $user->getBirthDate();
        $address = $user->getAddress();
        $phoneNumber = $user->getPhoneNumber();
        $avatar = $user->getAvatar();
        $role = $user->getRole()->getId();

        if ($userId > 0)
        {
            $sql = 'UPDATE user u SET '
                    . 'u.login = ?, '
                    . 'u.passwd = ?, '
                    . 'u.mail = ?, '
                    . 'u.inscription_date = ?, '
                    . 'u.first_name = ?, '
                    . 'u.last_name = ?, '
                    . 'u.birth_date = ?, '
                    . 'u.address = ?, '
                    . 'u.phone_number = ?, '
                    . 'u.avatar = ?, '
                    . 'u.ROLE_id_role = ? '
                    . 'WHERE u.id_user = ?';

            $params = array('ssssssssssii',
                &$login,
                &$password,
                &$mail,
                &$inscriptionDate,
                &$firstName,
                &$lastName,
                &$birthDate,
                &$address,
                &$phoneNumber,
                &$avatar,
                &$role,
                &$userId
            );
        }
        else
        {
            $sql = 'INSERT INTO user '
                    . '(login, passwd, mail, inscription_date, first_name, '
                    . 'last_name, birth_date, address, phone_number, avatar, ROLE_id_role) '
                    . 'VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

            $params = array('ssssssssssi',
                &$login,
                &$password,
                &$mail,
                &$inscriptionDate,
                &$firstName,
                &$lastName,
                &$birthDate,
                &$address,
                &$phoneNumber,
                &$avatar,
                &$role
            );
        }
        $idInsert = BaseSingleton::insertOrEdit($sql, $params);

        if($idInsert !== false && $userId > 0)
        {
            $idInsert = $userId;
        }

        return $idInsert;
    }
    
    /**
     * Delete the row corresponding to the given id.
     *
     * @param int $id
     * @return bool True if the row has been deleted. False if not.
     */
    public static function deleteUser($id)
    {
        $deleted = BaseSingleton::delete('delete from user where id_user = ?', array('i', &$id));
        return $deleted;
    }
}