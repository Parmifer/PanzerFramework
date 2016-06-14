<?php

/*
 * Copyright (C) 2016 yann.butscher
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

require_once('../../core_imports.php');

session_start();

// REQUIRES
require_once(PanzerConfiguration::getProjectRoot() . 'model/DAL/UserDAL.php');

// Accessing the POST var in a safe way to prevent SQL injects.
$pseudo = filter_input(INPUT_POST, 'pseudo');
$password = filter_input(INPUT_POST, 'password');

// Checking the input parameters
if (empty($pseudo) && empty($password))
{
    // We redirect the user if they aren't all set
    // It means that someone is trying to access this page without authorisation.    
    header('Location: ../../login');
}
// Encrypting the password to compare it later.
$encryptedPassword = hash(hash_algos()[7], $password);
// We're trying to access to the user with the given login.
$user = UserDAL::findByPseudo($pseudo);
// Case 1 : The login doesn't exist;
if ($user === null)
{
    // We're saving the error message.
    PanzerAlerter::alert(PanzerAlerter::LEVEL_ERROR, 'This pseudo doesn\'t exist!');
    // Redirecting.
    header('Location: ../../login');
}
// Case 2 : The password doesn't match.
else if ($user->getPassword() !== $encryptedPassword)
{
    // We're saving the error message.
    PanzerAlerter::alert(PanzerAlerter::LEVEL_ERROR, 'Wrong password! Try again.');
    // Redirecting.
    header('Location: ../../login');
}
// Case 3 : Everything is ok, the user is logged.
else
{
    // We're saving the success message.    
    $message = 'You are connected. Welcome ' . $user->getPseudo() . '!';

    PanzerAlerter::alert(PanzerAlerter::LEVEL_SUCCESS, $message);
    // We're saving the user informations.
    $_SESSION['user'] = $user;

    // Redirecting.
    header('Location: ../Accueil');
}