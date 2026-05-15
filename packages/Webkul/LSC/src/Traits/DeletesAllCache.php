<?php

namespace Webkul\LSC\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

trait DeletesAllCache
{
    /**
     * Delete all files and directories inside the priv cache folder.
     *
     * @param  string|null  $privPath  Optional path override. If null, uses config value.
     * @return bool Returns true on success, false on failure
     */
    public function deletePrivCache($privPath = null): bool
    {
        /**
         * Use provided path, or fallback to config, or use default
         */
        if ($privPath === null) {
            $privPath = core()->getConfigData('lsc.configuration.cache_application.cache_path')
                     ?? '/usr/local/lsws/cachedata/priv';
        }

        if (empty($privPath)) {
            Log::warning('LSCache: Empty cache path provided, skipping deletion');

            return false;
        }

        try {
            if (! File::exists($privPath)) {
                Log::warning('LSCache: Priv cache path does not exist', ['path' => $privPath]);

                return false;
            }

            if (! File::isDirectory($privPath)) {
                Log::error('LSCache: Path is not a directory', ['path' => $privPath]);

                return false;
            }

            if (! File::isWritable($privPath)) {
                Log::error('LSCache: Path is not writable', ['path' => $privPath]);

                return false;
            }

            $items = File::directories($privPath);
            $items = array_merge($items, File::files($privPath));
            $deletedCount = 0;
            $failedCount = 0;

            foreach ($items as $item) {
                $path = is_object($item) ? $item->getPathname() : $item;

                try {
                    if (File::isDirectory($path)) {
                        File::deleteDirectory($path);
                    } else {
                        File::delete($path);
                    }

                    $deletedCount++;
                } catch (\Exception $itemException) {
                    $failedCount++;

                    Log::warning('LSCache: Failed to delete item', [
                        'path'  => $path,
                        'error' => $itemException->getMessage(),
                    ]);
                }
            }

            Log::info('LSCache: Priv cache deletion completed', [
                'path'    => $privPath,
                'deleted' => $deletedCount,
                'failed'  => $failedCount,
            ]);

            return $failedCount === 0;

        } catch (\Throwable $e) {
            Log::error('LSCache: Critical error during cache deletion', [
                'path'  => $privPath,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return false;
        }
    }
}
