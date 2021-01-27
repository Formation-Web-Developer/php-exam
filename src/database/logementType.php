<?php
use NeutronStars\Database\Database;

function getLogementToArray(Database $database): array
{
    $pensions = [];
    foreach ($database->query('logement_type')->select('*')->getResults() as $result){
        $pensions[intval($result['id'])] = $result['name'];
    }
    return $pensions;
}
