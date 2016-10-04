<?php
/**
 * Created by PhpStorm.
 * User: leonnleite
 * Date: 04/10/16
 * Time: 08:26
 */
require_once __DIR__ . '/vendor/autoload.php';
$config = new \Doctrine\DBAL\Configuration();
/**
 * conectando, inicialmente sem db.
 * Caso não tenha, ele cria
 */
$params = json_decode(file_get_contents(__DIR__ . '/app/config/db.json'), true);
$databaseName = $params['dbname'];
unset($params['dbname']); //removendo momentaneamente
$conn = \Doctrine\DBAL\DriverManager::getConnection($params, $config);
$databases = $conn->getSchemaManager()->listDatabases();


/**
 * criando db, caso não exista
 */
if (!in_array($databaseName, $databases)) {
    $conn->getSchemaManager()->createDatabase($databaseName);
}
$params['dbname'] = $databaseName;
$conn = \Doctrine\DBAL\DriverManager::getConnection($params, $config);


/**
 * criando as tabelas, caso necessário
 */
$tables = $conn->getSchemaManager()->listTables();
if (count($tables) == 0) {
    $table = new \Doctrine\DBAL\Schema\Table('aluno');

    $table->addColumn('id', 'integer')->setAutoincrement(true);
    $table->addColumn('nome', 'string');
    $table->addColumn('sexo', 'string', ['length' => 1]);
    $table->addIndex(['id']);
    $conn->getSchemaManager()->createTable($table);
}