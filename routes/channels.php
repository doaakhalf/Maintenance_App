<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('maintenance-request.{id}', function ($user, $id) {
    
    return (int) $user->id === (int) $id;
});

Broadcast::channel('maintenance-perform.{id}', function ($user, $id) {
    
    return (int) $user->id === (int) $id;
});
Broadcast::channel('maintenance-request-change-status.{id}', function ($user, $id) {
    
    return (int) $user->id === (int) $id;
});
Broadcast::channel('maintenance-perform-change-status.{id}', function ($user, $id) {
    
    return (int) $user->id === (int) $id;
});

// Calibration

Broadcast::channel('calibration-request.{id}', function ($user, $id) {
    
    return (int) $user->id === (int) $id;
});


Broadcast::channel('calibration-perform.{id}', function ($user, $id) {
    
    return (int) $user->id === (int) $id;
});
// Assign Batch requests
// Calibration

Broadcast::channel('assign-batch-maintenance-requests.{id}', function ($user, $id) {
    
    return (int) $user->id === (int) $id;
});
Broadcast::channel('notify-ppm-equipment.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});
Broadcast::channel('calibration-request-change-status.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});
Broadcast::channel('calibration-perform-change-status.{id}', function ($user, $id) {
    
    return (int) $user->id === (int) $id;
});
// Broadcast::channel('assign-calibration-batch-maintenance-requests.{id}', function ($user, $id) {
    
//     return (int) $user->id === (int) $id;
// });
