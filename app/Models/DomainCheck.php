<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DomainCheck extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'domain_id',
        'status_code',
        'response_time',
        'is_successful',
        'error_message',
        'created_at',
    ];

    protected $casts = [
        'is_successful' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }
}
