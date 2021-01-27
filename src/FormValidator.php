<?php

namespace NeutronStars\TravelAgency;


use voku\helper\AntiXSS;

class FormValidator
{
    const REQUIRE_AREA   = 'Ce champs est requis !';
    const NOT_VALID_AREA = 'Ce champs n\'est pas valide !';

    private array $errors = [];
    private array $values;

    public function __construct(array $inputs, array $validate)
    {
        $this->values = (new AntiXSS())->xss_clean($inputs);
        $this->validate($this->values, $validate);
    }

    private function validate(array $input, array $validate): void
    {
        foreach ($validate as $key => $value)
        {
            switch ($value['type'] ?? 'text')
            {
                case 'text':
                    $this->text($input, $key, $value);
                    break;
                case 'number':
                    $this->number($input, $key, $value);
                    break;
                case 'select':
                    $this->selectValid($input, $key, $value);
                    break;
                case 'datetime':
                    $this->date($input, $key, $value);
                    break;
                case 'image':
                    $this->image($input, $key, $value);
                    break;
                default:
                    $this->errors[$key] = 'Le type de donnée n\'est pas valide !';
                    break;
            }
        }
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function isValid(): bool
    {
        return empty($this->errors);
    }

    /* VALIDATOR TEXT */

    private function text(array $input, $key, array $value): void
    {
        $this->textValid($input, $key, $value);
        if(!empty($value['add'])){
            foreach ($value['add'] as $k) {
                $this->textValid($input, $k, $value);
            }
        }
    }

    private function textValid(array $input, $key, array $value): void
    {
        $this->textLimit(getValueByArray($input, $key), $value['min'] ?? PHP_INT_MIN, $value['max'] ?? PHP_INT_MAX, $key, $value['require'] ?? true);
        if(empty($this->errors[$key]) && isset($value['tronc']) && is_numeric($value['tronc']) && mb_strlen($this->values[$key]) > intval($value['tronc'])){
            $this->values[$key] = mb_strstr($this->values[$key], 0, intval($value['tronc']));
        }
        if(empty($this->errors[$key]) && isset($value['matches']) && !preg_match($value['matches'], $this->values[$key])){
            $this->errors[$key] = 'Cette valeur n\'est pas correct.';
        }
    }

    private function textLimit(?string $text, int $min, int $max, string $key, bool $require = true): void
    {
        if(empty($text)){
            if($require){
                $this->errors[$key] = self::REQUIRE_AREA;
            }
        }elseif(mb_strlen($text) < $min) {
            $this->errors[$key] = 'Il n\'y a pas assez de caractère ! (min: '.$min.')';
        }elseif(mb_strlen($text) > $max) {
            $this->errors[$key] = 'Il y a trop de caractère ! (max: '.$max.')';
        }
    }

    /* VALIDATOR NUMBER */

    private function number(array $input, $key, array $value): void
    {
        $this->numberValid($input, $key, $value);
        if(!empty($value['add'])){
            foreach ($value['add'] as $k) {
                $this->numberValid($input, $k, $value);
            }
        }
    }

    private function numberValid(array $input, $key, array $value): void
    {
        $this->numberLimit(getValueByArray($input, $key), $value['max'] ?? PHP_INT_MIN, $value['max'] ?? PHP_INT_MAX, $key, $value['require'] ?? true);
    }

    private function numberLimit($value, int $min, int $max, $key, bool $require = true): void
    {
        if(empty($value) && !is_int($value))
        {
            if($require){
                $this->errors[$key] = self::REQUIRE_AREA;
            }
            return;
        }
        $value = intval($value);
        if($value < $min) {
            $this->errors[$key] = 'Le nombre est trop petit ! (min: '.$min.')';
        }elseif ($value > $max) {
            $this->errors[$key] = 'Le nombre est trop grand ! (max: '.$max.')';
        }
    }

    /* VALIDATOR SELECT */
    private function select(array $input, $key, array $value): void
    {
        $this->selectValid($input, $key, $value);
        if(!empty($value['add'])){
            foreach ($value['add'] as $k) {
                $this->selectValid($input, $k, $value);
            }
        }
    }

    private function selectValid(array $input, $key, array $value): void
    {
        $this->selectLimit($value['value'] ?? [], getValueByArray($input, $key), $key, $value['require'] ?? true);
    }

    private function selectLimit(array $values, $value, $key, bool $require = true): void
    {
        if(empty($value)){
            if($require){
                $this->errors[$key] = self::REQUIRE_AREA;
            }
        }elseif (empty($values[$value])){
            $this->errors[$key] = NOT_VALID_AREA;
        }
    }

    /* VALIDATOR DATE */
    private function date(array $input, $key, array $value): void
    {
        $this->dateValid($input, $key, $value);
        if(!empty($value['add'])){
            foreach ($value['add'] as $k) {
                $this->dateValid($input, $k, $value);
            }
        }
    }

    private function dateValid(array $input, $key, array $value): void
    {
        $this->dateLimit(getValueByArray($input, $key), $key, $value['require'] ?? true);
    }

    private function dateLimit(?string $value, $key, bool $require = true): void
    {
        if(empty($value)){
            if($require){
                $this->errors[$key] = self::REQUIRE_AREA;
            }
            return;
        }
        $time = strtotime($value);
        if(!$time) {
            $this->errors[$key] = self::NOT_VALID_AREA;
        }elseif($time < time()) {
            $this->errors[$key] = 'La date ne doit pas être inférieur à la date du jour !';
        }
    }

    /* VALIDATOR IMAGE */
    private function image(array $input, $key, array $value): void
    {
        $this->imageValid($input, $key, $value);
        if(!empty($value['add'])){
            foreach ($value['add'] as $k) {
                $this->imageValid($input, $k, $value);
            }
        }
    }

    private function imageValid(array $input, $key, array $value): void
    {
        $this->imageArrayLimit(getValueByArray($input, $key), $value['maxSize'] ?? 2000000, $value['extensions'] ?? [], $key, $value['require'] ?? true);
    }

    private function imageArrayLimit(?array $fileArray, int $maxSize, array $extensions, $key, bool $require = true): void
    {
        if(empty($fileArray)){
            if($require){
                $this->errors[$key] = self::REQUIRE_AREA;
            }
            return;
        }
        if(!isset($fileArray['error']) || !is_numeric($fileArray['error'])
            || ($fileArray['error'] > 0 && $fileArray['error'] != 4)
            || (empty($fileArray['tmp_name']) && $fileArray['error'] != 4)
        )
        {
            $this->errors[$key] = 'Une erreur est survenue le type d\'image ne doit pas être valide ! ('.implode(', ', $extensions).')';
        }
        $this->imageLimit($fileArray['tmp_name'], $maxSize, $extensions, $key, $require);
    }

    private function imageLimit(?string $file, int $maxSize, array $extensions, $key, bool $require = true): void
    {
        if(empty($file) || !file_exists($file)){
            if($require){
                $this->errors[$key] = self::REQUIRE_AREA;
            }
            return;
        }
        if (filesize($file) > $maxSize){
            $this->errors[$key] = 'L\'image ne doit pas excéder '.($maxSize/1000000).' Mo !';
        }else{
            $mime = getMIMEFile($file);
            $find = false;
            foreach ($extensions as $extension){
                if($mime == 'image/'.$extension){
                    $find = true;
                    break;
                }
            }

            if(!$find){
                $this->errors[$key] = 'L\'extension du fichier n\'est pas correct ! ('.implode(', ', $extensions).')';
            }
        }
    }
}
