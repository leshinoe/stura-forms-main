<?php

use App\Notifications\UploadBlacklistFailedNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schedule;

Schedule::command('app:upload-blacklist')
    ->dailyAt('00:00')
    ->onFailure(function () {

        Notification::route('mail', 'contact@julius-kiekbusch.de')
            ->notify(new UploadBlacklistFailedNotification);

    });
