<?php
namespace EntityGenerator\Helper;

trait RepositoryWriter
{
    public function writeRepositoryInterface(string $contextType)
    {
        $this->writeContent(
            $this->writeOpenFile()
        );

        $this->writeContent(
            $this->setApplicationNamespace()
        );

        $this->writeContent(
            $this->writeComments()
        );

        //$this->_entity .= $this->generateInterface($coloumnNames, $studlyTableName);
        $this->_entity .= "interface {$this->_enityName}{$contextType}Interface
{\n";
        $this->_entity .= "    /**\n";
        $this->_entity .= "    *\n";
        $this->_entity .= "    * Get City Metadata by quering (Ex. san-diego-ca)\n";
        $this->_entity .= "    * @param \$params array 'slug'\n";
        $this->_entity .= "    * @return object\n";
        $this->_entity .= "    */\n";
        $this->_entity .= "    //public function FindSlug(array \$params): {$this->_enityName}EntityInterface;\n";

        $this->writeContent("}\n");

        $this->createPackage(
            $this->phpFileName($contextType, 'Interface'),
            $contextType
        );
        $this->flushContent();
        unset($contextType);
    }

    public function writeRepositoryClass(string $contextType)
    {
        $this->writeContent(
            $this->writeOpenFile()
        );

        $this->writeContent(
            $this->setApplicationNamespace()
        );

        $this->_entity .= "\n";
        $this->_entity .= "use Coqui\BasicDAO\BasicDAO;\n";
        $this->_entity .= "use Coqui\BasicDAO\BasicDAORepositoriesHandlerMethodsInterface;\n";
        $this->_entity .= "use Coqui\BasicDAO\BasicDAORepositoriesCustomQueryHandlerInterface;\n";
        $this->_entity .= "use ".self::DOMAIN.'\\'.$this->_enityName.'\\'.$this->_enityName."Entity;\n";
        $this->_entity .= "use ".self::DOMAIN.'\\'.$this->_enityName.'\\'.$this->_enityName."EntityInterface;\n";

        $this->writeContent(
            $this->writeComments()
        );

        $this->_entity .= "abstract class Abstract{$this->_enityName}{$contextType}";
        $this->_entity .= " extends BasicDAO";
        $this->_entity .= " implements BasicDAORepositoriesHandlerMethodsInterface,";
        $this->_entity .= "BasicDAORepositoriesCustomQueryHandlerInterface,";
        $this->_entity .= "{$this->_enityName}EntityInterface\n";

        $this->_entity .= "{\n";

        $this->_entity .= "    public function __construct({$this->_enityName}Entity \$entity)\n";
        $this->_entity .= "    {\n";
        $this->_entity .= "        parent::__construct();\n";
        $this->_entity .= "        \$this->setEntity(get_class(\$entity));\n";

        $this->_entity .= "        // DB Table Properties\n";
        $this->_entity .= "        \$this->setTableName('{$this->_tableName}');\n";

        $this->_entity .= "        \$this->setColumnsList([";
        foreach ($this->_coloumnNames as $All_coloumnName) {
            $entityClassCols[] = "'".$All_coloumnName['Field']."'";
        }
        $this->_entity .= implode(",", $entityClassCols)."]);\n";

        $this->_entity .= "        //abstract public function FindSlug(array \$params): {$this->_enityName}EntityInterface;\n";
        $this->_entity .= "    }\n";
        $this->_entity .= "}\n";


        $this->_entity .= "class {$this->_enityName}{$contextType}";
        $this->_entity .= " extends Abstract{$this->_enityName}{$contextType}";
        $this->_entity .= " implements {$this->_enityName}{$contextType}Interface\n";
        $this->_entity .= "{\n";
        $this->_entity .= "    /**\n";
        $this->_entity .= "    *\n";
        $this->_entity .= "    * Get City Metadata by quering (Ex. san-diego-ca)\n";
        $this->_entity .= "    * @param \$params array 'slug'\n";
        $this->_entity .= "    * @return object\n";
        $this->_entity .= "    */\n";
        $this->_entity .= "    //public function FindSlug(array \$params): {$this->_enityName}EntityInterface{}\n";
        $this->_entity .= "}\n";

        $this->createPackage(
            $this->phpFileName($contextType, ''),
            $contextType
        );
        $this->flushContent();
        unset($contextType);
    }
}
