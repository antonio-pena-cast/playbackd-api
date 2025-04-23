<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model {
    /** @use HasFactory<\Database\Factories\ReservationFactory> */
    use HasFactory;

    public function users() {
        return $this->belongsToMany(User::class)->withPivot('type')->withPivot('review')->withPivot('rating')->withPivot('date');
    }

    public $guarded = [];
}
