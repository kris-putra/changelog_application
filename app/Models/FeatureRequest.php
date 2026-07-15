<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class FeatureRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'application_id','title','slug','description','pemohon_perubahan',
        'as_is','to_be','impact','attachment_filename','type','priority','status',
        'pic','rollback_plan','estimated_finish_at',

        'requested_by','assigned_to','metadata','votes_count','request_number',
        'started_at','completed_at','lesson_learned'
    ];

    protected $casts = [
        'metadata' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'estimated_finish_at' => 'datetime',
    ];

    private static array $priorityMap = [
        'low' => 'LO',
        'medium' => 'ME',
        'urgent' => 'UR',
    ];

    private static array $typeMap = [
        'feature' => 'FE',
        'change' => 'CH',
        'bug' => 'BU',
        'incident' => 'IN',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title) . '-' . Str::random(6);
            }

            if (empty($model->request_number)) {
                $model->request_number = static::generateRequestNumber($model->priority, $model->type);
            }
        });
    }

    public static function generateRequestNumber(string $priority, string $type, ?string $existingDate = null, ?int $existingSeq = null): string
    {
        $date = $existingDate ?? now()->format('Ymd');
        $pp = static::$priorityMap[$priority] ?? 'XX';
        $tt = static::$typeMap[$type] ?? 'XX';
        $seq = $existingSeq ?? (static::max('id') ?? 0);
        $nnnn = str_pad($seq + 1, 4, '0', STR_PAD_LEFT);

        return $date . $pp . $tt . $nnnn;
    }

    public function regenerateRequestNumber(): void
    {
        $date = substr($this->request_number, 0, 8);
        $nnnn = substr($this->request_number, -4);
        $this->request_number = static::generateRequestNumber($this->priority, $this->type, $date, (int) $nnnn - 1);
        $this->saveQuietly();
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

    public function components()
    {
        return $this->hasMany(FeatureRequestComponent::class);
    }

    public function technicalComponents()
    {
        return $this->belongsToMany(\App\Models\TechnicalComponent::class, 'feature_request_components', 'feature_request_id', 'technical_component_id');
    }

    public function affectedApplications()
    {
        return $this->belongsToMany(Application::class, 'feature_request_application')->withTimestamps();
    }
}
