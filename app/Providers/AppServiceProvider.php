<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');

        // Custom error message for username regex
        Validator::replacer('regex', function ($message, $attribute, $rule, $parameters) {
            if ($attribute === 'username') {
                return 'Username hanya boleh terdiri dari huruf kecil, angka, titik (.) dan garis bawah (_).';
            }
            return $message;
        });
    }
}
