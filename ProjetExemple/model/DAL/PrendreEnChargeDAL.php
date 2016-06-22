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
 


class PrendreEnChargeDAL extends PanzerDAL
{

    /**
     * Create a new association between one Chat and one Infirmier.
     *
     * @param int $idChat The id of the Chat.
     * @param int $idInfirmier The id of the Infirmier.
     * @return bool True is the creation worked. False otherwise.
     */
    public static function createAssociation($idChat, $idInfirmier)
    {
        $sql = 'INSERT INTO prendre_en_charge '
                . '(chat_id, infirmier_id)'
                . 'VALUES (?, ?)';
        $params = array('ii', &$idChat, &$idInfirmier);
        $itWorked = BaseSingleton::insertOrEdit($sql, $params);

        return $itWorked !== false;
    }

    /**
     * Delete an association between one Chat and one Infirmier.
     *
     * @param int $idChat The id of the Chat.
     * @param int $idInfirmier The id of the Infirmier.
     * @return bool True is the association was deleted. False otherwise.
     */
    public static function deleteAssociation($idChat, $idInfirmier)
    {
        $sql = 'DELETE FROM prendre_en_charge WHERE chat_id = ? AND infirmier_id = ?';
        $params = array('ii', &$idChat, &$idInfirmier);
        $deleted = BaseSingleton::delete($sql, $params);

        return $deleted;
    }
}