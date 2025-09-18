<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'value',
        'order',
        'field_id',
    ];

    public function field()
    {
        return $this->belongsTo(Field::class);
    }
}
