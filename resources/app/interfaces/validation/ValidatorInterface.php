<?php
namespace App\Interfaces\Validation;

interface ValidatorInterface
{
    /**
     * Sets the validation rules.
     * 
     * @param array $rules An associative array of rules where the key is the
     * $_POST key, and the value is a respect validation validator sequence.
     */
    public function setRules(array $rules);

    /**
     * Validates the data.
     */
    public function validate();

    /**
     * Retrieves all error messages.
     */
    public function getErrorMessages();

    /**
     * Checks of the validation was valid.
     */
    public function isValid();
}