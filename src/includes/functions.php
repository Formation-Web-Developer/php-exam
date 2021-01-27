<?php
/*
 * J'importe la class contre les failles XSS (Récupéré sur Packagist)
 */
use voku\helper\AntiXSS;
/*
 * Je déclare un nouvelle instance de la class AntiXSS.
 */
$antiXSS = new AntiXSS();

/*
 * Permet de sécuriser mes données des failles XSS.
 */
function antiXSS(AntiXSS $antiXSS, $var)
{
    return $antiXSS->xss_clean($var);
}

/*
 * Permet de récupérer une valeur d'un tableau avec une valeur par défaut si la clé n'existe pas.
 */
function getValueByArray(array $array, $key, $def = null)
{
    return !empty($array[$key]) ? $array[$key] : $def;
}

/*
 * Permet de récupéré le MIME d'un fichier/image.
 */
function getMIMEFile(string $file): string
{
    $fileInfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
    $mime = finfo_file($fileInfo, $file);
    finfo_close($fileInfo);
    return $mime;
}

/*
 * Permet de récupérer l'url d'une miniature en fonction de l'url de l'image originale.
 */
function getMiniature(string $file): string
{
    $extension = '.'.pathinfo($file,PATHINFO_EXTENSION);
    return str_replace($extension, '', $file).'_300x300'.$extension;
}

/*
 * Permet de formater un nombre entier séparer par des espace pour bien différencier les groupes de millier.
 */
function intFormat(string $number): string
{
    return number_format($number, 0, ',', ' ');
}
