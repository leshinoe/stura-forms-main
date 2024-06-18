<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Support\View\Components\Modal;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\Saml2\Saml2ExtendSocialite;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /** Disable mass assignment protection */
        Model::unguard();

        /** Register SAML2 Socialite Provider */
        Event::listen(SocialiteWasCalled::class, Saml2ExtendSocialite::class);

        /** Always include AlpineJS/Livewire assets */
        Livewire::forceAssetInjection();

        /** Disable closing modals in Filament by clicking away */
        Filament::serving(function () {
            Modal::closedByClickingAway(false);
        });

        /** Register rate limiter for Auth Routes */
        RateLimiter::for('auth', function (Request $request) {

            // Requests in the local environment are not rate limited
            if (App::environment('local')) {
                return Limit::none();
            }

            // Requests coming from the university network are not rate limited
            if (str_starts_with($request->ip(), '141.43.')) {
                return Limit::none();
            }

            return [
                Limit::perHour(100)->by('hour:'.$request->ip()),
                Limit::perMinute(40)->by('minute:'.$request->ip()),
                Limit::perSecond(6)->by('second:'.$request->ip()),
            ];
        });
    }
}
