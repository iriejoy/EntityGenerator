<?php
namespace EntityGenerator\Helper;

trait TraitWriter
{
    public function writeEntityTrait(string $contextType)
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
        $this->_entity .= "trait {$this->_enityName}{$contextType}Trait
{\n";
        foreach ($this->_coloumnNames as $coloumnName) :
            $this->_entity .= "    /*\n";
            $this->_entity .= "     *\n";
            $this->_entity .= "     * @var ".$coloumnName['Field']." Type: ".$coloumnName['Type']."\n";
            $this->_entity .= "     *\n";
            $this->_entity .= "     */\n";
            $this->_entity .= "    private \$".$coloumnName['Field'].";\n";
            $this->writeContent(
                $this->writeSettersTrait(
                    $coloumnName['Field'],
                    $coloumnName['Type']
                )
            );
            $this->writeContent(
                $this->writeGettersTrait(
                    $coloumnName['Field'],
                    $coloumnName['Type']
                )
            );
        endforeach;

        $this->writeContent("}\n");

        $this->createPackage(
            $this->phpFileName($contextType, 'Trait'),
            $contextType
        );
        $this->flushContent();
        unset($contextType);
    }

    private function writeGettersTrait($coloumnName, string $type = '')
    {
        $studlyColoumnName = HelperFunctions::studlyCaps($coloumnName);
        $typeConvertion = HelperFunctions::typeAlias($type);

        $str = "    public function get{$studlyColoumnName}(): {$typeConvertion};\n";
        $str .= "    {\n";
        $str .= "        return \$this->{$coloumnName};\n";
        $str .= "    }\n";
        return $str;
    }
    private function writeSettersTrait($coloumnName, string $type = '')
    {
        $studlyColoumnName = HelperFunctions::studlyCaps($coloumnName);
        $camelColoumnName = HelperFunctions::camelCase($coloumnName);
        $typeConvertion = HelperFunctions::typeAlias($type);

        $str = "    public function set{$studlyColoumnName}( {$typeConvertion} \${$camelColoumnName}):void\n";
        $str .= "    {\n";
        $str .= "        \$this->{$coloumnName} = \${$camelColoumnName};\n";
        $str .= "        unset(\${$camelColoumnName});\n";
        $str .= "    }\n";
        return $str;
    }
}
