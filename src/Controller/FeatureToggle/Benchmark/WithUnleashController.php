<?php

declare(strict_types=1);

namespace App\Controller\FeatureToggle\Benchmark;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Unleash\Client\Unleash;

class WithUnleashController extends AbstractController
{
    #[Route('/feature-toggle/benchmark/with-unleash', name: 'app_feature_toggle_benchmark_with_unleash', methods: ['GET'])]
    public function withUnleash(Unleash $unleash): Response
    {
        return new JsonResponse(['flag_enabled' => $unleash->isEnabled('attribute-feature-flag')], Response::HTTP_OK);
    }

    #[Route('/feature-toggle/benchmark/with-unleash-multiple-calls', name: 'app_feature_toggle_benchmark_with_unleash_multiple_calls', methods: ['GET'])]
    public function withUnleashNestedCalls(Unleash $unleash): Response
    {
        $featureFlagsEnabled = [];
        for ($i = 0; $i <= 10; $i++) {
            $featureFlagsEnabled[] = $unleash->isEnabled('attribute-feature-flag');
        }

        return new JsonResponse(['flags_enabled' => $featureFlagsEnabled], Response::HTTP_OK);
    }
}
