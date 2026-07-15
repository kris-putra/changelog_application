<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TechnicalComponent extends Model
{
    protected $fillable = ['name', 'display_order'];

    public function featureRequests(): BelongsToMany
    {
        return $this->belongsToMany(FeatureRequest::class, 'feature_request_components', 'technical_component_id', 'feature_request_id');
    }
}