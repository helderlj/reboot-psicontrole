<?php

namespace App\Models;

use App\Tenancy\BelongsToTenant;
use App\Models\Scopes\Searchable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Appointment extends Model
{
    use HasFactory;
    use Searchable;
    use BelongsToTenant;

    protected $fillable = [
        'start_time',
        'end_time',
        'date',
        'uuid',
        'token',
        'status',
        'cancelled_at',
        'user_id',
        'patient_id',
        'schedule_id',
        'service_id',
        'hightlights',
        'nature',
    ];

    protected $searchableFields = ['*'];

    protected $casts = [
        'date' => 'datetime',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'cancelled_at' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        static::creating(function ($appointment) {
            $appointment->uuid = (string) Str::uuid();
            $appointment->token = (string) Str::random(25);
            $appointment->start_time = $appointment->start_time->toTimeString();
            $appointment->end_time = $appointment->start_time->clone()->addMinutes(
                Service::find($appointment->service_id)->duration
            )->toTimeString();
            $appointment->date = Schedule::find($appointment->schedule_id)->date;
        });
    }

    public function scopeNotCancelled(Builder $builder)
    {
        $builder->whereNull('cancelled_at');
    }

    public function isCancelled()
    {
        return !is_null($this->cancelled_at);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
