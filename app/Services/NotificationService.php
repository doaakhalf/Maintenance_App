<?php
// app/Services/NotificationService.php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class NotificationService
{
    /**
     * Get the count of unread notifications for the authenticated user.
     *
     * @return int
     */
    public function getNotificationCount()
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            // Return the count of unread notifications for the authenticated user
            return Auth::user()->unreadNotifications()->count();
        }

        // Return 0 if the user is not authenticated
        return 0;
    }
   
}
