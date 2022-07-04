<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * App\Models\StickerData
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property mixed $spreadsheet
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|StickerData newModelQuery()
 * @method static Builder|StickerData newQuery()
 * @method static Builder|StickerData query()
 * @method static Builder|StickerData whereCreatedAt($value)
 * @method static Builder|StickerData whereId($value)
 * @method static Builder|StickerData whereName($value)
 * @method static Builder|StickerData whereSpreadsheet($value)
 * @method static Builder|StickerData whereUpdatedAt($value)
 * @method static Builder|StickerData whereUserId($value)
 * @mixin \Eloquent
 * @property-read mixed $spreadsheet_data
 */
class StickerData extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $fillable = [
        'user_id',
        'name',
        'spreadsheet',
    ];
    protected $appends = ['spreadsheet_data'];

    //TODO move to service
    public static function getLastSpreadsheet()
    {
        $uid = Auth::id() or abort(404);
        return StickerData::whereUserId($uid)->orderByDesc('id')->first();
    }

    public static function tryGetSpreadsheet($id = null)
    {
        $uid = Auth::id() or abort(404);

        return isset($id) ? StickerData::whereUserId($uid)->whereId($id)->first() :
            StickerData::whereUserId($uid)->orderByDesc('id')->first();
    }

    public function getSpreadsheetDataAttribute()
    {
        return json_decode($this->spreadsheet);
    }
}
