<?php
require_once 'init-production.php';

use EntityGenerator\Helper\ContextDevelopment;

$directoryName = "Context";

// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
ini_set('max_execution_time', 0);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $databaseRepository = new EntityGenerator\Database\DatabaseRepository($connection);
    $database   = filter_input(INPUT_POST, 'database');
    $tables     = explode(',', filter_input(INPUT_POST, 'tables'));
    if (empty($databases) == true && isset($tables[0]) && $tables[0] == '') {
        header("Location:index.php?error=Select database and tables");
        return false;
    }
    $connection->exec("USE $database");

    foreach ($tables as $table) {
        $coloumnNames = $databaseRepository->getColoumnNamesOfTable($table);
        $obj = new ContextDevelopment(
            $directoryName,
            $table,
            $coloumnNames,
            $database
        );
        $obj->EntityDesigner();
        $obj->RepositoryDesigner();
    }
    header("Location:index.php?success=Successfully Generated Entities");
} else {
    header("Location:index.php?error=Oops! Error in generating entities.");
}
