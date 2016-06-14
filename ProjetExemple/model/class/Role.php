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


class Role
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
    private $label;

    /**
     *
     * @var string
     */
    private $clef;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Create an empty object.
     */
    public function __construct()
    {
        $this->id = 0;
        $this->label = null;
        $this->clef = null;
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
     * Setter of label.
     *
     * @param string $label
     */
    public function setLabel($label)
    {
        if (is_string($label))
        {
            $this->label = $label;
        }
    }

    /**
     * Getter of label.
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Setter of clef.
     *
     * @param string $clef
     */
    public function setClef($clef)
    {
        if (is_string($clef))
        {
            $this->clef = $clef;
        }
    }

    /**
     * Getter of clef.
     *
     * @return string
     */
    public function getClef()
    {
        return $this->clef;
    }

    /////////////
    // METHODS //
    /////////////

    // Write your customs methods here !

}