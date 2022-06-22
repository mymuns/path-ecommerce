<?php

namespace App\Controller\Customer;

use App\Controller\BaseController;
use App\Entity\CustomerOrder;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CustomerOrderController extends BaseController
{
    #[Route('/customer/order', name: 'app_customer_order')]
    public function index(): JsonResponse
    {
        $user = $this->getUser();

        $orders = $this->getRepository(CustomerOrder::class)->getMyOrders($user);

        return $this->response($orders);
    }

    #[Route('/customer/show/{{orderCode}}', name: 'app_customer_order_show')]
    public function show($orderCode): JsonResponse
    {
        $order = $this->getRepository(CustomerOrder::class)->showOrderByOrderCode($this->getUser(),$orderCode);

        $message=null;
        $status = 200;
        if(!$order)
        {
           $message = "Talep edilen sipariş bulunamadı.";
            $status = 404;
        }
        return $this->response($order, $message, $status);
    }
    #[Route('/customer/create', name: 'app_customer_order_show')]
    public function create(Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager): JsonResponse
    {
        $parametersAsArray = [];
        if ($content = $request->getContent()) {
            $parametersAsArray = json_decode($content, true);
        }

        $constraints = new Assert\Collection([
            'productId' => [
                new Assert\NotBlank(),
                new Assert\Positive()
            ],
            'quantity' => [
                new Assert\NotBlank(),
                new Assert\Positive()
            ],
            'address' => [
                new Assert\NotBlank()
            ]
        ]);

        $validationResult = $validator->validate($parametersAsArray, $constraints);
        $errors = [];

        foreach($validationResult as $violation)
        {
            $errors[] = [$violation->getPropertyPath() => $violation->getMessage()];
        }
        if($errors)
        {
            $this->response($errors,"Hata",500);
        }

        $product = $this->getRepository(Product::class)->find($parametersAsArray['productId']);
        if(!$product)
        {
            return $this->response(null,"Bu ürün mevcut değil",404);
        }

        $customerOrder = new CustomerOrder();
        $customerOrder->setAddress($parametersAsArray['address']);
        $customerOrder->setQuantity($parametersAsArray['quantity']);
        $customerOrder->setProduct($product);
        $customerOrder->setUser($this->getUser());
        $customerOrder->setCreatedOrderCode();

        $entityManager->persist($customerOrder);
        $entityManager->flush();

        return $this->response($customerOrder);
    }
}
