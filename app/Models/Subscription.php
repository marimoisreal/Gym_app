<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    // This array creates a "white list" of fields. Only those fields that you specify here will allow Laravel to save through the create() method.
    //If you forget to add end_date here, it simply won’t be recorded in the database, and you’ll have to wonder why for a long time
    protected $fillable = ['user_id', 'start_date', 'end_date', 'type', 'price'];

    // This array casts the date fields to Carbon instances automatically
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];
    // Having a subscription object in hand, this will allow, easily find out who its owner is
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
