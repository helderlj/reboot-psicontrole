<?php

namespace App\Models;

use App\Tenancy\BelongsToTenant;
use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory;
    use Searchable;
    use BelongsToTenant;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'phone_alt',
        'birthday',
        'starting_date',
        'is_active',
        'summary',
        'user_id',
    ];

    protected $searchableFields = ['*'];

    protected $casts = [
        'birthday' => 'date',
        'starting_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
