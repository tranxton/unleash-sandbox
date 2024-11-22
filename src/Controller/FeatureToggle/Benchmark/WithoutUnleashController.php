<?php

declare(strict_types=1);

namespace App\Controller\FeatureToggle\Benchmark;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WithoutUnleashController extends AbstractController
{
    #[Route('/feature-toggle/benchmark/without-unleash', name: 'app_feature_toggle_benchmark_without_unleash', methods: ['GET'])]
    public function withoutUnleash(): Response
    {
        return new JsonResponse(['flag_enabled' => false], Response::HTTP_OK);
    }
}
