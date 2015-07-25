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

class classeBidonDAL extends classeBidon
{
    /**
     * Retourne l'objet correspondant à l'id donnée.
     * 
     * @param int $id Identifiant de l'objet à trouver
     * @return classeBion
     */
    public static function findById($id)
    {
        $data = BBD::select('SELECT id, nom, date_de_naissance, solde, vivant, ext_objet'
                          . 'FROM classe_bidon '
                          . 'WHERE id = ?', array('i', $id));
        
        $objetBidon = new classeBion();
        
        $objetBidon->hydrate($data);
        
        return $objetBidon;
    }
}