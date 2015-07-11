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
 * Description of classeBidon
 *
 * @author lucile
 */
class classeBidon {
    
    ///////////////
    // ATTRIBUTS //
    ///////////////
    
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
     * @var datetime 
     */
    private $dateDeNaissance;
    
    /**
     *
     * @var double 
     */
    private $solde;
    
    /**
     *
     * @var boolean 
     */
    private $vivant;
    
    ///////////////////
    // CONSTRUCTEURS //
    ///////////////////
           
    public function classeBidon($id=-1, $nom='', $dateDeNaissance=null, $solde=0, $vivant=false)
    {
        $this->id               = $id;
        $this->nom              = $nom;
        $this->dateDeNaissance  = $dateDeNaissance;
        $this->solde            = $solde;
        $this->vivant           = $vivant;        
    }
    
    /////////////////////
    // GETTERS&SETTERS //
    /////////////////////
    
    public function setId($id)
    {
        if(typeof($id) == int)
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
        if(typeof($nom) == string)
        {
            $this->nom = $nom;
        }
    }
    
    public function getNom()
    {
        return $this->nom;
    }
    
    public function setDateDeNaissance($dateDeNaissance)
    {
        if(typeof($dateDeNaissance) == DateTime)
        {
            $this->dateDeNaissance = $dateDeNaissance;
        }
    }
    
    public function getDateDeNaissance()
    {
        return $this->dateDeNaissance;
    }
    
    public function setSolde($solde)
    {
        if(typeof($solde) == double)
        {
            $this->solde = $solde;
        }
    }
    
    public function getSolde()
    {
        return $this->solde;
    }
    
    public function setVivant($vivant)
    {
        if(typeof($vivant) == boolean)
        {
            $this->vivant = $vivant;
        }
    }
    
    public function getVivant()
    {
        return $this->vivant;
    }
    
    //////////////
    // METHODES //
    //////////////
    
    public function hydrate()
    {
        
    }
    
    /*
     * Ajoutez vos méthodes personnalisées ici !
     */
}
