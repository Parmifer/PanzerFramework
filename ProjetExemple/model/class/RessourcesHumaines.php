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


class RessourcesHumaines
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
    private $prenom;

    /**
     *
     * @var string
     */
    private $adresse;

    /**
     *
     * @var float
     */
    private $salaire;

    /**
     *
     * @var int
     */
    private $userId;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Create an empty object.
     */
    public function __construct()
    {
        $this->id = 0;
        $this->nom = null;
        $this->prenom = null;
        $this->adresse = null;
        $this->salaire = 0;
        $this->userId = 0;
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
     * Setter of nom.
     *
     * @param string $nom
     */
    public function setNom($nom)
    {
        if (is_string($nom))
        {
            $this->nom = $nom;
        }
    }

    /**
     * Getter of nom.
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Setter of prenom.
     *
     * @param string $prenom
     */
    public function setPrenom($prenom)
    {
        if (is_string($prenom))
        {
            $this->prenom = $prenom;
        }
    }

    /**
     * Getter of prenom.
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Setter of adresse.
     *
     * @param string $adresse
     */
    public function setAdresse($adresse)
    {
        if (is_string($adresse))
        {
            $this->adresse = $adresse;
        }
    }

    /**
     * Getter of adresse.
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Setter of salaire.
     *
     * @param float $salaire
     */
    public function setSalaire($salaire)
    {
        if (is_float($salaire))
        {
            $this->salaire = $salaire;
        }
    }

    /**
     * Getter of salaire.
     *
     * @return float
     */
    public function getSalaire()
    {
        return $this->salaire;
    }

    /**
     * Setter of userId.
     *
     * @param int $userId
     */
    public function setUserId($userId)
    {
        if (is_int($userId))
        {
            $this->userId = $userId;
            $this->user = UserDAL::findById($userId);
        }
    }

    /**
     * Getter of userId.
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /////////////
    // METHODS //
    /////////////

    // Write your customs methods here !

}