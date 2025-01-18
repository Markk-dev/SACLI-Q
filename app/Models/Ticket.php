<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets';
    public $timestamps = true;

    protected $fillable = [
        'code', 
        'ticket_number', 
        'name',
        'status',
        'handled_by',
        'window_id',	
        'queue_id',
        'called_at',
        'completed_at',

    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    
    public function window()
    {
        return $this->belongsTo(Window::class);
    }

    
    public function queue()
    {
        return $this->belongsTo(Queue::class);
    }
}
