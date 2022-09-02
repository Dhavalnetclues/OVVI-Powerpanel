<?php

namespace App\Rules;
use Config;
use Illuminate\Contracts\Validation\Rule;
use App\Helpers\MyLibrary;

class Distinct_Field implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $id;
    protected $filedName;
    protected $modelName;

    public function __construct($id = false, $filedName, $modelName,$message=false)
    {
        $this->id = $id;
        $this->filedName = $filedName;
        $this->modelName = '\\App\\'.$modelName;
         $this->message = $message;
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
        $response = false;
        if($this->filedName == 'email') {
            $value = Mylibrary::getEncryptedString($value);
        }
        $response = $this->modelName::where([[$this->filedName ,'=', $value],['chrDelete','=','N']]);

        if(is_numeric($this->id)) {
           $response = $response->where('id','!=',$this->id);
        }

        $response = $response->first();

        if(empty($response)) {             
            $response = true;
        } else {
            $response = false;
        }

        return $response;          
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $message = ':attribute already exist.';
      if($this->message){
        $message = $this->message;
      }
      return $message;
    }
}
