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

require_once(PanzerConfiguration::getProjectRoot().'model/DAL/ChatDAL.php');

class Salle
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
     * @var int
     */
    private $etage;

    /**
     *
     * @var string
     */
    private $numero;

    /**
     *
     * @var int
     */
    private $nombrePlaces;

    /**
     *
     * @var array of Chat
     */
    private $lesChat;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Create an empty object.
     */
    public function __construct()
    {
        $this->id = 0;
        $this->etage = 0;
        $this->numero = null;
        $this->nombrePlaces = 0;
        $this->lesChat = array();
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
     * Setter of etage.
     *
     * @param int $etage
     */
    public function setEtage($etage)
    {
        if (is_int($etage))
        {
            $this->etage = $etage;
        }
    }
    
    /**
     * Getter of etage.
     *
     * @return int
     */
    public function getEtage()
    {
        return $this->etage;
    }

    /**
     * Setter of numero.
     *
     * @param string $numero
     */
    public function setNumero($numero)
    {
        if (is_string($numero))
        {
            $this->numero = $numero;
        }
    }
    
    /**
     * Getter of numero.
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Setter of nombrePlaces.
     *
     * @param int $nombrePlaces
     */
    public function setNombrePlaces($nombrePlaces)
    {
        if (is_int($nombrePlaces))
        {
            $this->nombrePlaces = $nombrePlaces;
        }
    }
    
    /**
     * Getter of nombrePlaces.
     *
     * @return int
     */
    public function getNombrePlaces()
    {
        return $this->nombrePlaces;
    }

    /**
     * Setter of lesChat.
     *
     * @param array of Chat $lesChat
     */
    public function setLesChat($lesChat)
    {
        if (is_array($lesChat))
        {
            $this->lesChat = $lesChat;
        }
    }
    
    /**
     * Getter of lesChat.
     *
     * @return array of Chat
     */
    public function getLesChat()
    {
        return $this->lesChat;
    }
    
    /**
     * Add a Chat.
     *
     * @param Chat
     */
    public function addChat($chat)
    {
        if (is_a($chat, 'Chat'))
        {
            $this->lesChat[] = $chat;
        }
    }

    /////////////
    // METHODS //
    /////////////

    // Write your customs methods here !

}