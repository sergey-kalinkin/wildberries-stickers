<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class BarcodeRule implements Rule
{
    private $message = '';

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
        try {
            $barcode_type = func_get_arg(2)[0] ?? 'EAN13';//TODO move types in config
            \DNS1D::getBarcodeHTML($value, $barcode_type);//TODO mpdf library has barcode generation, change on it
            return true;
        }
        catch (\Throwable $t) {
            $this->message = "code {$value} : {$t->getMessage()}";
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
        return $this->message;
    }
}
