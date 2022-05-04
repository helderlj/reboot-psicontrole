<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['name', 'duration'];

    protected $searchableFields = ['*'];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
