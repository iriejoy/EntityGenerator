<?php
namespace EntityGenerator\Helper;

interface ContextDevelopmentInterface
{
    const DOMAIN = "Domain\\Aggregates\\Entity";
    const APPLICATION = "Application\\Repositories";
    const NAMESPACE_LABEL = "namespace ";

    public function entityDesigner():void;
    public function RepositoryDesigner():void;
}
class ContextDevelopment implements ContextDevelopmentInterface
{
    use TraitWriter;
    use InterfaceEntityWriter;
    use RepositoryWriter;

    private $_directoryName;
    private $_enityName;
    private $_coloumnNames;
    private $_database;
    private $_entity;

    //$tableName, $coloumnNames, $database
    public function __construct(
        string $directoryName,
        string $enityName,
        array $coloumnNames,
        string $database
    ) {
        $this->_directoryName = $directoryName;
        $this->_tableName = $enityName;
        $this->_enityName = HelperFunctions::studlyCaps($enityName);
        $this->_coloumnNames = $coloumnNames;
        $this->_database = HelperFunctions::studlyCaps($database);
    }
    public function entityDesigner():void
    {
        $this->writeEntityInterface('Entity');
        $this->writeEntityTrait('Entity');
        $this->writeEntityClass('Entity');
    }

    public function RepositoryDesigner():void
    {
        $this->writeRepositoryInterface('Repository');
        $this->writeRepositoryClass('Repository');
    }

    private function setDomainNamespace():string
    {
        return self::NAMESPACE_LABEL." ".self::DOMAIN."\\{$this->_enityName};\n";
    }

    private function setApplicationNamespace():string
    {
        return self::NAMESPACE_LABEL." ".self::APPLICATION."\\{$this->_enityName};\n";
    }


    private function writeComments():string
    {
        return "/**
* @Entity @Table(name=\"{$this->_enityName}\")
**/\n";
    }

    public function writeEntityClass(string $contextType):void
    {
        $this->writeContent(
            $this->writeOpenFile()
        );

        $this->writeContent(
            $this->setDomainNamespace()
        );

        $this->writeContent(
            $this->writeComments()
        );

        $this->_entity .= "class {$this->_enityName}{$contextType} implements {$this->_enityName}{$contextType}Interface
{\n";
        $this->_entity .= "    use {$this->_enityName}{$contextType}Trait;\n";

        $this->writeContent("}\n");

        $this->createPackage(
            $this->phpFileName($contextType, ''),
            $contextType
        );
        $this->flushContent();
        unset($contextType);
    }

    private function writeOpenFile():string
    {
        return "<?php\n";
    }

    private function writeContent(string $string):void
    {
        $this->_entity .= $string;
    }
    private function flushContent():void
    {
        $this->_entity = '';
    }

    private function phpFileName(string $context, string $type):string
    {
        return $this->_enityName.$context.$type.'.php';
    }

    public function createPackage(string $fileName, string $contextFolder):void
    {

        $filePath = dirname(__FILE__, 3)."/{$this->_directoryName}/{$this->_database}/{$this->_enityName}/{$contextFolder}/";

        //if (!defined('FILE_WRITE_PATH')) {
        //    define('FILE_WRITE_PATH', $filePath);
        //}

        if (!file_exists($filePath.$fileName)) {
            mkdir($filePath, 0777, true);
        }
        if ($fh = fopen($filePath.$fileName, 'w+')) {
            if (is_writable($filePath.$fileName)) {
                fwrite($fh, $this->_entity);
            } else {
                exit('Please provide Read and Write permissions for directory');
            }
        } else {
            exit('Please provide Read and Write permissions for directory');
        }
        unset($fileName, $fh, $contextFolder, $filePath);
    }
}
