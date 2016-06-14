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

require_once(PanzerConfiguration::getProjectRoot().'model/DAL/RoleDAL.php');
// require_once(PanzerConfiguration::getProjectRoot().'model/DAL/CanardDAL.php');

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
    private $pseudo;

    /**
     *
     * @var string
     */
    private $password;

    /**
     *
     * @var DateTime
     */
    private $creationDate;

    /**
     *
     * @var Role
     */
    private $role;

    /**
     *
     * @var Canard
     */
    private $canard;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Create an empty object.
     */
    public function __construct()
    {
        $this->id = 0;
        $this->pseudo = null;
        $this->password = null;
        $this->creationDate = null;
        $this->role = null;
        $this->canard = null;
    }

    ///////////////////////
    // GETTERS & SETTERS //
    ///////////////////////

    /**
     * Setter of id.
     *
     * @param int $id
     */
    public function setId($id)
    {
        if (is_int($id))
        {
            $this->id = $id;
        }
    }

    /**
     * Getter of id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Setter of pseudo.
     *
     * @param string $pseudo
     */
    public function setPseudo($pseudo)
    {
        if (is_string($pseudo))
        {
            $this->pseudo = $pseudo;
        }
    }

    /**
     * Getter of pseudo.
     *
     * @return string
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * Setter of password.
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        if (is_string($password))
        {
            $this->password = $password;
        }
    }

    /**
     * Getter of password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Setter of creationDate.
     *
     * @param DateTime $creationDate
     */
    public function setCreationDate($creationDate)
    {
        if (is_DateTime($creationDate))
        {
            $this->creationDate = $creationDate;
        }
    }

    /**
     * Getter of creationDate.
     *
     * @return DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Setter of role.
     *
     * @param Role|int $role
     */
    public function setRole($role)
    {
        if (is_a($role, 'Role'))
        {
            $this->role = $role;
        }
        else if (is_int($role))
        {
            $this->role = RoleDAL::findById($role);
        }
    }

    /**
     * Getter of role.
     *
     * @return Role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Setter of canard.
     *
     * @param Canard|int $canard
     */
    public function setCanard($canard)
    {
        if (is_a($canard, 'Canard'))
        {
            $this->canard = $canard;
        }
        else if (is_int($canard))
        {
            $this->canard = CanardDAL::findById($canard);
        }
    }

    /**
     * Getter of canard.
     *
     * @return Canard
     */
    public function getCanard()
    {
        return $this->canard;
    }

    /////////////
    // METHODS //
    /////////////

    // Write your customs methods here !

}