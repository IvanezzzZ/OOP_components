<?php
require_once 'Database.php';

class Validate
{
    private $passed = false, $db = null, $errors = [];

    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    public function check($source, $items = [])
    {
        foreach ($items as $item => $rules)
        {
            foreach ($rules as $rule => $rule_value)
            {
                $input = $source[$item];

                if ($rule == 'required' && empty($input))
                {
                    $this->addError("Field $item is required");
                }
                elseif (!empty($input))
                {
                    switch ($rule)
                    {
                        case 'min':
                            if (strlen($input) < $rule_value)
                            {
                                $this->addError("$item must be a minimum of $rule_value characters.");
                            }
                        break;

                        case 'max':
                            if (strlen($input) > $rule_value)
                            {
                                $this->addError("$item must be a maximum of $rule_value characters.");
                            }
                        break;

                        case 'unique':
                            $result = $this->db->get($rule_value, [$item, '=', $input]);
                            if ($result->getCount())
                            {
                                $this->addError("$item already exist");
                            }
                            break;

                        case 'confirm':
                            if ($input != $source[$rule_value])
                            {
                                $this->addError("$item must be $rule_value");
                            }
                        break;

                        case 'email':
                            if (!filter_var($input, FILTER_VALIDATE_EMAIL))
                            {
                                $this->addError("$input is non an email type");
                            }
                        break;
                    }
                }
            }
        }
        if (empty($this->errors))
        {
            $this->passed = true;
        }

        return $this;
    }
    public function addError($error)
    {
        $this->errors[] = $error;
    }
    public function getErrors()
    {
        return $this->errors;
    }
    public function passed()
    {
        return $this->passed;
    }
}