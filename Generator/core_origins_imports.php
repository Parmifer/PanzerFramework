<?php

/* 
 * Copyright (C) 2016 Parmifer
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

require_once('Core_Origins/functions/PanzerStringUtils.php');
require_once('Core_Origins/functions/PanzerSQLUtils.php');
require_once('Core_Origins/functions/PanzerSessionUtils.php');
require_once('Core_Origins/configuration/PanzerConfiguration.php');
require_once('Core_Origins/BaseSingleton.php');
require_once('Core_Origins/PanzerDAL.php');
require_once('Core_Origins/alerter/PanzerAlerter.php');
require_once('Core_Origins/alerter/PanzerAlert.php');
require_once('Core_Origins/logger/PanzerLogger.php');
require_once('Core_Origins/Syntaxalyser.php');
require_once('model/DAL/UserDAL.php');
require_once('model/DAL/RoleDAL.php');

// à générer automatiquement : si le type veut le calendrier.
$files = glob('Core_Origins/widget/calendar/*.php');
foreach ($files as $file) {
    require($file);
}