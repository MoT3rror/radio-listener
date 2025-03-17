<?php

namespace App\Controller\Api;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class RadiosController extends AbstractController
{
    #[Route('/api/radio/{radioId}/recordings', name: 'app_api_radio_recordings', methods: ['GET'])]
    public function recordings(Request $request, int $radioId): JsonResponse
    {
        return $this->json([
            'radio' => $radioId,
            'date' => $request->query->get('date'),
            'data' => [
                [
                    'id' => 1,
                    'record_start' => new DateTime(),
                    'record_end' => new DateTime(),
                ],
                [
                    'id' => 2,
                    'record_start' => new DateTime(),
                    'record_end' => new DateTime(),
                ],
                [
                    'id' => 3,
                    'record_start' => new DateTime(),
                    'record_end' => new DateTime(),
                ],
            ],
        ]);
    }
}
