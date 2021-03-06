<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PyScript implements Rule
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
        //must return a true or false
		//explodes original filename into an array split by ., then checks the final element (i.e. the extension)
		//to see if it's a .py file. To extend to other file types, add an || end($exploded) == "YOUREXTHERE" to the if statement

		//this checks if there is a value set - if not, they clicked x on the script and didn't upload a new one, and they'll
		//be returned with an error message
		if(isset($value)){
			$exploded = explode(".", $value->getClientOriginalName());
			if(end($exploded) == "py"){
				return true;
			}
			else{
				return false;
			}
		}
		else{
			return false;
		}

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Only .py files may be uploaded.';
    }
}
