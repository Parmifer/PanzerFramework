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

class PanzerSessionUtils
{

    /**
     * Open a new session if there is no one already opened
     */
    static public function openSession()
    {
        if (!self::isSessionOpened())
        {
            $userName = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
            $password = openssl_digest(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING), 'sha512');

            if (!empty($userName) && !empty($password))
            {
                $_SESSION['user'] = UserDAL::verifyConnection($userName, $password);
            }
        }
    }

    static public function isSessionOpened()
    {
        return isset($_SESSION['user']);
    }

    static public function getConnectedUser()
    {
        if (isset($_SESSION['user']))
        {
            return $_SESSION['user'];
        }
        else
        {
            return false;
        }
    }

}
