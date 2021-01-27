<?php
use NeutronStars\Database\Database;

function getLogements(Database $database, int $offset, int $items): array
{
    return $database->query('logement l')
        ->select('l.*, t.name AS logement_type')
        ->leftJoin('logement_type t', 't.id=l.type')
        ->limit($items, $offset)
        ->getResults();
}

function getCount(Database $database): int
{
    return intval($database->query('logement')
        ->select('COUNT(*) AS count')
        ->getResult()['count']);
}

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


function setImageLogement(Database $database, int $id, ?string $path): void
{
    $database->query('logement')
        ->update('picture=:picture')
        ->where('id=:id')
        ->setParameters([':picture' => $path, ':id' => $id])
        ->execute();
}
