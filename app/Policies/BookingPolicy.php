<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Booking $booking): bool
    {
        // For now, allow all authenticated users (admin dashboard)
        // You can add more specific logic here, e.g., $user->hasPermission('edit_bookings')
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Booking $booking): bool
    {
        return true;
    }
}
