<?php

namespace App\Traits;

trait HasEncryptedRouteKey
{
    public function getRouteKey()
    {
        return encrypt($this->getKey());
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? $this->getRouteKeyName(), decrypt($value))->firstOrFail();
    }
}
