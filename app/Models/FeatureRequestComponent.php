<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeatureRequestComponent extends Model
{
    protected $fillable = [
        'feature_request_id',
        'component',
    ];

    public function featureRequest()
    {
        return $this->belongsTo(FeatureRequest::class);
    }
}