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

/**
 * Description of utilisateur
 *
 * @author lucile
 */
class utilisateur
{
    
    // ATTRIBUTS //

    private $id;
    private $pseudo;
    private $mdp;
    private $email;
    private $dateInscription;
    private $dateDerniereConnexion;
    private $plateformeInscription;
    private $commentaires;
    private $dateNaissance;
    private $reputation;
    private $reputations;

    // CONSTRUCTEUR DECONSTRUCTEUR //

    function __construct()
    {
        // Ne rien faire.
        // Modifiable.
    }

    function __destruct()
    {
        unset($this);
    }

    // GETTERS / SETTERS //

    function getReputation()
    {
        if (empty($this->reputation))
        {
            hydraterReputation();
        }
    }

    // A faire avec Netbeans.
    // HYDRATATION DE L'OBJET //

    function hydraterPseudo() {
        
    }

    function hydraterReputation() {
        $sql = 'select SUM(reputation)
        from reputation r
        join utilisateur u on u.ext_id_reputation = r.id
        where u.id = '.$id;
    }

    function hydraterAllReputation() {
        $sql = 'select réputation
        from réputation r
        join utilisateur u on u.ext_id_reputation = r.id
        where u.id = '.$id;
    }

    function hydraterChamp($nomChamp) {
        $requete = "Select $nomChamp from utilisateur";
        //result doit être du bon type dès le départ
        $result = "vraken@live.fr";
        $champConcerne = "\$this->\$$nomChamp=\$result;";

        echo $requete . "\n";
        echo $champConcerne;

        eval($champConcerne);
    }

    // METHODES //
}
