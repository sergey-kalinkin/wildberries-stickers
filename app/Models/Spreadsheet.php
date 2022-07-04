<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Spreadsheet
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SpreadsheetField[] $fields
 * @property-read int|null $fields_count
 * @method static \Illuminate\Database\Eloquent\Builder|Spreadsheet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Spreadsheet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Spreadsheet query()
 * @method static \Illuminate\Database\Eloquent\Builder|Spreadsheet whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Spreadsheet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Spreadsheet whereName($value)
 * @mixin \Eloquent
 */
class Spreadsheet extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'code',
    ];

    public function fields(): HasMany
    {
        return $this->hasMany(SpreadsheetField::class);
    }
}
