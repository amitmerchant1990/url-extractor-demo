<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $url
 * @property int $title
 * @property string $image
 * @property int $desc
 * @property \Carbon\Carbon $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Urlinfo extends Model
{
    use SoftDeletes;
    /**
     * @var array
     */
    protected $fillable = ['id', 'url', 'title', 'image', 'desc'];

    /**
     * @var array
     */
    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
}
