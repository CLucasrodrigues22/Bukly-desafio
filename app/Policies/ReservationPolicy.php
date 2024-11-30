<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;

class ReservationPolicy
{
    /**
     * Determine if the given reservation can be viewed by the user.
     *
     * @param  User  $user
     * @param  Reservation  $reservation
     * @return bool
     */
    public function view(User $user, Reservation $reservation)
    {
        // only the owner of the reservation can view it
        return $user->id === $reservation->user_id;
    }
}
