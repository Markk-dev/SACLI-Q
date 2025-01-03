<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class WindowGroup extends Model
{
    protected $table = 'window_groups';

    protected $fillable = [
        'name',
        'description',
        'status'
    ];

    public function queue()
    {
        return $this->belongsTo(Queue::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'window_group_access', 'window_group_id', 'user_id');
    }
}
