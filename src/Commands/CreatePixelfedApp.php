<?php

namespace Inovector\Mixpost\Commands;

use Illuminate\Console\Command;
use Inovector\Mixpost\Actions\CreatePixelfedApp as CreatePixelfedAppAction;
use Inovector\Mixpost\Facades\ServiceManager;

class CreatePixelfedApp extends Command
{
    public $signature = 'mixpost:create-pixelfed-app {server}';

    public $description = 'Create new pixelfed application for a server';

    public function handle(): int
    {
        $server = $this->argument('server');

        $serviceName = "pixelfed.$server";

        if (ServiceManager::get($serviceName)) {
            if (!$this->confirm('Are you sure you want to create a new application for this server?')) {
                return self::FAILURE;
            }

            $this->comment("This action may have a negative impact on scheduled posts and authenticated accounts with Pixelfed on $server server.");

            if (!$this->confirm('I confirm that I understand the risks and I will reauthenticate all accounts on this Pixelfed server.')) {
                return self::FAILURE;
            }
        }

        $result = (new CreatePixelfedAppAction())($server);

        if (isset($result['error'])) {
            $this->error($result['error']);

            return self::FAILURE;
        }

        $this->info("A new application for the $server server has been created!");

        return self::SUCCESS;
    }
} 