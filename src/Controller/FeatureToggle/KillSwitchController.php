<?php

declare(strict_types=1);

namespace App\Controller\FeatureToggle;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Unleash\Client\Bundle\Attribute\IsNotEnabled;

#[IsNotEnabled('kill-switch-flag', Response::HTTP_SERVICE_UNAVAILABLE)]
class KillSwitchController extends AbstractController
{
    #[Route('/feature-flag/kill-switch', name: 'app_kill_switch')]
    public function index(): Response
    {
        return $this->render('feature-toggle/kill-switch.html.twig');
    }
}
