<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidateBadWord implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $result = array();
        $badWords = array("nude", "naked", "sex", "porn", "porno", "sperm");

        preg_match("/\b(" . implode($badWords, "|") . ")\b/i", $value, $result);
        
        if (isset($result[0]) && !empty($result[0])) 
        {
            return false;
        } else {
            return true;
        }

    }
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Please remove bad word/inappropriate language.';
    }
}
