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

require_once('PanzerClassGenerator.php');

/**
 * Description of PanzerGenerator
 *
 * @author Parmifer
 */
class PanzerGenerator
{
    private $options;
	private $tablesPourGeneration;
    private $panzerClassGenerator;

    /**
     * Constructor
     */
    function __construct($options)
    {
        $this->options = json_decode($options, true);
		
		// echo('<pre>');
		// var_dump($this->options);
		// echo('</pre>');
    }

    /**
     * Génère toutes les classes à partir des options choisies par l'utilisateur.
     */
    public function generateClasses()
    {        
        $this->panzerClassGenerator = new PanzerClassGenerator();        
        
        // echo('<pre>');
        // var_dump($this->options['tables_a_transformer']);
        // echo('</pre>');
        
        foreach ($this->options['tables_a_transformer'] as $uneTable => $infos)
        {  	
            $this->panzerClassGenerator->generateOneClass($uneTable, $infos['relations'], '../ProjetExemple/model/Class/');
            
            echo 'Table : ' . $uneTable . ' ok ! <br />';
        }
    }
}   