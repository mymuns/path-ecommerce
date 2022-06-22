<?php

namespace App\Controller\Customer;

use App\Controller\BaseController;
use App\Entity\CustomerOrder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CustomerOrderController extends BaseController
{
    #[Route('/customer/order', name: 'app_customer_order')]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        $user = $this->getUser();

        $orders = $doctrine->getRepository(CustomerOrder::class)->getMyOrders($user);

        return $this->response($orders);
    }

    #[Route('/customer/show/{{orderCode}}', name: 'app_customer_order_show')]
    public function show(ManagerRegistry $doctrine, $orderCode): JsonResponse
    {
        $order = $doctrine->getRepository(CustomerOrder::class)->showOrderByOrderCode($this->getUser(),$orderCode);

        $message=null;
        $status = 200;
        if(!$order)
        {
           $message = "Talep edilen sipariş bulunamadı.";
            $status = 404;
        }
        return $this->response($order, $message, $status);
    }
}
