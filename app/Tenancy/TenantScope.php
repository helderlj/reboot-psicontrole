<?php

namespace App\Tenancy;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (auth()->check()) {
            if(auth()->user()->isSuperAdmin()) {
                $builder->withoutGlobalScope($this);
            } else {
                $builder->where('user_id', auth()->id());
            }
        }
    }

    public function extend(Builder $builder)
    {
        $this->addWithoutTenancy($builder);
    }

    /**
     * Adding a withoutTenancy scope to remove the tenant scope and
     * retrieve non-tenancy rows
     */
    protected function addWithoutTenancy(Builder $builder)
    {
        $builder->macro('withoutTenancy', function (Builder $builder) {
            return $builder->withoutGlobalScope($this);
        });
    }
}
