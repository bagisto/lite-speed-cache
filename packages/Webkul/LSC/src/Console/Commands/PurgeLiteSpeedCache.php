<?php

namespace Webkul\LSC\Console\Commands;

use Illuminate\Console\Command;
use Webkul\LSC\Traits\DeletesAllCache;

class PurgeLiteSpeedCache extends Command
{
    use DeletesAllCache;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'litespeed:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the LiteSpeed Cache completely via HTTP header and file deletion';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->deletePrivCache();

        $this->info('✅ LiteSpeed Cache fully cleared!');
    }
}
