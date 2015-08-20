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

// Generator's initialisation
if(isset($argv[1]))
{
    switch(strtolower($argv[1]))
    {
        case 'help':
            showHelp();
            break;
        case 'man':
            showHelp();
            break;
        case '?':
            showHelp();
            break;
        case 'generate':
        {
            if(isset($argv[2]))
            {
                $dataBaseName = $argv[2];                
                if(isset($argv[3]) && $argv[3] !== 'all')
                {
                    $tables = array($argv[3]);
                    $i = 4;
                    while(isset($argv[$i]))
                    {
                        $tables[] = $argv[$i++];                    
                    }
                    
                    generateEverything($tables);
                }
                else if($argv[3] === 'all')
                {
                    generateEverything('all');
                }
                else
                {
                    showTip();
                }
            }
            else
            {
                showTip();
            }
            break;
        }
        default:
            showTip();
            break;
    }   
}

function showTip()
{
    echo "\r\nOups... Something really bad happened. You sould try again.\r\n";
    echo "Type 'help' for a complete documentation!";
}

function showHelp()
{
    echo "\r\nHow to use:\r\n";    
    
    echo "generate [all | table1 [tableN]*]\r\n\r\n";
    
    echo "all:      If you want to generate a whole database.\r\n";
    echo "          Recommended for new applications.\r\n\r\n";
    
    echo "table1:   First table to generate.\r\n";
    echo "          Usefull when you want to regenerate a specific object.\r\n\r\n";
    
    echo "tableN:   An other table to generate.\r\n";
    echo "          You can generate as many table as you want.\r\n\r\n";
}

function generateEverything($toGenerate)
{
    generateDAL($toGenerate);
    generateClass($toGenerate);
}

