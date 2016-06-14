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

    public function generateOneClass($table, $relations, $folder)
    {
        // Usefull vars
        $className = PanzerStringUtils::convertTableEnNomClasse($table);
        $this->attributes = BaseSingleton::select('describe ' . $table);
        $this->includes = array();

        if (!empty($relations))
        {
            foreach ($relations as $uneRelation)
            {
                $this->attributes[] = array(
                    'Field' => $uneRelation['table'],
                    'Type' => PanzerStringUtils::convertTableEnNomClasse($uneRelation['table']),
                    'storage' => $uneRelation['storage'],
                    'Key' => (isset($uneRelation['clef_externe']) ? $uneRelation['clef_externe'] : null),
                    'DAL' => (isset($uneRelation['DAL']) ? $uneRelation['DAL'] : null)
                );

                switch ($uneRelation['storage'])
                {
                    case 'object':
                    case 'array':
                        $fileName = PanzerStringUtils::convertTableEnNomClasse($uneRelation['table']) . 'DAL.php';
                        $this->includes[] = 'PanzerConfiguration::getProjectRoot().\'model/DAL/' . $fileName;
                        break;
                    case 'manyToMany':
                        $fileName = PanzerStringUtils::convertTableEnNomClasse($uneRelation['DAL']) . 'DAL.php';
                        $this->includes[] = 'PanzerConfiguration::getProjectRoot().\'model/DAL/' . $fileName;
                        break;
                }
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

        foreach($this->includes as $fileToInclude)
        {
            $maClasse .= '
require_once('.$fileToInclude.'\');';
        }

        $maClasse .= '

class ' . $className .
'
{
    ////////////////
    // ATTRIBUTES //
    ////////////////';

        foreach ($this->attributes as $attribut)
        {
            if ($attribut['Key'] != 'MUL')
            {
                $attributCamelCase = PanzerStringUtils::convertBddEnCamelCase($attribut['Field']);
                $type = PanzerSQLUtils::getPhpType($attribut['Type']);

                $maClasse .=
                        '

    /**
     *
     * @var ' . $type . '
     */
    private $' . $attributCamelCase . ';';
            }
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
        foreach ($this->attributes as $attribut)
        {
            if ($attribut['Key'] != 'MUL')
            {
                $attributCamelCase = PanzerStringUtils::convertBddEnCamelCase($attribut['Field']);
                $type = PanzerSQLUtils::getPhpType($attribut['Type']);

                $maClasse .= '
        $this->' . $attributCamelCase . ' = ' . (($type === 'int' || $type === 'float') ? '0' : 'null') . ';';
            }
        }

        $maClasse .=
                '
    }

    ///////////////////////
    // GETTERS & SETTERS //
    ///////////////////////';

        foreach ($this->attributes as $attribut)
        {
            if ($attribut['Key'] != 'MUL')
            {
                $attributCamelCase = PanzerStringUtils::convertBddEnCamelCase($attribut['Field']);
                $attributMaj = PanzerStringUtils::premiereLettreMaj($attributCamelCase);
                $type = PanzerSQLUtils::getPhpType($attribut['Type']);

                if (isset($attribut['storage']))
                {
                    $maClasse .=
                            '

    /**
     * Setter of ' . $attributCamelCase . '.
     *
     * @param ' . $type . '|int $' . $attributCamelCase . '
     */
    public function set' . $attributMaj . '($' . $attributCamelCase . ')
    {
        if (is_a($' . $attributCamelCase . ', \'' . $type . '\'))
        {
            $this->' . $attributCamelCase . ' = $' . $attributCamelCase . ';
        }
        else if (is_int($' . $attributCamelCase . '))
        {
            $this->' . $attributCamelCase . ' = ' . $attributMaj . 'DAL::findById($' . $attributCamelCase . ');
        }
    }';
                }
                else
                {
                    $maClasse .=
                            '

    /**
     * Setter of ' . $attributCamelCase . '.
     *
     * @param ' . $type . ' $' . $attributCamelCase . '
     */
    public function set' . $attributMaj . '($' . $attributCamelCase . ')
    {
        if (is_' . $type . '($' . $attributCamelCase . '))
        {
            $this->' . $attributCamelCase . ' = $' . $attributCamelCase . ';
        }
    }';
                }

                $maClasse .=
                        '

    /**
     * Getter of ' . $attributCamelCase . '.
     *
     * @return ' . $type . '
     */
    public function get' . $attributMaj . '()
    {
        return $this->' . $attributCamelCase . ';
    }';
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