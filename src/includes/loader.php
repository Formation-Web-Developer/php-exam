<?php
/*
 * Dans ce fichier je charge toutes mes dépendances et ressources que j'ai besoin par défaut.
 */

require_once __DIR__ . '/../../vendor/autoload.php';

/* FUNCTIONS */
require_once __DIR__ . '/functions.php';

/* DATABASE */
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../database/logement.php';
require_once __DIR__ . '/../database/logementType.php';
