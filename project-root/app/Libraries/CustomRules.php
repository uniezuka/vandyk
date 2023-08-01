<?php 

namespace App\Libraries;

use CodeIgniter\Validation\StrictRules\Rules;

class CustomRules
{
    private Rules $rules;

    public function __construct()
    {
        $this->rules = new Rules();
    }

    public function requiredIf(string $value, string $params, array $data, ?string &$error = null): bool
    {
        if ($params === null) {
            return true;
        }

        $params = explode(',', $params);
        
        $paramField = $params[0] ?? null;
        $paramValue = $params[1] ?? null;
        $paramRequiredField = $params[2] ?? null;

        $requiredFieldValue = "";

        if (array_key_exists($paramField, $data)) {
            $requiredFieldValue = $data[$paramField];

            if ($paramValue === $requiredFieldValue) {
                $error = 'The ' . $paramRequiredField . ' field is required.';
                return $this->rules->required($value ?? '');
            }

            return true;
        }

        return true;
    }
}