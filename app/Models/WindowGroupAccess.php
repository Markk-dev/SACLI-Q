<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WindowGroupAccess extends Model
{
    protected $table = 'window_group_access';
    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'window_group_id',
        'window_name',
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