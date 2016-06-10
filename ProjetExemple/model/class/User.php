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

class User
{
    ////////////////
    // ATTRIBUTES //
    ////////////////

    /**
     *
     * @var int
     */
    private $id;
    
    /**
     *
     * @var string
     */
    private $nom;  

    /**
     *
     * @var string
     */
    private $adresseEmail; 

    /**
     *
     * @var role
     */
    private $role;

    //////////////////
    // CONSTRUCTORS //
    //////////////////
    public function User($id = -1)
    {
        $this->id = $id;
        $this->nom = null;
        $this->adresseEmail = null;
        $this->role = null;
    }

    /////////////////////
    // GETTERS&SETTERS //
    /////////////////////
    public function setId($id)
    {
        if (is_int($id))
        {
            $this->id = $id;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setNom($nom)
    {
        if (is_string($nom))
        {
            $this->nom = $nom;
        }
    }

    public function getNom()
    {
        return $this->nom;
    }
    
    public function setAdresseEmail($adresseEmail)
    {
        if (is_string($adresseEmail))
        {
            $this->adresseEmail = $adresseEmail;
        }
    }

    public function getAdresseEmail()
    {
        return $this->adresseEmail;
    }    

    /**
     * Setter of the Role
     *
     * Accept its id, or the item
     *
     * @param Role $role
     */
    public function setRole($role)
    {
//        if (is_int($role))
//        {
//            $this->role = RoleDAL::findById($role);
//        }
//        else if (is_a($role, 'Role'))
//        {
//            $this->role = $role;
//        }
        
        $this->role = $role;
    }

    public function getRole()
    {
//        $role = null;
//
//        if (is_int($this->role))
//        {
//            $role = RoleDAL::findById($this->role);
//        }
//        else if (is_a($this->role, 'Role'))
//        {
//            $role = $this->role;
//        }
//
//        return $role;
        
        return $this->role;
    }

    //////////////
    // METHODES //
    //////////////

}