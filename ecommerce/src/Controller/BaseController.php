<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{

    public function response($data, $message=null,$status=200): JsonResponse
    {
        return $this->json([
            'message' => $message ?? 'Başarılı',
            'data' => $data,
            'status' => $message ? 'Başarısız' : 200,
        ]);
    }
}
