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
 

require_once(PanzerConfiguration::getProjectRoot().'model/class/Role.php');

class RoleDAL extends PanzerDAL
{
    /**
     * Returns the Role which the id match with the given id.
     *
     * @param int $id The id of the searched Role.
     * @return mixed A Role. Null if not found.
     */
    public static function findById($id)
    {
        $params = array('i', &$id);
        $dataset = BaseSingleton::select('SELECT id, label, level, code FROM role WHERE id = ?', $params);

        $toReturn = self::handleResults($dataset);
        
        return $toReturn;
    }

    /**
     * Returns all the Role.
     *
     * @return mixed From zero to many Role.
     */
    public static function findAll()
    {
        $dataset = BaseSingleton::select('SELECT id, label, level, code FROM role');

        $toReturn = self::handleResults($dataset);
        
        return $toReturn;
    }

    /**
     * Create or edit a Role.
     *
     * @param Role $role
     * @return int id of the Role inserted/edited in base. False if it didn't work.
     */
    public static function persist($role)
    {
        $id = $role->getId();
        $label = $role->getLabel();
        $level = $role->getLevel();
        $code = $role->getCode();

        if ($id > 0)
        {
            $sql = 'UPDATE role SET '
                    .'role.label = ?, '
                    .'role.level = ?, '
                    .'role.code = ? '
                    .'WHERE role.id = ?';

            $params = array('sisi',
                &$label,
                &$level,
                &$code,
                &$id
            );
        }
        else
        {
            $sql = 'INSERT INTO role '
                    . '(label, level, code) '
                    . 'VALUES (? ,? ,?)';

            $params = array('sis',
                &$label,
                &$level,
                &$code
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
     * @param int $id The id of the Role you want to delete.
     * @return bool True if the row has been deleted. False if not.
     */
    public static function deleteRole($id)
    {
        $deleted = BaseSingleton::delete('delete from role where id = ?', array('i', &$id));

        return $deleted;
    }
}