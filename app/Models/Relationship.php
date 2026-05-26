<?php

namespace App\Models;

use App\Traits\HasEncryptedRouteKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Relationship extends Model
{
    use HasEncryptedRouteKey;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'client_id',
        'company_name',
        'type',
        'lead_source',
        'city',
        'state',
        'zip',
        'address',
        'phone',
        'email',
        'fax',
        'website',
        'license',
        'insurance',
        'notes',
    ];

    protected $appends = ['encrypted_id'];

    public function getEncryptedIdAttribute()
    {
        return encrypt($this->id);
    }
}
