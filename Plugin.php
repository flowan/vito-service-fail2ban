<?php

namespace App\Vito\Plugins\Flowan\VitoServiceFail2ban;

use App\Plugins\AbstractPlugin;
use App\Plugins\RegisterServiceType;
use App\Plugins\RegisterViews;
use App\Vito\Plugins\Flowan\VitoServiceFail2ban\Services\Fail2ban;

class Plugin extends AbstractPlugin
{
    protected string $name = 'Fail2Ban';

    protected string $description = 'Daemon to ban hosts that cause multiple authentication errors.';

    public function boot(): void
    {
        RegisterViews::make('vito-service-fail2ban')
            ->path(__DIR__.'/views')
            ->register();

        RegisterServiceType::make(Fail2ban::id())
            ->type(Fail2ban::type())
            ->label('Fail2Ban')
            ->handler(Fail2ban::class)
            ->versions([
                'latest',
            ])
            ->register();
    }
}
