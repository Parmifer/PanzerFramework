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

require_once(PanzerConfiguration::getProjectRoot().'model/DAL/RessourcesHumainesDAL.php');
require_once(PanzerConfiguration::getProjectRoot().'model/DAL/VeterinaireDAL.php');
require_once(PanzerConfiguration::getProjectRoot().'model/DAL/InfirmierDAL.php');
require_once(PanzerConfiguration::getProjectRoot().'model/DAL/RoleDAL.php');

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
     * @var int
     */
    private $roleId;

    /**
     *
     * @var RessourcesHumaines
     */
    private $ressourcesHumaines;

    /**
     *
     * @var array of Veterinaire
     */
    private $lesVeterinaire;

    /**
     *
     * @var array of Infirmier
     */
    private $lesInfirmier;

    /**
     *
     * @var Role
     */
    private $role;

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
        $this->roleId = 0;
        $this->ressourcesHumaines = null;
        $this->lesVeterinaire = array();
        $this->lesInfirmier = array();
        $this->role = null;
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
            $this->setRessourcesHumaines(RessourcesHumainesDAL::findByIdUser($id));
            $this->setVeterinaire(VeterinaireDAL::findByIdUser($id));
            $this->setInfirmier(InfirmierDAL::findByIdUser($id));
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
     * Setter of roleId.
     *
     * @param int $roleId
     */
    public function setRoleId($roleId)
    {
        if (is_int($roleId))
        {
            $this->roleId = $roleId;
            $this->role = RoleDAL::findById($roleId);
        }
    }

    /**
     * Getter of roleId.
     *
     * @return int
     */
    public function getRoleId()
    {
        return $this->roleId;
    }

    /**
     * Setter of ressourcesHumaines.
     *
     * @param RessourcesHumaines|int $ressourcesHumaines
     */
    public function setRessourcesHumaines($ressourcesHumaines)
    {
        if (is_a($ressourcesHumaines, 'RessourcesHumaines'))
        {
            $this->ressourcesHumaines = $ressourcesHumaines;
        }
        else if (is_int($ressourcesHumaines))
        {
            $this->ressourcesHumaines = RessourcesHumainesDAL::findByIdUser($this->id);
        }
    }

    /**
     * Getter of ressourcesHumaines.
     *
     * @return RessourcesHumaines
     */
    public function getRessourcesHumaines()
    {
        return $this->ressourcesHumaines;
    }

    /**
     * Setter of lesVeterinaire.
     *
     * @param array of Veterinaire $lesVeterinaire
     */
    public function setLesVeterinaire($lesVeterinaire)
    {
        if (is_array($lesVeterinaire))
        {
            $this->lesVeterinaire = $lesVeterinaire;
        }
    }

    /**
     * Getter of lesVeterinaire.
     *
     * @return array of Veterinaire
     */
    public function getLesVeterinaire()
    {
        return $this->lesVeterinaire;
    }

    /**
     * Add a Veterinaire.
     *
     * @param Veterinaire
     */
    public function addVeterinaire($veterinaire)
    {
        if (is_a($veterinaire, 'Veterinaire'))
        {
            $this->lesVeterinaire[] = $veterinaire;
        }
    }

    /**
     * Setter of lesInfirmier.
     *
     * @param array of Infirmier $lesInfirmier
     */
    public function setLesInfirmier($lesInfirmier)
    {
        if (is_array($lesInfirmier))
        {
            $this->lesInfirmier = $lesInfirmier;
        }
    }

    /**
     * Getter of lesInfirmier.
     *
     * @return array of Infirmier
     */
    public function getLesInfirmier()
    {
        return $this->lesInfirmier;
    }

    /**
     * Add a Infirmier.
     *
     * @param Infirmier
     */
    public function addInfirmier($infirmier)
    {
        if (is_a($infirmier, 'Infirmier'))
        {
            $this->lesInfirmier[] = $infirmier;
        }
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

    /////////////
    // METHODS //
    /////////////

    // Write your customs methods here !

}