<?php

namespace App\Http\Validation;

use Illuminate\Contracts\Validation\Rule;

class ValidateNotInIf implements Rule {

    protected $message = 'The max bid calculation is ambiguous. There are values for both max bid and max bid multiplier attributes.';

    public function passes($attribute, $value) {
        if ($value) {
            $this->replaceNotInIf();
        }
    }

    public function message() {
        return $this->message;
    }


    protected function replaceNotInIf($message, $attribute, $rule, $parameters) {
        $if_value = array_pop($parameters);
        $if_attribute = array_pop($parameters);

        $if_attribute = $this->getAttribute($if_attribute);

        return str_replace(array(':other', ':value'), array($if_attribute, $if_value), $message);
    }

}