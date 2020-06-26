<?php
namespace App\Http\Validation;

use Illuminate\Contracts\Validation\Rule;

class ValidateEstRpc implements Rule {

    protected $message = 'The max bid calculation is ambiguous. There are values for both max bid and max bid multiplier attributes.';

    public function passes($attribute, $value) {

    }

    public function message() {
        return $this->message;
    }

    protected function validateAmbiguousRevCalculations($attribute, $value, $parameters) {
        if ( !$this->validateRequired($attribute, $value) ) {
            if ( array_get($this->data, $parameters[0]) != 0
                && array_get($this->data, $parameters[1]) != 0 ) {
                return false;
            }
        }
        return true;
    }

}