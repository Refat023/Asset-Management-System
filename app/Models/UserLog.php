<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'login_at',
        'logout_at',
        'ip_address',
        'user_agent',
        'browser',
        'os',
        'device',
    ];

    protected $casts = [
        'login_at' => 'datetime',
        'logout_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship with User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get login count for a specific user
     */
    public static function getLoginCount($userId)
    {
        return self::where('user_id', $userId)
                   ->where('action', 'login')
                   ->count();
    }

    /**
     * Get last login for a specific user
     */
    public static function getLastLogin($userId)
    {
        return self::where('user_id', $userId)
                   ->where('action', 'login')
                   ->latest('login_at')
                   ->first();
    }

    /**
     * Get login history for a specific user
     */
    public static function getUserLoginHistory($userId, $limit = 50)
    {
        return self::where('user_id', $userId)
                   ->where('action', 'login')
                   ->latest('login_at')
                   ->paginate($limit);
    }
}
