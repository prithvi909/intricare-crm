<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MergeHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'master_contact_id',
        'merged_contact_id',
        'master_contact_name',
        'merged_contact_name',
        'merged_data',
        'fields_added'
    ];

    protected $casts = [
        'merged_data' => 'array',
        'fields_added' => 'array',
    ];

    public function masterContact()
    {
        return $this->belongsTo(Contact::class, 'master_contact_id');
    }

    public function mergedContact()
    {
        return $this->belongsTo(Contact::class, 'merged_contact_id');
    }
}



