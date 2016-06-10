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

require_once(PanzerConfiguration::getProjectRoot().'model/DAL/UserDAL.php');

$users      = UserDAL::findAll();
$parmifer   = UserDAL::findById(2);

if(isset($_SESSION['message']))
{
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}


echo '<pre>';
var_dump ($message);
echo '<br />';
var_dump($users);
echo '<br />';
var_dump($parmifer);
echo '</pre>';