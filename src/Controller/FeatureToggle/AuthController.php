<?php

declare(strict_types=1);

namespace App\Controller\FeatureToggle;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Unleash\Client\Configuration\Context;
use Unleash\Client\Unleash;

class AuthController extends AbstractController
{
    #[Route('/feature-toggle/auth/role-any', name: 'app_feature_toggle_auth_role_any')]
    public function roleAny(Unleash $unleash, Context $context): Response
    {
        return $this->render('feature-toggle/auth/any-role.html.twig', [
            'is_content_for_admin' => $unleash->isEnabled('admin-feature-flag', $context),
        ]);
    }
}
