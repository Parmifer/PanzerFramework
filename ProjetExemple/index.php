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

require_once 'core/start/PanzerStart.php';

PanzerStart::Start();
$alerts = PanzerAlerter::getAlerts();
?>
<!DOCTYPE html>
<html>
    <head lang=en>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Index - PanzerTest</title>

        <!-- CSS -->
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="core/library/bootstrap/css/bootstrap.min.css">
        <!-- Bootstrap theme -->
        <!--<link rel="stylesheet" href="core/library/bootstrap/css/bootstrap-theme.min.css">-->
        <!-- Theme>
        <!-- Custom css -->


        <!-- JAVASCRIPT -->
        <!-- JQuery -->
        <script src="core/library/jquery/jquery-2.2.4.min.js"></script>
        <!--<script src="core/library/jquery/jquery.validate.min.js"></script>-->
        <!-- Latest compiled and minified JavaScript -->
        <script src="core/library/bootstrap/js/bootstrap.min.js"></script>
    </head>
    <body>


        <!-- Nav bar for unconnected user -->
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="?page=home">PANZER Application</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <?php if (!PanzerSessionUtils::isSessionOpened()) : ?>
                        <ul class="nav navbar-nav navbar-right">
                            <li>
                                <form class="navbar-form" action="index.php" method="post">
                                    <div class="form-group">
                                        <input type="text" class="form-control"name="login" placeholder="Username">
                                        <input type="password" class="form-control" name="password" placeholder="Password">
                                    </div>
                                    <button type="submit" class="btn btn-default">Sign In</button>
                                </form>
                            </li>
                            <li>
                                <button type="button" id="register-button-link" class="btn btn-default navbar-btn">Register</button>
                            </li>
                        </ul>
                    <?php else : ?>
                        <ul class="nav navbar-nav navbar-right">
                            <li>
                                <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <?= PanzerSessionUtils::getConnectedUser()->getPseudo() ?> <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="?page=profile">Profile</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li>
                                        <li><a href="?page=logout">Sign out</a></li>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </nav>



        <div class="page-header col-sm-offset-2 col-sm-8">
            <h1 class="white chalk"><em>PanzerFramework</em></h1>
        </div>
        <?php if ($alerts !== null): ?>
            <?php foreach ($alerts as $alert): ?>
                <div class="alert <?= $alert->getLevel(); ?> alert-dismissible col-sm-offset-3 col-sm-6" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?= $alert->getMessage(); ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php
        // Safe access to the data
        $class = filter_input(INPUT_GET, 'class');
        $page = filter_input(INPUT_GET, 'page');

        if (!empty($page) && !empty($class) && file_exists('controller/page/' . $class . '/' . $page . '.php'))
        {
            require_once('controller/' . $page . '/' . $page . '_' . $action . '.php');
        }
        else if (isset($page) && file_exists('controller/page/' . $page . '.php'))
        {
            require_once('controller/page/' . $page . '.php');
        }
        ?>
    </body>
</html>