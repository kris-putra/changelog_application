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

    protected static function booted()
    {
        static::saving(function ($model) {
            if ($model->url) {
                $model->url = strtolower(preg_replace('#^https?://(www\.)?#', '', rtrim($model->url, '/')));
            }
        });
    }

    public function featureRequests()
    {
        return $this->hasMany(FeatureRequest::class);
    }
}
