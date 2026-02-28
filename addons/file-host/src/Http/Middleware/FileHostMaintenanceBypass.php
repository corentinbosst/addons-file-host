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

namespace App\Addons\FileHost\Http\Middleware;

use App\Addons\FileHost\Models\FileHost;
use Closure;
use Illuminate\Http\Request;

/**
 * Middleware enregistré via withoutMiddleware() sur la route publique file-host.
 *
 * Permet aux fichiers d'être servis même lorsque le site est en maintenance,
 * en court-circuitant le MaintenanceMiddleware de ClientXCMS (groupe web).
 * Note : PreventRequestsDuringMaintenance (middleware global du Kernel) n'est
 * pas géré ici — il doit être configuré au niveau du CMS si nécessaire.
 */
class FileHostMaintenanceBypass
{
    public function handle(Request $request, Closure $next)
    {
        // Si le site n'est PAS en maintenance, on laisse passer normalement.
        if (!app()->isDownForMaintenance()) {
            return $next($request);
        }

        // Récupérer le préfixe configuré (avec fallback sécurisé).
        $prefix = FileHost::getPrefix();
        $path   = ltrim($request->getPathInfo(), '/');

        // La requête ne correspond pas au préfixe file-host → on laisse passer.
        if (!str_starts_with($path, $prefix . '/')) {
            return $next($request);
        }

        // Extraire le UUID depuis le chemin : /{prefix}/{uuid}
        $uuid = substr($path, strlen($prefix) + 1);

        if (empty($uuid)) {
            return $next($request);
        }

        // Servir le fichier via la logique centralisée du modèle.
        $response = FileHost::serve($uuid);

        if ($response === null) {
            return response(__('file-host::messages.access_denied'), 404);
        }

        return $response;
    }
}
