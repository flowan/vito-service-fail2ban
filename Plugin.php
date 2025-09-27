<?php

namespace App\Vito\Plugins\Vitodeploy\PluginTemplate;

use App\Plugins\AbstractPlugin;

class Plugin extends AbstractPlugin
{
    protected string $name = 'Plugin Template';

    protected string $description = 'An example plugin template for vito plugins';

    public function boot(): void
    {
        // Register plugin features here
        // https://vitodeploy.com/docs/plugins
    }
}
