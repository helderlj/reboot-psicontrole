<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScheduleUnavailability extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['start_time', 'end_time', 'schedule_id'];

    protected $searchableFields = ['*'];

    protected $table = 'schedule_unavailabilities';

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
