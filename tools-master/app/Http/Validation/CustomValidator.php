<?php
namespace App\Http\Validation;

use Illuminate\Validation\Validator;

class CustomValidator extends Validator {

    public function __construct(TranslatorInterface $translator, $data, $rules, $messages = []) {
        parent::__construct($translator, $data, $rules, $messages);
        $this->implicitRules[] = "AmbiguousRevCalculations";
    }

    protected function validateCampaignCountry($attribute, $value, $parameters) {
        return in_array($value, ['US', 'ALL', 'INTL']) || in_array($value, array_keys(Country::all()));
    }

    protected function validateNotInIf($attribute, $value, $parameters) {
        $if_value = array_pop($parameters);
        $if_attribute = array_pop($parameters);

        if ($if_value == array_get($this->data, $if_attribute)) {
            return $this->validateNotIn($attribute, $value, $parameters);
        }

        return true;
    }

    protected function replaceNotInIf($message, $attribute, $rule, $parameters) {
        $if_value = array_pop($parameters);
        $if_attribute = array_pop($parameters);

        $if_attribute = $this->getAttribute($if_attribute);

        return str_replace(array(':other', ':value'), [$if_attribute, $if_value], $message);
    }


    protected function validateGreaterThan($attribute, $value, $parameters) {
        $this->requireParameterCount(1, $parameters, 'greater_than');

        return $value > $parameters[0];
    }

    protected function replaceGreaterThan($message, $attribute, $rule, $parameters) {
        return str_replace(':value', $parameters[0], $message);
    }

    protected function validateGreaterThanIf($attribute, $value, $parameters) {
        $this->requireParameterCount(3, $parameters, 'greater_than');

        return $this->data[$parameters[1]] !== $parameters[2] || $value > $parameters[0];
    }

    protected function replaceGreaterThanIf($message, $attribute, $rule, $parameters) {
        $message = str_replace(':value', $parameters[0], $message);
        $message = str_replace(':other_name', $parameters[1], $message);
        $message = str_replace(':other_value', $parameters[2], $message);
        return $message;
    }

    protected function validateAmbiguousRevCalculations($attribute, $value, $parameters) {
        if (!$this->validateRequired($attribute, $value)) {
            if (array_get($this->data, $parameters[0]) != 0 && array_get($this->data, $parameters[1]) != 0) {
                return false;
            }
        }
        return true;
    }

}
