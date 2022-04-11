<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ban extends Model
{
    use HasFactory;

    public const TYPE_TEMP = "temp";
    public const TYPE_PERM = "perm";

    public const ALLOWED_TYPES = [
        self::TYPE_TEMP,
        self::TYPE_PERM
    ];

    /**
     * @param User $user
     * @param array $data
     * 
     * @return Bans
     */
    public function new(User $user, array $data)
    {
        $this->users_id = $user->id;
        $this->description = $data["description"];
        $this->type = $data["type"];
        if ($this->type == self::TYPE_PERM)
            $this->days = 0;
        else
            $this->days = $data["days"];
        return $this;
    }

    /**
     * @return User
     */
    public function user(): User
    {
        return $this->hasOne(User::class, "id", "users_id")->first();
    }

    /**
     * @return bool
     */
    public function isTemp(): bool
    {
        return $this->type == self::TYPE_TEMP;
    }

    /**
     * @return bool
     */
    public function isPerm(): bool
    {
        return $this->type == self::TYPE_PERM;
    }
}
