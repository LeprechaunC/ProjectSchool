<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'start_time',
        'end_time',
        'team_id',
        'user_id',
        'done',
        'priority',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'done' => 'boolean',
    ];

    /**
     * Get the user that owns the goal.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the team that owns the goal.
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Scope a query to only include completed goals.
     */
    public function scopeCompleted($query)
    {
        return $query->where('done', true);
    }

    /**
     * Scope a query to only include pending goals.
     */
    public function scopePending($query)
    {
        return $query->where('done', false);
    }

    /**
     * Scope a query to only include overdue goals.
     */
    public function scopeOverdue($query)
    {
        return $query->where('done', false)
                    ->where('end_time', '<', now());
    }
}
