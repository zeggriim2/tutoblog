<?php

namespace App\Validators;

use App\Table\PostTable;

class PostValidator extends AbstractValidator {

    /**
     * PostValidator constructor.
     * @param $data
     * @param PostTable $table
     */
    public function __construct($data,PostTable $table, ?int $postId = null)
    {
        parent::__construct($data);

        $this->validator->rule('required', ['name', 'slug']);
        $this->validator->rule('lengthBetween', ['name', 'slug'], 3, 200);
        $this->validator->rule('slug', 'slug');
        $this->validator->rule(function ($field, $value) use ($table, $postId){
            return !$table->exists($field,$value, $postId);
        }, 'slug')->message('Ce slug est déjà utilisé.');
    }
}