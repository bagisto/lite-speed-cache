<?php

namespace Webkul\LSC\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
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
     * @return int
     */
    public function handle()
    {
        $this->info('Clearing LiteSpeed Cache...');

        try {
            $success = $this->deletePrivCache();

            if ($success) {
                $this->info('✅ LiteSpeed Cache fully cleared!');
                return self::SUCCESS;
            } else {
                $this->warn('⚠️  Cache clearing completed with some errors. Check logs for details.');
                return self::FAILURE;
            }
        } catch (\Throwable $e) {
            $this->error('❌ Failed to clear LiteSpeed Cache: ' . $e->getMessage());
            
            Log::error('LSCache Command: Failed to clear cache', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return self::FAILURE;
        }
    }
}
