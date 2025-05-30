<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model {
    /** @use HasFactory<\Database\Factories\ReservationFactory> */
    use HasFactory;

    public function users() {
        return $this->belongsToMany(User::class, "list")->withPivot('type', "review", "rating", "date");
    }

    public $guarded = [];
}
