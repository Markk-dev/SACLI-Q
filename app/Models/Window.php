<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Window extends Model
{
    protected $table = 'windows';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'limit', // Daily ticket limit
        'description',
        'status'
    ];

    public function queue()
    {
        return $this->belongsTo(Queue::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'window_access', 'window_id', 'user_id');
    }

    public function tickets()
{
    return $this->hasMany(Ticket::class);
}
}
