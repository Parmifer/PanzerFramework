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

require_once('core_imports.php');
require_once('model/class/User.php');

session_start();

PanzerConfiguration::loadConfiguration();

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
        <link rel="stylesheet" href="../../core/library/bootstrap/css/bootstrap.min.css">
        <!-- Bootstrap theme -->
        <link rel="stylesheet" href="../../core/library/bootstrap/css/bootstrap-theme.min.css">
        <!-- Theme>
        <!-- Custom css -->
       

        <!-- JAVASCRIPT -->
        <!-- JQuery -->
        <script src="../../core/library/jquery/jquery-2.2.4.min.js"></script>
        <script src="../../core/library/jquery/jquery.validate.min.js"></script>
        <!-- Latest compiled and minified JavaScript -->
        <script src="../../core/library/bootstrap/js/bootstrap.min.js"></script>
    </head>
    <body>        
        <div class="page-header col-sm-offset-2 col-sm-8">
            <h1 class="white chalk"><em>PanzerFramework</em></h1>
        </div>
        <?php 
        $alerts = PanzerAlerter::getAlerts();
        ?>
        <?php if ($alerts !== null): ?>
            <?php foreach ($alerts as $alert): ?>
                <div class="alert <?= $alert->getLevel(); ?> alert-dismissible col-sm-offset-3 col-sm-6" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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
        else
        {
            header('Location: Accueil');
        }
        ?>
    </body>
</html>