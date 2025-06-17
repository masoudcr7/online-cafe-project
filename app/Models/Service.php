<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'base_price',
        'price_calculation_params',
        'initial_form_fields',
        'is_active',
        'requires_coordination',
    ];

    protected $casts = [
        'price_calculation_params' => 'array', // or 'json'
        'initial_form_fields' => 'array',      // or 'json'
        'is_active' => 'boolean',
        'requires_coordination' => 'boolean',
    ];

    public function initialRequests()
    {
        return $this->hasMany(InitialRequest::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
