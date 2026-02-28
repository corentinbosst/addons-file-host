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

namespace App\Addons\FileHost\Http\Controllers;

use App\Addons\FileHost\Models\FileHost;
use App\Http\Controllers\Controller;

class FileHostPublicController extends Controller
{
    public function download(string $prefix, string $uuid)
    {
        // Valider que le préfixe de l'URL correspond au setting configuré (runtime).
        // La route utilise {prefix} générique pour être compatible avec route:cache ;
        // on effectue donc la vérification ici, à chaque requête.
        $configuredPrefix = FileHost::getPrefix();
        if ($prefix !== $configuredPrefix) {
            abort(404);
        }

        $response = FileHost::serve($uuid);

        if ($response === null) {
            abort(404);
        }

        return $response;
    }
}
