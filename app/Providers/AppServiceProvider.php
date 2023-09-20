<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
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
    public function boot() : void
    {
        Validator::extend('valid_email_domain', function ($attribute, $value, $parameters, $validator) {
            $allowedDomains = ['id', 'net', 'com'];
            $emailParts = explode('@', $value);
            $domainParts = explode('.', end($emailParts));
            if (count($domainParts) === 2) {
                list(, $domain) = $domainParts;
                return in_array(strtolower($domain), $allowedDomains);
            } else if(count($domainParts) > 2) {
                list(, $domain) = [$domainParts[count($domainParts) - 2], $domainParts[count($domainParts) - 1]];
                return in_array(strtolower($domain), $allowedDomains);
            }
            return false;
        });

        Validator::replacer('valid_email_domain', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':domain', implode(', ', $parameters), $message);
        });
    }
}
