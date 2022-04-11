<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    public const GENDER_MALE = "male";
    public const GENDER_FEMALE = "female";

    public const LEVEL_USER = 1;
    public const LEVEL_ADMIN = 2;
    public const LEVEL_SUPER = 3;

    public const STATUS_BANNED = "banned";
    public const STATUS_ARCHIVED = "archived";
    public const STATUS_ACTIVE = "active";

    public const ALLOWED_GENDERS = [
        self::GENDER_MALE,
        self::GENDER_FEMALE
    ];

    public const ALLOWED_LEVELS = [
        self::LEVEL_USER,
        self::LEVEL_ADMIN,
        self::LEVEL_SUPER
    ];

    public const ALLOWED_STATUS = [
        self::STATUS_BANNED,
        self::STATUS_ARCHIVED,
        self::STATUS_ACTIVE
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
        'gender'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @param array $data
     * 
     * @return bool
     */
    public function ban(array $data): bool
    {
        /** @var \App\Models\Ban $ban */
        $ban = (new Ban())->new($this, $data);
        if (!$ban->save()) {
            return false;
        }

        $this->level = self::LEVEL_USER;
        $this->status = self::STATUS_BANNED;

        return $this->save();
    }

    /**
     * @return bool
     */
    public function unban(): bool
    {
        /** @var \App\Models\Ban $ban */
        $ban = $this->bans()->first();
        if ($ban && !$ban->done_at) {
            if ($ban->isPerm()) {
                $ban->delete();
            } else {
                $doneDate = date("Y-m-d H:i:s", strtotime("+ {$ban->days}days", strtotime($ban->created_at)));
                if ($doneDate > date("Y-m-d H:i:s")) {
                    $ban->delete();
                } else {
                    $ban->done_at = date("Y-m-d H:i:s");
                    $ban->save();
                }
            }
        }

        $this->status = self::STATUS_ACTIVE;

        return $this->save();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bans()
    {
        return $this->hasMany(Ban::class, "users_id", "id")->orderBy("created_at", "desc");
    }

    public function isUser(): bool
    {
        return $this->level == self::LEVEL_USER;
    }

    public function isAdmin(): bool
    {
        return $this->level == self::LEVEL_ADMIN;
    }

    public function isSuper(): bool
    {
        return $this->level == self::LEVEL_SUPER;
    }
}
