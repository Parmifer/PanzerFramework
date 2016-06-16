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
    
require_once(PanzerConfiguration::getProjectRoot().'model/class/Salle.php');

class SalleDAL extends PanzerDAL
{
    /**
     * Returns the Salle which the id match with the given id.
     *
     * @param int $id The id of the searched Salle.
     * @return mixed A Salle. Null if not found.
     */
    public static function findById($id)
    {
        $params = array('i', &$id);
        $dataset = BaseSingleton::select('SELECT id, etage, numero, nombre_places FROM salle WHERE id = ?', $params);

        return self::handleResults($dataset);
    }

    /**
     * Returns all the Salle.
     *
     * @return mixed From zero to many Salle.
     */
    public static function findAll()
    {
        $dataset = BaseSingleton::select('SELECT id, etage, numero, nombre_places FROM salle');

        return self::handleResults($dataset);
    }

    /**
     * Create or edit a Salle.
     *
     * @param Salle $salle
     * @return int id of the Salle inserted/edited in base. False if it didn't work.
     */
    public static function persist($salle)
    {
        $id = $salle->getId();
        $etage = $salle->getEtage();
        $numero = $salle->getNumero();
        $nombrePlaces = $salle->getNombrePlaces();

        if ($id > 0)
        {
            $sql = 'UPDATE salle SET '
                    .'salle.etage = ?, '
                    .'salle.numero = ?, '
                    .'salle.nombre_places = ? '
                    .'WHERE salle.id = ?';

            $params = array('isii',
                &$etage,
                &$numero,
                &$nombrePlaces,
                &$id
            );
        }
        else
        {
            $sql = 'INSERT INTO salle '
                    . '(etage, numero, nombre_places) '
                    . 'VALUES (? ,? ,?)';

            $params = array('isi',
                &$etage,
                &$numero,
                &$nombrePlaces
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
     * @param int $id The id of the Salle you want to delete.
     * @return bool True if the row has been deleted. False if not.
     */
    public static function deleteSalle($id)
    {
        $deleted = BaseSingleton::delete('delete from salle where id = ?', array('i', &$id));

        return $deleted;
    }
}