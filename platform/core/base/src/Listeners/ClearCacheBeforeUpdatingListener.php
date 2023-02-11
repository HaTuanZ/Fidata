<?php

namespace Botble\Base\Listeners;

use Botble\Base\Supports\Helper;
use Exception;
use Illuminate\Support\Facades\File;
use Menu;

class ClearCacheBeforeUpdatingListener
{
    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Helper::clearCache();
            Menu::clearCacheMenuItems();

            foreach (File::glob(config('view.compiled') . '/*') as $view) {
                File::delete($view);
            }

            File::delete(app()->getCachedConfigPath());
            File::delete(app()->getCachedRoutesPath());
            File::delete(base_path('bootstrap/cache/packages.php'));
            File::delete(base_path('bootstrap/cache/services.php'));
            File::deleteDirectory(storage_path('app/purifier'));
        } catch (Exception $exception) {
            info($exception->getMessage());
        }
    }
}
