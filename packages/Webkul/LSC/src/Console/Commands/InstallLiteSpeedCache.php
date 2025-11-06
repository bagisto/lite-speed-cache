<?php

namespace Webkul\LSC\Console\Commands;

use Illuminate\Console\Command;

class InstallLiteSpeedCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'litespeed:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install and configure LiteSpeed Cache for Bagisto';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->info('Publishing vendor assets...');
        $this->call('vendor:publish', [
            '--provider' => 'Webkul\LSC\Providers\LSCServiceProvider',
            '--force'    => true,
        ]);

        $this->info('Clearing optimization cache...');
        $this->call('optimize:clear');

        $this->info('LiteSpeed Cache installation completed!');
    }
}
