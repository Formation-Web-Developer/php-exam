<?php
/*
 * Ce fichier me permet de traiter tous ce qui est attrait à la table logement_type de ma base de donnée.
 *
 * J'importe la class Database.
 */
use NeutronStars\Database\Database;

/*
 * Permet de récupérer tous les types de logements stockés dans la base de donnée.
 */
function getLogementToArray(Database $database): array
{
    $pensions = [];
    foreach ($database->query('logement_type')->select('*')->getResults() as $result){
        $pensions[intval($result['id'])] = $result['name'];
    }
    return $pensions;
}
