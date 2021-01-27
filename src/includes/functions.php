<?php

use voku\helper\AntiXSS;
$antiXSS = new AntiXSS();

function antiXSS(AntiXSS $antiXSS, $var)
{
    return $antiXSS->xss_clean($var);
}

function getValueByArray(array $array, $key, $def = null)
{
    return !empty($array[$key]) ? $array[$key] : $def;
}


function startsWith( $haystack, $needle ): bool
{
    $length = strlen( $needle );
    return substr( $haystack, 0, $length ) === $needle;
}

function endsWith( string $haystack, string $needle ): bool
{
    $length = strlen( $needle );
    if( !$length ) {
        return true;
    }
    return substr( $haystack, -$length ) === $needle;
}

function endsWithByArray(string $haystack, string ...$needle): bool
{
    foreach ($needle as $value){
        if(endsWith($haystack, $value)){
            return true;
        }
    }
    return false;
}

function getMIMEFile(string $file): string
{
    $fileInfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
    $mime = finfo_file($fileInfo, $file);
    finfo_close($fileInfo);
    return $mime;
}

function getMiniature(string $file): string
{
    $extension = '.'.pathinfo($file,PATHINFO_EXTENSION);
    return str_replace($extension, '', $file).'_300x300'.$extension;
}
