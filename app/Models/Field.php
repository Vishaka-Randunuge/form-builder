<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Field extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'label',
        'type',
        'required',
        'order',
    ];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function options()
    {
        return $this->hasMany(FieldOption::class); // ✅ must be hasMany
    }
}
