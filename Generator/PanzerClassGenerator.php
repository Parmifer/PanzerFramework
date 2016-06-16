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

class PanzerClassGenerator
{
    private $attributes;
    private $includes;

    public function generateOneClass($table, $attributes, $folder)
    {
        // Usefull vars
        $className = PanzerStringUtils::convertToClassName($table);
        $this->includes = array();

        foreach ($attributes as $attribut)
        {
            switch ($attribut['storage'])
            {
                case 'object':
                case 'array':
                case 'manyToMany':
                    $this->includes[] = 'PanzerConfiguration::getProjectRoot().\'model/DAL/' . $attribut['toUpperName'] . 'DAL.php';
                    break;
            }

            if ($attribut['isPrimaryKey'])
            {
                $primaryKey = $attribut;
            }
        }

        $generatedClassFile = $folder . $className . '.php';
        $handle = fopen($generatedClassFile, 'w') or die('Cannot open file:  ' . $generatedClassFile);

        $maClasse = '<?php

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
';

        foreach ($this->includes as $fileToInclude)
        {
            $maClasse .= '
require_once(' . $fileToInclude . '\');';
        }

        $maClasse .= '

class ' . $className .
                '
{
    ////////////////
    // ATTRIBUTES //
    ////////////////';

        foreach ($attributes as $attribut)
        {
            $maClasse .=
                    '

    /**
     *
     * @var ' . ($attribut['storage'] === 'array' || $attribut['storage'] === 'manyToMany' ? 'array of ' : '') . $attribut['phpType'] . '
     */
    private $' . $attribut['attributName'] . ';';
        }

        $maClasse .=
                '

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Create an empty object.
     */
    public function __construct()
    {';
        foreach ($attributes as $attribut)
        {
            if ($attribut['storage'] === 'array')
            {
                $maClasse .= '
        $this->' . $attribut['attributName'] . ' = array();';
            }
            else
            {
                $maClasse .= '
        $this->' . $attribut['attributName'] . ' = ' . (($attribut['phpType'] === 'int' || $attribut['phpType'] === 'float') ? '0' : 'null') . ';';
            }
        }

        $maClasse .=
                '
    }

    ///////////////////////
    // GETTERS & SETTERS //
    ///////////////////////';

        foreach ($attributes as $attribut)
        {
            switch ($attribut['storage'])
            {
                case 'object':
                    if ($attribut['foreignKeyInClass'])
                    {
                        $maClasse .=
                                '

    /**
     * Setter of ' . $attribut['attributName'] . '.
     *
     * @param ' . $attribut['phpType'] . '|int $' . $attribut['attributName'] . '
     */
    public function set' . $attribut['toUpperName'] . '($' . $attribut['attributName'] . ')
    {
        if (is_a($' . $attribut['attributName'] . ', \'' . $attribut['phpType'] . '\'))
        {
            $this->' . $attribut['attributName'] . ' = $' . $attribut['attributName'] . ';
        }
        else if (is_int($' . $attribut['attributName'] . '))
        {
            $this->' . $attribut['attributName'] . ' = ' . $attribut['toUpperName'] . 'DAL::findById($' . $attribut['attributName'] . ');
        }
    }';
                    }
                    else
                    {
                        $maClasse .=
                                '

    /**
     * Setter of ' . $attribut['attributName'] . '.
     *
     * @param ' . $attribut['phpType'] . '|int $' . $attribut['attributName'] . '
     */
    public function set' . $attribut['toUpperName'] . '($' . $attribut['attributName'] . ')
    {
        if (is_a($' . $attribut['attributName'] . ', \'' . $attribut['phpType'] . '\'))
        {
            $this->' . $attribut['attributName'] . ' = $' . $attribut['attributName'] . ';
        }
        else if (is_int($' . $attribut['attributName'] . '))
        {
            $this->' . $attribut['attributName'] . ' = ' . $attribut['toUpperName'] . 'DAL::findById' . $className . '($this->' . $primaryKey['attributName'] . ');
        }
    }';
                    }
                    $maClasse .=
                            '

    /**
     * Getter of ' . $attribut['attributName'] . '.
     *
     * @return ' . $attribut['phpType'] . '
     */
    public function get' . $attribut['toUpperName'] . '()
    {
        return $this->' . $attribut['attributName'] . ';
    }';
                    break;
                case 'var':
                    if ($attribut['isForeignKey'])
                    {
                        $maClasse .=
                                '

    /**
     * Setter of ' . $attribut['attributName'] . '.
     *
     * @param ' . $attribut['phpType'] . ' $' . $attribut['attributName'] . '
     */
    public function set' . $attribut['toUpperName'] . '($' . $attribut['attributName'] . ')
    {
        if (is_' . $attribut['phpType'] . '($' . $attribut['attributName'] . '))
        {
            $this->' . $attribut['attributName'] . ' = $' . $attribut['attributName'] . ';
            $this->' . PanzerStringUtils::convertBddEnCamelCase($attribut['referencedTable']) .
                                ' = ' . PanzerStringUtils::convertToClassName($attribut['referencedTable']) . 'DAL::findById($' . $attribut['attributName'] . ');
        }
    }';
                    }
                    else
                    {
                        $maClasse .= '

    /**
     * Setter of ' . $attribut['attributName'] . '.
     *
     * @param ' . $attribut['phpType'] . ' $' . $attribut['attributName'] . '
     */
    public function set' . $attribut['toUpperName'] . '($' . $attribut['attributName'] . ')
    {
        if (is_' . $attribut['phpType'] . '($' . $attribut['attributName'] . '))
        {
            $this->' . $attribut['attributName'] . ' = $' . $attribut['attributName'] . ';';

                        if ($attribut['isPrimaryKey'])
                        {
                            foreach ($attributes as $toLoad)
                            {
                                if (($toLoad['storage'] === 'object' || $toLoad['storage'] === 'array') && !$toLoad['foreignKeyInClass'])
                                {
                                    $maClasse .= '
            $this->set' . $toLoad['toUpperName'] . '(' . $toLoad['toUpperName'] . 'DAL::findById' . $className . '($'.$attribut['attributName'].'));';
                                }
                                else if ($toLoad['storage'] === 'array')
                                {
                                    $upperClassName = PanzerStringUtils::premiereLettreMaj($toLoad['attributName']);
                                    $maClasse .= '
            $this->set' . $upperClassName . '(' . $toLoad['toUpperName'] . 'DAL::findById' . $className . '($'.$attribut['attributName'].'));';
                                }
                                else if($toLoad['storage'] === 'manyToMany')
                                {
                                    $upperClassName = PanzerStringUtils::premiereLettreMaj($toLoad['attributName']);
                                    $referencedClassName = PanzerStringUtils::convertToClassName($toLoad['referencedTable']);
                                    $maClasse .= '
            $this->set' . $upperClassName . '(' . $referencedClassName . 'DAL::find'.$toLoad['toUpperName'].'ById' . $className . '($'.$attribut['attributName'].'));';
                                }
                            }
                        }
                        $maClasse .= '
        }
    }';
                    }

                    $maClasse .=
                            '

    /**
     * Getter of ' . $attribut['attributName'] . '.
     *
     * @return ' . $attribut['phpType'] . '
     */
    public function get' . $attribut['toUpperName'] . '()
    {
        return $this->' . $attribut['attributName'] . ';
    }';
                    break;
                case 'array':
                    $upperName = PanzerStringUtils::premiereLettreMaj($attribut['attributName']);
                    $maClasse .=
                            '

    /**
     * Setter of ' . $attribut['attributName'] . '.
     *
     * @param array of ' . $attribut['phpType'] . ' $' . $attribut['attributName'] . '
     */
    public function set' . $upperName . '($' . $attribut['attributName'] . ')
    {
        if (is_array($' . $attribut['attributName'] . '))
        {
            $this->' . $attribut['attributName'] . ' = $' . $attribut['attributName'] . ';
        }
    }

    /**
     * Getter of ' . $attribut['attributName'] . '.
     *
     * @return array of ' . $attribut['phpType'] . '
     */
    public function get' . $upperName . '()
    {
        return $this->' . $attribut['attributName'] . ';
    }

    /**
     * Add a ' . $attribut['phpType'] . '.
     *
     * @param ' . $attribut['phpType'] . '
     */
    public function add' . $attribut['toUpperName'] . '($' . PanzerStringUtils::convertBddEnCamelCase($attribut['fieldName']) . ')
    {
        if (is_a($' . PanzerStringUtils::convertBddEnCamelCase($attribut['fieldName']) . ', \'' . $attribut['phpType'] . '\'))
        {
            $this->' . $attribut['attributName'] . '[] = $' . PanzerStringUtils::convertBddEnCamelCase($attribut['fieldName']) . ';
        }
    }';
                    break;

                case 'manyToMany':
                    $maClasse .=
                            '

    /**
     * Setter of ' . $attribut['referencedTable'] . '.
     *
     * @param array of ' . $attribut['phpType'] . ' $' . $attribut['attributName'] . '
     */
    public function set' . $attribut['toUpperName'] . '($' . $attribut['attributName'] . ')
    {
        if (is_array($' . $attribut['attributName'] . '))
        {
            $this->' . $attribut['attributName'] . ' = $' . $attribut['attributName'] . ';
        }
    }

    /**
     * Getter of ' . $attribut['attributName'] . '.
     *
     * @return array of ' . $attribut['phpType'] . '
     */
    public function get' . $attribut['toUpperName'] . '()
    {
        return $this->' . $attribut['attributName'] . ';
    }

    /**
     * Add a ' . $attribut['phpType'] . '.
     *
     * @param ' . $attribut['phpType'] . '
     */
    public function add' . $attribut['toUpperName'] . '($' . $attribut['attributName'] . ')
    {
        if (is_a($' . $attribut['attributName'] . ', \'' . $attribut['phpType'] . '\'))
        {
            $this->' . $attribut['attributName'] . '[] = $' . $attribut['attributName'] . ';
        }
    }';
                    break;
            }
        }

        $maClasse .=
                '

    /////////////
    // METHODS //
    /////////////

    // Write your customs methods here !

}';
        // Writing the file.
        fwrite($handle, $maClasse);
        fclose($handle);
    }
}