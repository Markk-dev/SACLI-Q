<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class queued extends Model
{
    protected $table = 'queued';
    public $timestamps = true;

    protected $fillable = [
        'code', 
        'name',
        'status',
        'handled_by',
        'window_group_id',	
        'queue_id',
        'called_at'

    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function windowGroup()
    {
        return $this->belongsTo(WindowGroup::class);
    }
}
