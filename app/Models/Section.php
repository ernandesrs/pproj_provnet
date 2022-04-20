<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    public function elements()
    {
        return $this->hasMany(SectionElement::class, "sections_id", "id");
    }
}
