<?php

declare(strict_types=1);

namespace App\Controller\FeatureToggle;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Unleash\Client\Bundle\Attribute\IsEnabled;
use Unleash\Client\Unleash;

class FeatureToggleController extends AbstractController
{
    #[Route('/feature-toggle/default', name: 'app_feature_toggle_default')]
    public function default(Unleash $unleash): Response
    {
        return $this->render('feature-toggle/default.html.twig', [
            'new_feature_enabled' => $unleash->isEnabled('default-feature-flag'),
        ]);
    }

    #[IsEnabled('attribute-feature-flag', Response::HTTP_SERVICE_UNAVAILABLE)]
    #[Route('/feature-toggle/attribute', name: 'app_feature_toggle_attribute')]
    public function attribute(): Response
    {
        return $this->render('feature-toggle/attribute.html.twig');
    }

    #[Route('/feature-toggle/twig', name: 'app_feature_toggle_twig')]
    public function twig(): Response
    {
        return $this->render('feature-toggle/twig.html.twig');
    }

    #[IsEnabled('stale-feature-flag', Response::HTTP_SERVICE_UNAVAILABLE)]
    #[Route('/feature-toggle/stale', name: 'app_feature_toggle_stale')]
    public function stale(): Response
    {
        return $this->render('feature-toggle/attribute.html.twig');
    }
}
