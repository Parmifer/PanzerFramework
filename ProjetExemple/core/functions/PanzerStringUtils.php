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

class PanzerStringUtils
{

    public static function premiereLettreMaj($string)
    {
        return strtoupper(substr($string, 0, 1)) . substr($string, 1);
    }

    public static function convertBddEnCamelCase($string)
    {
        $mots = explode('_', strtolower($string));

        $nbMots = count($mots);

        $camelCase = $mots[0];

        for ($i = 1; $i < $nbMots; $i++)
        {
            $camelCase .= self::premiereLettreMaj($mots[$i]);
        }

        return $camelCase;
    }

    public static function convertToClassName($string)
    {
        return self::premiereLettreMaj(self::convertBddEnCamelCase($string));
    }

    public static function addEndingSlashIfNeeded(&$string)
    {
        // Si la chaîne ne fini pas pas '/', on le rajoute.
        if(substr($string, -1) !== '/')
        {
            $string .= '/';
        }
    }
    
    public static function getParentClassName($string)
    {
        $mots = explode('_', strtolower($string));
        
        return self::premiereLettreMaj($mots[0]);
    }
}