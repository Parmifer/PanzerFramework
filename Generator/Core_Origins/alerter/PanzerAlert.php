<?php

/*
 * Copyright (C) 2016 lucile
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
 * Object containing Level and message, what can be displayed to the user.
 *
 * @author lucile
 */
class PanzerAlert
{

    private $level;
    private $message;

    public function __construct($level = null, $message = null)
    {
        $this->level = $level;
        $this->message = $message;
    }

    function getLevel()
    {
        return $this->level;
    }

    function getMessage()
    {
        return $this->message;
    }

    function setLevel($level)
    {
        $this->level = $level;
    }

    function setMessage($message)
    {
        $this->message = $message;
    }

}
