<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'member_code',
        'name',
        'email',
        'contact_number',
        'address',
        'date_of_birth',
        'gender',
        'emergency_contact_name',
        'emergency_contact_number',
        'profile_photo_path',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'is_active' => 'boolean',
        ];
    }
}
