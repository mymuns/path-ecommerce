<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ObjectManager;
;

class BaseController extends AbstractController
{
    private ObjectManager $_em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->_em = $em;
    }

    public function getRepository($persistenObject)
    {
        return $this->_em->getRepository($persistenObject);
    }
    public function response($data, $message=null, $status=200): JsonResponse
    {
        return $this->json([
            'message' => $message ?? 'Başarılı',
            'data' => $data,
            'status' => $message ? 'Başarısız' : 200,
        ]);
    }
}
