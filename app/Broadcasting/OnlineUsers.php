<?php

namespace App\Broadcasting;

use App\Models\User;
// use Illuminate\Support\Facades\Storage;



class OnlineUsers
{
    /**
     * Create a new channel instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     */
    public function join(User $user)
    {
        // $userAuthRecord = $user->authRecords()->where('action', 'logged in')->latest('id')->first();

        // $disk = Storage::disk('public');

        // $image = $disk->url("user_images/$user->id");

        // $details = $user->details?->details;

        return [

            'id' => $user->id,
            'name' => $user->name,
            'usertype' => $user->usertype_id
            // 'name' => $details['employee_name'] ?? $user->name,
            // 'ip' => $userAuthRecord->ip,
            // 'logged_in_at' => $userAuthRecord->created_at->toDayDateTimeString(),
            // 'image' => $image,
            // 'details' => $details,
            // 'timestamp' => $userAuthRecord->created_at->timestamp,
        ];
    }
}
