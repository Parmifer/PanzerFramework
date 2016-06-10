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

// TODO : Remplacer les liens des require par $_SESSION['projectRoot']/chemin/vers/la/ressource
require_once($_SERVER['DOCUMENT_ROOT'].'/PanzerFramework/ProjetExemple/Core/BaseSingleton.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/PanzerFramework/ProjetExemple/Core/PanzerDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/PanzerFramework/ProjetExemple/model/Class/User.php');

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
        $dataset = BaseSingleton::select('SELECT * FROM user where ext_role = ?');
        
        return self::handleResults($dataset);
    }
}