<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FeatureRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id','title','slug','description','detail_perubahan','pemohon_perubahan',
        'as_is','to_be','klasifikasi_perubahan','type','priority','status',
        'requested_by','assigned_to','metadata','votes_count'
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title) . '-' . Str::random(6);
            }
        });
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
