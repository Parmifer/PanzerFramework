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

require_once(PanzerConfiguration::getProjectRoot().'model/DAL/VeterinaireDAL.php');

class Diagnostiquer
{
    ////////////////
    // ATTRIBUTES //
    ////////////////

    /**
     *
     * @var int
     */
    private $chatId;

    /**
     *
     * @var int
     */
    private $veterinaireId;

    /**
     *
     * @var DateTime
     */
    private $date;

    /**
     *
     * @var string
     */
    private $diagnostic;

    /**
     *
     * @var Veterinaire
     */
    private $veterinaire;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Create an empty object.
     */
    public function __construct()
    {
        $this->chatId = 0;
        $this->veterinaireId = 0;
        $this->date = null;
        $this->diagnostic = null;
        $this->veterinaire = null;
    }

    ///////////////////////
    // GETTERS & SETTERS //
    ///////////////////////

    /**
     * Setter of chatId.
     *
     * @param int $chatId
     */
    public function setChatId($chatId)
    {
        if (is_int($chatId))
        {
            $this->chatId = $chatId;
            $this->chat = ChatDAL::findById($chatId);
        }
    }

    /**
     * Getter of chatId.
     *
     * @return int
     */
    public function getChatId()
    {
        return $this->chatId;
    }

    /**
     * Setter of veterinaireId.
     *
     * @param int $veterinaireId
     */
    public function setVeterinaireId($veterinaireId)
    {
        if (is_int($veterinaireId))
        {
            $this->veterinaireId = $veterinaireId;
            $this->veterinaire = VeterinaireDAL::findById($veterinaireId);
        }
    }

    /**
     * Getter of veterinaireId.
     *
     * @return int
     */
    public function getVeterinaireId()
    {
        return $this->veterinaireId;
    }

    /**
     * Setter of date.
     *
     * @param DateTime $date
     */
    public function setDate($date)
    {
        if (is_DateTime($date))
        {
            $this->date = $date;
        }
    }

    /**
     * Getter of date.
     *
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Setter of diagnostic.
     *
     * @param string $diagnostic
     */
    public function setDiagnostic($diagnostic)
    {
        if (is_string($diagnostic))
        {
            $this->diagnostic = $diagnostic;
        }
    }

    /**
     * Getter of diagnostic.
     *
     * @return string
     */
    public function getDiagnostic()
    {
        return $this->diagnostic;
    }

    /**
     * Setter of veterinaire.
     *
     * @param Veterinaire|int $veterinaire
     */
    public function setVeterinaire($veterinaire)
    {
        if (is_a($veterinaire, 'Veterinaire'))
        {
            $this->veterinaire = $veterinaire;
        }
        else if (is_int($veterinaire))
        {
            $this->veterinaire = VeterinaireDAL::findById($veterinaire);
        }
    }

    /**
     * Getter of veterinaire.
     *
     * @return Veterinaire
     */
    public function getVeterinaire()
    {
        return $this->veterinaire;
    }

    /////////////
    // METHODS //
    /////////////

    // Write your customs methods here !

}