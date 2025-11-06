<?php

namespace Webkul\LSC\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

trait DeletesAllCache
{
    /**
     * Delete all files and directories inside the priv cache folder.
     *
     * @param  string  $privPath
     * @return void
     */
    public function deletePrivCache($privPath = '/usr/local/lsws/cachedata/priv')
    {
        try {
            if (File::exists($privPath)) {
                $items = File::directories($privPath);
                $items = array_merge($items, File::files($privPath));

                foreach ($items as $item) {
                    $path = is_object($item) ? $item->getPathname() : $item;

                    if (File::isDirectory($path)) {
                        File::deleteDirectory($path);
                    } else {
                        File::delete($path);
                    }
                }

                Log::info('Deleted all files and directories inside priv folder', ['path' => $privPath]);
            } else {
                Log::warning('Priv path does not exist', ['path' => $privPath]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to delete priv contents: '.$e->getMessage());
        }
    }
}
