<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Menimpa default email verifikasi agar pakai APP_URL dari .env
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            $parsedUrl = parse_url($url);
            $customUrl = config('app.url') . ($parsedUrl['path'] ?? '') . '?' . ($parsedUrl['query'] ?? '');

            return (new MailMessage)
                ->subject('Verify Email Address')
                ->line('Please click the button below to verify your email address.')
                ->action('Verify Email Address', $customUrl)
                ->line('If you did not create an account, no further action is required.');
        });
    }
}
