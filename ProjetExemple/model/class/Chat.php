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

require_once(PanzerConfiguration::getProjectRoot().'model/DAL/DiagnostiquerDAL.php');
require_once(PanzerConfiguration::getProjectRoot().'model/DAL/OpererDAL.php');

class Chat
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
    private $adresse;

    /**
     *
     * @var string
     */
    private $reference;

    /**
     *
     * @var string
     */
    private $etat;

    /**
     *
     * @var int
     */
    private $salleId;

    /**
     *
     * @var array of Diagnostiquer
     */
    private $lesDiagnostiquer;

    /**
     *
     * @var array of Operer
     */
    private $lesOperer;

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
        $this->adresse = null;
        $this->reference = null;
        $this->etat = null;
        $this->salleId = 0;
        $this->lesDiagnostiquer = array();
        $this->lesOperer = array();
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
     * Setter of reference.
     *
     * @param string $reference
     */
    public function setReference($reference)
    {
        if (is_string($reference))
        {
            $this->reference = $reference;
        }
    }
    
    /**
     * Getter of reference.
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Setter of etat.
     *
     * @param string $etat
     */
    public function setEtat($etat)
    {
        if (is_string($etat))
        {
            $this->etat = $etat;
        }
    }
    
    /**
     * Getter of etat.
     *
     * @return string
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Setter of salleId.
     *
     * @param int $salleId
     */
    public function setSalleId($salleId)
    {
        if (is_int($salleId))
        {
            $this->salleId = $salleId;
            $this->salle = SalleDAL::findById($salleId);
        }
    }
    
    /**
     * Getter of salleId.
     *
     * @return int
     */
    public function getSalleId()
    {
        return $this->salleId;
    }

    /**
     * Setter of lesDiagnostiquer.
     *
     * @param array of Diagnostiquer $lesDiagnostiquer
     */
    public function setDiagnostiquer($lesDiagnostiquer)
    {
        if (is_array($lesDiagnostiquer))
        {
            $this->lesDiagnostiquer = $lesDiagnostiquer;
        }
    }
    
    /**
     * Getter of lesDiagnostiquer.
     *
     * @return array of Diagnostiquer
     */
    public function getDiagnostiquer()
    {
        return $this->lesDiagnostiquer;
    }
    
    /**
     * Add a Diagnostiquer.
     *
     * @param Diagnostiquer
     */
    public function addDiagnostiquer($lesDiagnostiquer)
    {
        if (is_a($lesDiagnostiquer, 'Diagnostiquer'))
        {
            $this->lesDiagnostiquer[] = $lesDiagnostiquer;
        }
    }

    /**
     * Setter of lesOperer.
     *
     * @param array of Operer $lesOperer
     */
    public function setOperer($lesOperer)
    {
        if (is_array($lesOperer))
        {
            $this->lesOperer = $lesOperer;
        }
    }
    
    /**
     * Getter of lesOperer.
     *
     * @return array of Operer
     */
    public function getOperer()
    {
        return $this->lesOperer;
    }
    
    /**
     * Add a Operer.
     *
     * @param Operer
     */
    public function addOperer($lesOperer)
    {
        if (is_a($lesOperer, 'Operer'))
        {
            $this->lesOperer[] = $lesOperer;
        }
    }

    /////////////
    // METHODS //
    /////////////

    // Write your customs methods here !

}