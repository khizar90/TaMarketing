<?php

namespace App\Actions;

use App\Models\Notification;

class NewNotification
{
    public static function handle($user,$data_id, $body, $type){

        $notification = new Notification();

        $notification->user_id = $user->uuid;
        $notification->body = $body;
        $notification->type = $type;
        $notification->data_id = $data_id;
        $notification->date = date('Y-m-d');
        $notification->time = strtotime(date('Y-m-d H:i:s'));
        $notification->save();
        return true;

    }
}