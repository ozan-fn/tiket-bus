<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TerminalPhoto extends Model
{
    protected $fillable = [
        'terminal_id',
        'path',
    ];

    public function terminal()
    {
        return $this->belongsTo(Terminal::class);
    }
}
