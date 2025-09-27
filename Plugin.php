<?php

namespace App\Vito\Plugins\Flowan\VitoServiceFail2ban;

use App\Plugins\AbstractPlugin;
use App\Plugins\RegisterServiceType;
use App\Vito\Plugins\Flowan\VitoServiceFail2ban\Services\Fail2ban;

class Plugin extends AbstractPlugin
{
    protected string $name = 'Fail2Ban';

    protected string $description = 'Daemon to ban hosts that cause multiple authentication errors.';

    public function boot(): void
    {
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
