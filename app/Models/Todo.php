<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Todo extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'completed',
        'user_id',
        'board_id',
        'position',
        'reminder_at'
    ];
    
    protected $casts = [
        'completed' => 'boolean',
        'reminder_at' => 'datetime'
    ];
    
    /**
     * Получить пользователя, которому принадлежит задача.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Получить доску, к которой относится задача.
     */
    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class);
    }
}
