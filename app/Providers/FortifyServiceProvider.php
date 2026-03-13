<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->configureActions();
        $this->configureViews();
        $this->configureRateLimiting();
        $this->configureAuthentication();
    }

    private function configureActions(): void
    {
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::createUsersUsing(CreateNewUser::class);
    }

    private function configureViews(): void
    {
        Fortify::loginView(function () {
            return view('auth.login');
        });
    }

    private function configureRateLimiting(): void
    {
        RateLimiter::for('login', function (Request $request) {
            $name = (string) $request->input('name');

            return Limit::perMinute(5)->by($name . '|' . $request->ip());
        });
    }

    private function configureAuthentication(): void
    {
        Fortify::authenticateUsing(function (Request $request) {
            $request->validate([
                'name' => ['required', 'string'],
                'password' => ['required', 'string'],
            ], [
                'name.required' => 'Username wajib diisi.',
                'password.required' => 'Password wajib diisi.',
            ]);

            $user = User::where('name', $request->name)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }

            return $this->failedAuthentication();
        });
    }

    private function failedAuthentication()
    {
        return null;
    }
}
