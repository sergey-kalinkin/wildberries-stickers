<?php

namespace App\Models;

use Database\Factories\SpreadsheetFieldFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SpreadsheetField
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $validation_type
 * @property int $spreadsheet_id
 * @property-read \App\Models\Spreadsheet $spreadsheet
 * @method static \Database\Factories\SpreadsheetFieldFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|SpreadsheetField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SpreadsheetField newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SpreadsheetField query()
 * @method static \Illuminate\Database\Eloquent\Builder|SpreadsheetField whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpreadsheetField whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpreadsheetField whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpreadsheetField whereSpreadsheetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpreadsheetField whereValidationType($value)
 * @mixin \Eloquent
 */
class SpreadsheetField extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'code',
        'validation_type',
        'spreadsheet_id',
        'is_special',
        'sequence',
    ];

    public function spreadsheet()
    {
        return $this->belongsTo(Spreadsheet::class);
    }
}
