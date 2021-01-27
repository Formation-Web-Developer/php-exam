<?php
/*
 * Ce fichier me permet de traiter tous ce qui est attrait à la table logement de ma base de donnée.
 *
 * J'importe la class Database.
 */
use NeutronStars\Database\Database;

/*
 * Permet de récupérer tous les logements stockés dans la base de données
 * avec une limite déterminé par la variable $items ainsi que triés du plus récent au plus ancien.
 */
function getLogements(Database $database, int $offset, int $items): array
{
    return $database->query('logement l')
        ->select('l.*, t.name AS logement_type')
        ->leftJoin('logement_type t', 't.id=l.type')
        ->limit($items, $offset)
        ->orderBy('l.created_at', \NeutronStars\Database\Query::ORDER_BY_DESC)
        ->getResults();
}

/*
 * Permet de récupérer le nombre de logement stockés dans la base de donnée.
 */
function getCount(Database $database): int
{
    return intval($database->query('logement')
        ->select('COUNT(*) AS count')
        ->getResult()['count']);
}

/*
 * Permet de créer un nouveau logement dans la base de donnée.
 */
function createLogement(Database $database, array $array): void
{
    $database->query('logement')
        ->insertInto(
            'title,address,city,postal_code,surface,price,type,description,created_at',
            ':title,:address,:city,:postal_code,:surface,:price,:type,:description,NOW()'
        )
        ->setParameters([
            ':title'            => $array['title'],
            ':address'          => $array['address'],
            ':city'             => $array['city'],
            ':postal_code'      => $array['postal_code'],
            ':surface'          => $array['surface'],
            ':price'            => $array['price'],
            ':type'             => $array['type'],
            ':description'      => $array['description'] ?: null
        ])->execute();
}

/*
 * Permet de mettre à joueur l'image d'un logement dans la base de donnée.
 */
function setImageLogement(Database $database, int $id, ?string $path): void
{
    $database->query('logement')
        ->update('picture=:picture')
        ->where('id=:id')
        ->setParameters([':picture' => $path, ':id' => $id])
        ->execute();
}
