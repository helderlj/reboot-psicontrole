<?php

namespace App\Models;

use App\Tenancy\BelongsToTenant;
use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory;
    use Searchable;
    use BelongsToTenant;

    protected $fillable = ['date', 'start_time', 'end_time', 'user_id'];

    protected $searchableFields = ['*'];

    protected $casts = [
        'date' => 'datetime',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scheduleUnavailabilities()
    {
        return $this->hasMany(ScheduleUnavailability::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
