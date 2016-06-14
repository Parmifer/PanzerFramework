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

/**
 * Description of PanzerControllerGenerator
 *
 * @author yann.butscher
 */
class PanzerControllerGenerator
{

    public function generateOneController($pageName, $pageInfos, $folder)
    {
        $camelCasePageName = PanzerStringUtils::convertToClassName($pageName);
        $parentClassName = PanzerStringUtils::getParentClassName($pageName);

        $generatedControllerFile = $folder . $camelCasePageName . 'Controller.php';
        $handle = fopen($generatedControllerFile, 'w') or die('Cannot open file:  ' . $generatedControllerFile);

        $newController = '<?php

/*
 * Copyright (C) ' . date('Y') . '
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

if (isset($_SESSION[\'user\']))
{
    $user = $_SESSION[\'user\'];

    if ($user->getRole()->getNiveau() >= PanzerSQLUtils::getNiveauRoleFromCode(\''.$pageInfos['droits'].'\'))
    {
        ';
        if($parentClassName != $camelCasePageName)
        {
            $newController .= 'require_once(\'view/page/' . $parentClassName . '/' . $camelCasePageName . '.phtml\');';
        }
        else
        {
            $newController .= 'require_once(\'view/page/' . $camelCasePageName . '.phtml\');';
        }
    $newController .= '
    }
    else
    {
        header(\'Location: Accueil\');
    }
}
else
{
    header(\'Location: login\');
}';
        // Writing the file.
        fwrite($handle, $newController);
        fclose($handle);
    }
}