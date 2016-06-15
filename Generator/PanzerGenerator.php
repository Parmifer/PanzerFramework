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

require_once('PanzerClassGenerator.php');
require_once('PanzerDALGenerator.php');
require_once('PanzerControllerGenerator.php');

/**
 * Description of PanzerGenerator
 *
 * @author Parmifer
 */
class PanzerGenerator
{
    private $options;
    
    private $tables;
    
    private $panzerClassGenerator;
    private $panzerDALGenerator;
    private $panzerControllerGenerator;

    /**
     * Constructor
     */
    function __construct($options)
    {
        $this->options = json_decode($options, true);
        $this->processAllTables();

        echo('<pre>');
        var_dump($this->tables);
        echo('</pre>');
        
        die();
    }

    /**
     * Génère toutes les classes à partir des options choisies par l'utilisateur.
     */
    public function generateAllClasses()
    {
        $this->panzerClassGenerator = new PanzerClassGenerator();

        foreach ($this->tables as $table => $infos)
        {
            if($infos['onlyDAL'] != 'true')
            {
                $this->panzerClassGenerator->generateOneClass($table, $infos['attributes'], PanzerConfiguration::getProjectRoot().'model/class/');

                echo 'Classe : ' . $table . ' ok ! <br />';
            }
        }
    }

    /**
     * Génère toutes les DAL à partir des options choisies par l'utilisateur.
     */
    public function generateAllDAL()
    {
        $this->panzerDALGenerator = new PanzerDALGenerator();

        foreach ($this->tables as $table => $infos)
        {
            if($infos['onlyDAL'] != 'true')
            {
                $this->panzerDALGenerator->generateOneDAL($table, $infos['attributes'], PanzerConfiguration::getProjectRoot().'model/class/');

                echo 'DAL : ' . $table . ' ok ! <br />';
            }
        }
    }

    public function generateAllControllers()
    {
        $this->panzerControllerGenerator = new panzerControllerGenerator();
        
        foreach($this->options['arborescence']['pages'] as $pageName => $pageInfos)
        {
            $this->panzerControllerGenerator->generateOneController($pageName, $pageInfos, PanzerConfiguration::getProjectRoot().'controller/page/');
        }
    }
    
    private function processAllTables()
    {
        $this->tables = array();
        
        foreach ($this->options['tables_a_transformer'] as $uneTable => $infosTable)
        {
            $this->tables[$uneTable]['onlyDAL'] = $infosTable['onlyDAL'];
            $this->tables[$uneTable]['attributes'] = $this->processTableData($uneTable, $infosTable);
        }
    }
    
    private function processTableData($table, $infosTable)
    {
        $attributes = array();     
        
        $databaseFields = BaseSingleton::select('describe ' . $table);
        
        foreach ($databaseFields as $field)
        {
            $fieldName = $field['Field'];
            $attributName = PanzerStringUtils::convertBddEnCamelCase($fieldName);
            $toUpperName = PanzerStringUtils::premiereLettreMaj($attributName);
            $isEnum = PanzerSQLUtils::isEnum($field['Type']);
            $enumList = ($isEnum ? PanzerSQLUtils::getEnumList($field['Type']) : null );
            $isPrimaryKey = $field['Key'] === 'PRI';
            $isForeignKey = PanzerSQLUtils::isAForeignKey($table, $fieldName);
            
            $referencedTable = null;
            $referencedColumn = null;
            $relationType = null;
            
            if($isForeignKey)
            {
                $mappingInfo = PanzerSQLUtils::getFieldMappingInfo($table, $fieldName);
                $relationType = $mappingInfo['relationType'];
                $referencedTable = $mappingInfo['referencedTable'];
                $referencedColumn = $mappingInfo['referencedColumn'];
            }                
            
            $attribut = array(
                'tableName' => $table,
                'fieldName' => $fieldName,
                'attributName' => $attributName,
                'toUpperName' => $toUpperName,
                'storage' => 'var',
                'sqlType' => $field['Type'],
                'phpType' => PanzerSQLUtils::getPhpType($field['Type']),
                'paramsMapping' => PanzerSQLUtils::getParamsMapping($field['Type']),
                'isEnum' => $isEnum, 
                'enumList' => $enumList,
                'isPrimaryKey' => $isPrimaryKey,
                'isForeignKey' => $isForeignKey,
                'referencedTable' => $referencedTable,
                'referencedColumn' => $referencedColumn,
                'isRequestable' => true,
                'isSavable' => true,
                'relationType' => $relationType,
                'foreignKeyInClass' => null,
                'relatedForeignKey' => null,
            ); 
            
            $attributes[] = $attribut;
        }
        
        foreach($infosTable['relations'] as $relation)
        {
            $fieldName = $relation['table'];
            $attributName = PanzerStringUtils::convertBddEnCamelCase($fieldName);
            $toUpperName = PanzerStringUtils::premiereLettreMaj($attributName);
            $storage = $relation['storage'];
            $relatedForeignKey = null;
            
            switch ($storage)
            {
                case 'object':
                    $mappingInfo = PanzerSQLUtils::getTableMappingInfo($table, $relation['table']);
                    $foreignKeyInClass = $mappingInfo['foreignKeyInClass'];
                    $relatedForeignKey = $mappingInfo['relatedForeignKey'];
                    break;
                case 'array':
                case 'manyToMany':
                    $foreignKeyInClass = false;
                    break;
            }
            
            
            $attribut = array(
                'tableName' => $table,
                'fieldName' => $fieldName,
                'attributName' => $attributName,
                'toUpperName' => $toUpperName,
                'storage' => $storage,
                'sqlType' => null,
                'phpType' => $toUpperName,
                'paramsMapping' => null,
                'isEnum' => false,
                'enumList' => null,
                'isPrimaryKey' => false,
                'isForeignKey' => false,
                'referencedTable' => null,
                'referencedColumn' => null,
                'isRequestable' => false,
                'isSavable' => false,
                'relationType' => null,
                'foreignKeyInClass' => $foreignKeyInClass,
                'relatedForeignKey' => $relatedForeignKey,
            );
            
            $attributes[] = $attribut;
        }
        
        return  $attributes;
    }
}