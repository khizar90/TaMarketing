<?php

namespace App\Actions;


class FirebaseNotification
{
    public static function handle($tokens,$body,$title,$arr)
    {



        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

        $notification = [
            'title' => $title,
            'body' => $body,
        ];

        $extraNotificationData = $arr;

        $fcmNotification = [
            'registration_ids'        => $tokens, //single token
            'notification' => $notification,
            'data' => $extraNotificationData
        ];

        $headers = [
            'Authorization: key= AAAAELsVv_A:APA91bGgMXUbzGv99e34ffeFPoLUAzJmiCutcL1s9zEZpdWFhMjNLtQU-DWeyJ9orAVhqrd0Ey5TIat4fCnvv2xEwsIE34o43wbq2KX4zwk8W7-xl7WlWHszabJXHLsqzBdzqQQPtLaD',
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);
        // dd($result);
        return $result;
    }
}