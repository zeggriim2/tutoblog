<?php

namespace App\Validators;

use App\Validator;

abstract class AbstractValidator {

    protected $data;

    /*** @var Validator */
    protected $validator;

    /**
     * PostValidator constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
        $v = new Validator($data);
        $this->validator = $v;
    }

    public function validate(): bool
    {
        return $this->validator->validate();
    }

    public function errors(): array
    {
        return $this->validator->errors();
    }


}