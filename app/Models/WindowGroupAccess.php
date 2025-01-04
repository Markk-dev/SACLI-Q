<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WindowGroupAccess extends Model
{
    protected $table = 'window_group_access';
    public $timestamps = true;
    protected $fillable = [
        'queue_id',
        'user_id',
        'window_group_id',
        'window_name',
        'can_close_own_window',
        'can_close_any_window',
        'can_close_queue',
        'can_clear_queue',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function windowGroup()
    {
        return $this->belongsTo(WindowGroup::class);
    }
}