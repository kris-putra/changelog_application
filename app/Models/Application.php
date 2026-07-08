<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'url',
        'location',
    ];

    public function featureRequests()
    {
        return $this->hasMany(FeatureRequest::class);
    }
}
