<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;

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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('excel-progress.{id}', fn(User $user, $id) => (int) $user->id === (int) $id);
Broadcast::channel('importing-progress.{id}', fn(User $user, $id) => (int) $user->id === (int) $id);
Broadcast::channel('updating-progress.{id}', fn(User $user, $id) => (int) $user->id === (int) $id);
Broadcast::channel('updating-progress-all.{id}', fn(User $user, $id) => (int) $user->id === (int) $id);
Broadcast::channel('generating-deposited-checks.{id}', fn(User $user, $id) => (int) $user->id === (int) $id);
Broadcast::channel('generating-bounce-checks.{id}', fn(User $user, $id) => (int) $user->id === (int) $id);
Broadcast::channel('generating-dated-pdc-reports-accounting.{id}', fn(User $user, $id) => (int) $user->id === (int) $id);
Broadcast::channel('generating-deposited-checks-accounting.{id}', fn(User $user, $id) => (int) $user->id === (int) $id);
