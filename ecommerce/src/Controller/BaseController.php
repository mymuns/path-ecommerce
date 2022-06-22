<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Persistence\ObjectManager;

class BaseController extends AbstractController
{
    private ObjectManager $_em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->_em = $em;
    }

    /**
     * @param $persistenObject
     * @return \Doctrine\ORM\EntityRepository|\Doctrine\Persistence\ObjectRepository
     */
    public function getRepository($persistenObject)
    {
        return $this->_em->getRepository($persistenObject);
    }

    /**
     * @param $data
     * @param null $message
     * @param int $status
     * @return JsonResponse
     */
    public function response($data, $message=null, $status=200): JsonResponse
    {
        return $this->json([
            'message' => $message ?? 'Başarılı',
            'data' => $data,
            'status' => $message ? 'Başarısız' : 200,
        ]);
    }
}
