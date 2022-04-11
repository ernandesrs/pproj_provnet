<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contact_messages';

    /**
     * @param array $data
     * @return ContactMessage
     */
    public function new(array $data): ContactMessage
    {
        $this->email = $data["email"];
        $this->phone_number = $data["phone_number"] ?? "none";

        $data["sent_at"] = null;

        $this->content = json_encode($data);

        return $this;
    }

    /**
     * @param string|null $email
     * @return bool
     */
    public function hasSentToday(?string $email = null): bool
    {
        $email = $email ?? $this->email;

        $r = $this->where("email", "=", $email)->orderBy("created_at", "DESC")->first();
        if (!$r) return false;

        return !$r ? false : strtotime("+1 day", strtotime($r->created_at)) >= time();
    }
}
