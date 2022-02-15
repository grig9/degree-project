<?php

use App\QueryBuilder;
use App\DataBase\Connection;

$config = include __DIR__ . "/config.php"; 

return new QueryBuilder(Connection::make($config['database']));