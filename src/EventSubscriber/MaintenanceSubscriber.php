<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class MaintenanceSubscriber implements EventSubscriberInterface
{
    public function onKernelResponse(ResponseEvent $event)
    {
        // On a ajouté une variable dans le .env qui déclare
        // si on affiche l'annonce de la maintenance ou non
        // Cette variable est ensuite activée dans le fichier .env.local
        // Attention, même si on écrit true ou false dans les fichiers .env,
        // on attent une chaine caractéres et non un booléen !!
        if ($_ENV['MAINTENANCE_ANNOUNCEMENT'] == 'true') {   
            // On reçoit un objet ResponseEvent avec des détails sur l'événement
            // On récupère l'objet Response dedans
            $response = $event->getResponse();
            
            // L'objet Reponse contient toute sles informatiosn de la réponse HTTP
            // qui sera faite par Symfony (en-tête HTTP avec cookie et content-type, contenu de la page)
            // On récupère son contenu HTML car on souhaite le modifier
            $content = $response->getContent();
            
            // On modifie le contenu en remplaçant la balise body par elle-même+une balise div
            // Ce qui a pour effet d'ajouter une div derrière <body>
            // str_replace n'a pas modifié $content mais la chaine de caractère qu'elle contient
            // on doit affecter la valeur de retour de str_replace à $content
            $content = str_replace('<body>', '<body><div class="alert alert-danger">Maintenance prévue mardi 1er avril à 17h00</div>', $content);
            
            // On redéfinit le code HTML à retourner
            $response->setContent($content);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.response' => 'onKernelResponse',
        ];
    }
}
