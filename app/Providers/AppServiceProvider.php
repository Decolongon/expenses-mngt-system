<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Foundation\DevCommands;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Laravel\Fortify\Contracts\LogoutResponse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->configDevCommand();
        $this->unknownOnFailConfig();
        $this->configLogoutResponse();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }

    protected function configDevCommand(): void
    {
        DevCommands::artisan('optimize:clear', 'Clear caches')->yellow();

        DevCommands::artisan('optimize', 'Optimize')->blue();

        DevCommands::node('build', 'Build assets')->purple();

        DevCommands::except('queue');
        DevCommands::artisan('serve', 'Serve the application')->green();
    }

    protected function unknownOnFailConfig(): void
    {
        FormRequest::failOnUnknownFields();
    }

    protected function configLogoutResponse()
    {
        $this->app->instance(LogoutResponse::class, new class implements LogoutResponse
        {
            public function toResponse($request)
            {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                Inertia::clearHistory();
                return redirect()->intended(route('home'));
            }
        });
    }
}
