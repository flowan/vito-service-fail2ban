<?php

namespace App\Vito\Plugins\Flowan\VitoServiceFail2ban\Services;

use App\Services\AbstractService;
use Closure;

class Fail2ban extends AbstractService
{
    public static function id(): string
    {
        return 'fail2ban';
    }

    public static function type(): string
    {
        return 'security';
    }

    public function unit(): string
    {
        return 'fail2ban';
    }

    public function creationRules(array $input): array
    {
        return [
            'type' => [
                function (string $attribute, mixed $value, Closure $fail): void {
                    $existingFail2ban = $this->service->server->services()
                        ->where('name', self::id())
                        ->first();
                    if ($existingFail2ban) {
                        $fail('Fail2Ban is already installed on this server.');
                    }
                },
            ],
        ];
    }

    public function install(): void
    {
        $this->service->server->ssh()->exec(
            'sudo apt-get update -y && sudo apt-get install -y fail2ban',
            'install-fail2ban'
        );

        $status = $this->service->server->systemd()->status($this->unit());
        $this->service->validateInstall($status);

        event('service.installed', $this->service);
        $this->service->server->os()->cleanup();
    }

    public function uninstall(): void
    {
        if ($this->status() === 'running') {
            $this->stop();
        }

        $this->disable();

        $this->service->server->ssh()->exec(
            'sudo apt-get remove -y fail2ban',
            'uninstall-fail2ban'
        );

        event('service.uninstalled', $this->service);
        $this->service->server->os()->cleanup();
    }

    public function enable(): void
    {
        $this->service->server->systemd()->enable($this->unit());
    }

    public function disable(): void
    {
        $this->service->server->systemd()->disable($this->unit());
    }

    public function restart(): void
    {
        $this->service->server->systemd()->restart($this->unit());
    }

    public function stop(): void
    {
        $this->service->server->systemd()->stop($this->unit());
    }

    public function start(): void
    {
        $this->service->server->systemd()->start($this->unit());
    }

    public function status(): string
    {
        try {
            $result = $this->service->server->ssh()->exec("sudo systemctl is-active {$this->unit()}");

            return trim($result) === 'active' ? 'running' : 'stopped';
        } catch (\Exception $e) {
            return 'stopped';
        }
    }
}
