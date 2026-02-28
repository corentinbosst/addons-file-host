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

// On utilise un {prefix} dynamique au lieu d'appeler setting() ici.
// Appeler setting() au top-level des routes est incompatible avec php artisan route:cache
// (la valeur serait figée au moment du cache, ignorant tout changement ultérieur).
// Le contrôleur se charge de vérifier que le préfixe correspond au setting configuré.
Route::get('/{prefix}/{uuid}', [FileHostPublicController::class, 'download'])
    ->name('download')
    ->where('prefix', '[a-zA-Z0-9\-\_]+')
    ->where('uuid', '[a-zA-Z0-9\.\-_]+(?:\/[a-zA-Z0-9\.\-_]+)*')
    ->withoutMiddleware([
        // Middleware de maintenance ClientXCMS (groupe web)
        \App\Http\Middleware\MaintenanceMiddleware::class,
        // Note : PreventRequestsDuringMaintenance est un middleware GLOBAL (Kernel), withoutMiddleware()
        // est inefficace contre lui. Il est géré par FileHostMaintenanceBypass (prépendé au Kernel).
    ]);
