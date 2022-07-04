<?php

namespace App\Providers;

use App\Rules\BarcodeRule;
use App\Rules\SpreadsheetColumnNamesRule;
use Illuminate\Support\ServiceProvider;

class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        self::barcodeRule();
        self::requiredColumnsRule();
    }

    private function barcodeRule()
    {
        $barcode_rule = new BarcodeRule();
        \Validator::extend('barcode', function($attribute, $value, $parameters) use ($barcode_rule) {
            return $barcode_rule->passes($attribute, $value, $parameters);
        });

        \Validator::replacer('barcode', function($message, $attribute, $rule, $parameters) use($barcode_rule) {
            return $barcode_rule->message();
        });
    }

    private function requiredColumnsRule()
    {
        $spreadsheet_columns = new SpreadsheetColumnNamesRule();
        \Validator::extend('spreadsheet_columns', function($attribute, $value, $parameters) use ($spreadsheet_columns) {
            return $spreadsheet_columns->passes($attribute, $value, $parameters);
        });

        \Validator::replacer('spreadsheet_columns', function($message, $attribute, $rule, $parameters) use($spreadsheet_columns) {
            return $spreadsheet_columns->message();
        });
    }
}
