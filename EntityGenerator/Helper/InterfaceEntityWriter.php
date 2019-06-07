<?php
namespace EntityGenerator\Helper;

trait InterfaceEntityWriter
{

    public function writeEntityInterface(string $contextType):void
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

        //$this->_entity .= $this->generateInterface($coloumnNames, $studlyTableName);
        $this->_entity .= "interface {$this->_enityName}{$contextType}Interface
{\n";
        foreach ($this->_coloumnNames as $coloumnName) :
            //if($coloumnName['Key'] == 'PRI' || $coloumnName['Key'] == 'PRIMARY'){
            //  $output .= igetGetter($coloumnName['Field']);
            //  continue;
            //}else{
            //$this->_entity .= igetSetter($coloumnName['Field'], $coloumnName['Type']);
            //}

            $this->writeContent(
                $this->writeSettersInterface(
                    $coloumnName['Field'],
                    $coloumnName['Type']
                )
            );

            $this->writeContent(
                $this->writeGettersInterface(
                    $coloumnName['Field'],
                    $coloumnName['Type']
                )
            );
        endforeach;

        $this->writeContent("}\n");

        $this->createPackage(
            $this->phpFileName($contextType, 'Interface'),
            $contextType
        );
        $this->flushContent();
        unset($contextType);
    }

    private function writeGettersInterface($coloumnName, string $type = ''):string
    {
        $studlyColoumnName = HelperFunctions::studlyCaps($coloumnName);
        $typeConvertion = HelperFunctions::typeAlias($type);

        return "    public function get{$studlyColoumnName}(): {$typeConvertion};\n";
    }

    private function writeSettersInterface($coloumnName, string $type = ''):string
    {
        $studlyColoumnName = HelperFunctions::studlyCaps($coloumnName);
        $camelColoumnName = HelperFunctions::camelCase($coloumnName);
        $typeConvertion = HelperFunctions::typeAlias($type);

        return "    public function set{$studlyColoumnName}( {$typeConvertion} \${$camelColoumnName} ):void;\n";
    }
}
