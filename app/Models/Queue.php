<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Queue extends Model
{
    protected $table = 'queues';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'status'
    ];

    public function windowGroups(): HasMany
    {
        return $this->hasMany(WindowGroup::class);
    }
}
