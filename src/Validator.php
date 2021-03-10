<?php

namespace App;

use Valitron\Validator as ValitronValidator;

class Validator extends ValitronValidator{

    /**
     * @param  string $field
     * @param  string $message
     * @param  array  $params
     * @return array
     */
    protected function checkAndSetLabel($field, $message, $params)
    {
        $message = str_replace('{field}', '', $message);
        return $message;
    }
}