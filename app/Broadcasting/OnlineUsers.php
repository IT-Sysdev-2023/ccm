<?php

namespace App\Broadcasting;

use App\Models\User;
use Illuminate\Support\Facades\Storage;


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

        $image = "/storage/users-image/$user->id";

        return [
            'id' => $user->id,
            'name' => $user->name,
            'usertype' => $user->usertype_id,
            'image' => $image,
            'username' => $user->username
        ];
    }
}
