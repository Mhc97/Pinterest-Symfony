<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class LocaleController extends AbstractController
{
    #[Route('/locale/{locale}', name: 'app_locale_switch', requirements: ['locale' => 'fr|en'])]
    public function switch(string $locale, Request $request): RedirectResponse
    {
        $request->getSession()->set('_locale', $locale); // on mémorise le choix

        $referer = $request->headers->get('referer'); // la page d'où venait le clic

        return $referer ? $this->redirect($referer) : $this->redirectToRoute('app_pin_index');
    }
}

