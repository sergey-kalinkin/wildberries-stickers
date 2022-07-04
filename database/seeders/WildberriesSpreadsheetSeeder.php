<?php

namespace Database\Seeders;

use App\Models\Spreadsheet;
use App\Models\SpreadsheetField;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WildberriesSpreadsheetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::beginTransaction();

            //
            $sheet = Spreadsheet::firstOrCreate(
                self::tableDefinition()
            );

            //
            $fields = self::fieldsDefinition();
            $data = SpreadsheetField::factory()
                ->for($sheet, 'spreadsheet')
                ->count(count($fields))
                ->state(new Sequence(...$fields))
                ->make();

            /** @var SpreadsheetField $item */
            $data->each(function ($item) {
                try {
                    $item->save();
                } catch (\Throwable $t) {
                };
            });

            DB::commit();
        } catch (\Throwable $t) {
            DB::rollBack();
            throw $t;
        }
    }

    private function tableDefinition()
    {
        return [
            'name' => ($wb = 'Wildberries'),
            'code' => Str::of($wb)->slug('_'),
        ];
    }

    private function fieldsDefinition()
    {
        return [
            [
                'name' => 'Количество',
                'code' => 'quantity',
                'validation_type' => 'integer',
            ],
            [
                'name' => 'Баркод',
                'code' => 'barcode',
                'validation_type' => 'barcode:EAN13',
                'is_special' => true,
                'sequence' => 0,
            ],
            [
                'name' => 'Изготовитель (Юр.лицо)',
                'code' => 'manufacturer',
                'validation_type' => 'string',
                'is_special' => true,
                'sequence' => 1,
            ],
            [
                'name' => 'Цвет',
                'code' => 'color',
                'validation_type' => 'string',
            ],
            [
                'name' => 'Размер на бирке',
                'code' => 'size',
                'validation_type' => 'alpha_num',
            ],
        ];
    }
}
