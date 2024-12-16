<?php

namespace Ravols\SocialiteSeznamDriver;

use Laravel\Socialite\Contracts\Factory;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SocialiteSeznamDriverServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('socialite-seznam-driver');
    }

    public function boot()
    {
        $socialite = $this->app->make(Factory::class);

        $socialite->extend('seznam', function () use ($socialite) {
            return $socialite->buildProvider(SocialiteSeznamProvider::class, config('services.seznam'));
        });
    }
}
