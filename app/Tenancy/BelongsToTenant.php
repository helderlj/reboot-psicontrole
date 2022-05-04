<?php
namespace App\Tenancy;

use App\Models\User;
use App\Tenancy\TenantScope;

trait BelongsToTenant
{
    public static function bootBelongsToTenant()
    {
        static::addGlobalScope(new TenantScope());

        if (auth()->check()) {
            static::creating(function ($model) {
                $model->user_id = auth()->id();
            });
        }
    }

    public function tenant()
    {
        $this->belongsTo(User::class, 'user_id');
    }
}
