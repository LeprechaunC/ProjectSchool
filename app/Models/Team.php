<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'owner', 'invite_code'];
    // Define the relationship with users (many-to-many)
    public function users()
    {
        return $this->belongsToMany(User::class); // A team belongs to many users
    }

    // Define the relationship with goals (one-to-many)
    public function goals()
    {
        return $this->hasMany(Goal::class);
    }
}
