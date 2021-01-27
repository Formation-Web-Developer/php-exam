<?php

/*
 * J'importe la class Database (Que j'ai conçu en amont)
 */
use NeutronStars\Database\Database;

/*
 * Je déclare un nouvelle instance de la class Database avec les valeurs requis.
 */
$database = new Database('immobilier', [
    'port'      => 3307,
    'user'      => 'root',
    'password'  => ''
]);
