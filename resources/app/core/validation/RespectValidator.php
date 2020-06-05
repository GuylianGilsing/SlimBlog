<?php
namespace App\Core\Validation;

use Psr\Http\Message\ServerRequestInterface as Request;
use App\Interfaces\Validation\ValidatorInterface;
use Respect\Validation\Exceptions\NestedValidationException;

class RespectValidator implements ValidatorInterface
{
    private $request = null;
    private $rules = [];
    private $errors = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Sets the validation rules.
     * 
     * @param array $rules An associative array of rules where the key is the
     * $_POST key, and the value is a respect validation validator sequence.
     */
    public function setRules(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * Validates the data.
     * 
     * @param Request $request The request object from the controller.
     * @param array $rules an associative array that uses the keys as 
     */
    public function validate()
    {
        $rawPostData = $this->request->getParsedBody();

        if(count($this->rules) > 0)
        {
            foreach($this->rules as $inputName => $rules)
            {
                try
                {
                    if(isset($rawPostData[$inputName]))
                    {
                        $inputValue = $rawPostData[$inputName];
                        $rules->setName($inputName)->assert($inputValue);
                    }
                    else
                    {
                        $this->errors[$inputName] = "Please fill this field in.";
                    }
                }
                catch(NestedValidationException $e)
                {
                    $this->errors[$inputName] = $e->getMessages();
                }
            }
        }
        else
        {
            $this->errors[$inputName] = "Please fill this field in.";
        }
    }

    /**
     * Retrieves all error messages.
     */
    public function getErrorMessages()
    {
        return $this->errors;
    }

    /**
     * Checks of the validation was valid.
     */
    public function isValid()
    {
        return count($this->errors) <= 0;
    }
}