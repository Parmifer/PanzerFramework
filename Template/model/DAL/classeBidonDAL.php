<?php
/*
 * Copyright (C) 2015 lucile
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
require_once('BaseSingleton.php');
require_once('../model/classeBidon.php');
class classeBidonDAL extends classeBidon {
    /**
     * Retourne l'objet correspondant à l'id donnée.
     * 
     * @param int $id Identifiant de l'objet à trouver
     * @return classeBion
     */
    public static function findById($id)
    {
        $data = BaseSingleton::select(
                        'SELECT id, nom, date_de_naissance, solde, vivant, ext_objet'
                        . 'FROM classe_bidon '
                        . 'WHERE id = ?', array('i', $id)
        );
        $objetBidon = new classeBion();
        $objetBidon->hydrate($data);
        return $objetBidon;
    }
    /**
     * Retourne tous les objets classeBidon.
     * 
     * @return mixed Tous les objets dans un tableau.
     */
    public static function findAll()
    {
        $mesObjets = array();
        $data = BaseSingleton::select(
                        'SELECT id, nom, date_de_naissance, solde, vivant, ext_objet'
                        . 'FROM classe_bidon '
        );
        foreach ($data as $row)
        {
            $objetBidon = new classeBion();
            $objetBidon->hydrate($row);
            $mesObjets[] = $objetBidon;
        }
        return $mesObjets;
    }
    /**
     * 
     * @param classeBidon $classeBidon
     * @return int L'id de l'objet inséré en base. False si ça a planté.
     */
    public static function insertOnDuplicate($classeBidon)
    {
        $sql = 'INSERT INTO classe_bidon ' .
                '(nom, date_de_naissance, ' .
                'solde, vivant, ext_objet) ' .
                'VALUES(?, ?, ?, ?, ?, ?) ' .
                'ON DUPLICATE KEY ' .
                'UPDATE nom = VALUES(nom), ' .
                'date_de_naissance = VALUES(date_de_naissance), ' .
                'solde = VALUES(solde), ' .
                'vivant = VALUES(vivant), ' .
                'ext_objet = VALUES(ext_objet)';
        $nom = $classeBidon->getNom();
        $dateDeNaissance = $classeBidon->getDateDeNaissance();
        $solde = $classeBidon->getSolde();
        $vivant = $classeBidon->getVivant();
        $objetId = $classeBidon->getObjet()->getId();
        $params = array('sidii', array(
                $nom,
                $dateDeNaissance,
                $solde,
                $vivant,
                $objetId,
        ));
        $idInsert = BaseSingleton::insertOrEdit($sql, $params);
        return $idInsert;
    }
    /**
     * Delete the row corresponding to the given id.
     * 
     * @param int $id
     * @return bool True if the row has been deleted. False if not.
     */
    public static function deleteClasseBidon($id)
    {
        $deleted = BaseSingleton::delete('delete from classe_bidon where id = ?', array('i', $id));
        return $deleted;
    }
}