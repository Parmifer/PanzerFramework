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

function premiereLettreMaj($string)
{
    return strtoupper(substr($string, 0, 1)) . substr($string, 1);
}

function convertBddEnCamelCase($string)
{
    $mots = explode('_', strtolower($string));
    
    $nbMots = count($mots);
    
    $camelCase = $mots[0];
    
    for($i = 1; $i < $nbMots; $i++)
    {
        $camelCase .= premiereLettreMaj($mots[$i]);
    }
    
    return $camelCase;
}

function formatBddPourEval($string)
{
    $mots = explode('_', strtolower($string));
    
    $nbMots = count($mots);
    $start = $mots[0] === 'ext' ? 1 : 0;
    
    $camelCase = '';
    
    for($i = $start; $i < $nbMots; $i++)
    {
        $camelCase .= premiereLettreMaj($mots[$i]);
    }
    
    return $camelCase;
}