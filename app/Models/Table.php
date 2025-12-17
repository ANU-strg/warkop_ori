<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Table extends Model
{
    protected $fillable = [
        'uuid',
        'table_number',
        'qr_code_image_path',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
