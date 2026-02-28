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

use App\Addons\FileHost\Http\Controllers\FileHostPublicController;
use Illuminate\Support\Facades\Route;

try {
    $prefix = setting('file_host_prefix', 'drive') ?: 'drive';
} catch (\Throwable $e) {
    $prefix = 'drive';
}

Route::get('/' . $prefix . '/{uuid}', [FileHostPublicController::class, 'download'])
    ->name('download')
    ->where('uuid', '[a-zA-Z0-9\.\-_]+(?:\/[a-zA-Z0-9\.\-_]+)*')
    ->withoutMiddleware([
        // Middleware de maintenance ClientXCMS (groupe web) — le vrai bloqueur
        \App\Http\Middleware\MaintenanceMiddleware::class,
        // Middleware de maintenance Laravel natif (globale), géré aussi par FileHostMaintenanceBypass
        \Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class,
    ]);
