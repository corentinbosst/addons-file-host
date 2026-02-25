<?php

/*
 * FileHost Addon for ClientXCMS V2
 * Author: Corentin WebSite
 * Year: 2026
 * License: Open Source
 *
 * Disclaimer: La maintenance de fonctionnement est assurée par Corentin WebSite.
 * En cas de modification du code par un tiers, l'auteur décline toute responsabilité
 * si le logiciel ne fonctionne plus correctement.
 */

namespace App\Addons\FileHost;

use App\Extensions\BaseAddonServiceProvider;
use App\Models\Admin\Permission;
use Illuminate\Support\Facades\Log;

class FileHostServiceProvider extends BaseAddonServiceProvider
{
    protected string $uuid = 'file-host';

    public function register()
    {
    }

    public function boot()
    {
        $this->loadTranslations();
        $this->loadViews();
        $this->loadMigrations();

        if (\Illuminate\Support\Facades\File::exists(addon_path($this->uuid, 'routes/admin.php'))) {
            \Illuminate\Support\Facades\Route::middleware(['web', 'admin'])
                ->prefix(admin_prefix())
                ->name('admin.')
                ->group(addon_path($this->uuid, 'routes/admin.php'));
        }

        if (\Illuminate\Support\Facades\File::exists(addon_path($this->uuid, 'routes/web.php'))) {
            \Illuminate\Support\Facades\Route::middleware(['web'])
                ->name('file-host.')
                ->group(addon_path($this->uuid, 'routes/web.php'));
        }

        $this->registerSettingsItems();
    }

    protected function registerSettingsItems(): void
    {
        try {
            $settings = $this->app->make('settings');

            if (!app()->bound('corentin_website_card_registered')) {
                $settings->addCard(
                    'corentin-website',
                    'file-host::messages.menu_card',
                    'file-host::messages.menu_card_desc',
                    55
                );
                app()->instance('corentin_website_card_registered', true);
            }

            $settings->addCardItem(
                'corentin-website',
                'file-host',
                'file-host::messages.title',
                'file-host::messages.subtitle',
                'bi bi-folder2-open',
                [\App\Addons\FileHost\Http\Controllers\Admin\FileHostController::class, 'index'],
                Permission::MANAGE_EXTENSIONS
            );
        } catch (\Exception $e) {
            Log::error("FileHost: Erreur menu: {$e->getMessage()}");
        }
    }
}
