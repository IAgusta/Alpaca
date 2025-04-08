<?php

use Illuminate\Support\Facades\Schedule;
use App\Models\User;

Schedule::call(function () {
    User::whereNull('email_verified_at')
        ->where('created_at', '<', now()->subDays(30))
        ->delete();
})->daily();