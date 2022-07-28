<?php

return [
    'driver' => env('FCM_PROTOCOL', 'http'),
    'log_enabled' => false,

    'http' => [
        'server_key' => env('FCM_SERVER_KEY', 'AAAA-ChRmSo:APA91bFMynyKAbcYElt2quizW1d8CmBHkeRIVvMtyUIpiTSOqVWSxyc3jQji4ZQpwam9XbA5mHoA8pxhOZatJ_aJLSaYN_tmUEBr2KPUj4ugr8CfLCbiOabFOEQ58d_PbNaUoX_3X_Ns'),
        'sender_id' => env('FCM_SENDER_ID', '1065828325674'),
        'server_send_url' => 'https://fcm.googleapis.com/fcm/send',
        'server_group_url' => 'https://android.googleapis.com/gcm/notification',
        'timeout' => 30.0, // in second
    ],
];
