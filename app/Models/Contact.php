<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'gender',
        'profile_image',
        'additional_file',
        'additional_emails',
        'additional_phones',
        'custom_field_values',
        'status',
        'merged_into_id'
    ];

    protected $casts = [
        'additional_emails' => 'array',
        'additional_phones' => 'array',
        'custom_field_values' => 'array',
    ];

    public function mergedInto()
    {
        return $this->belongsTo(Contact::class, 'merged_into_id');
    }

    public function mergedContacts()
    {
        return $this->hasMany(Contact::class, 'merged_into_id');
    }

    public function getInitialsAttribute()
    {
        $words = explode(' ', $this->name);
        $initials = '';
        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        return substr($initials, 0, 2);
    }
}



