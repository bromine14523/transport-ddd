<?php

namespace App\Controller;

//use App\Cache\PromotionCache;
//use App\DTO\LowestPriceEnquiry;
//use App\Entity\Promotion;
use App\Entity\Vehicle;
//use App\Filter\PromotionsFilterInterface;
//use App\Repository\ProductRepository;
use App\Service\Serializer\DTOSerializer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class MainController extends AbstractController
{

//    /**
//     * @param ProductRepository $repository
//     * @param EntityManagerInterface $entityManager
//     */
    public function __construct(
//        private ProductRepository $repository,
//        private EntityManagerInterface $entityManager
    )
    {
    }

    #[Route('/index', name: 'index', methods: 'GET')]
    public function index() {
        return new JsonResponse(["data" => "test"]);
    }

    #[Route('/index-two', name: 'index-two', methods: 'POST')]
    public function indexTwo(
        Request $request,
        DTOSerializer $serializer,
    ) {
        $vehicleRecord = $serializer->deserialize($request->getContent(), Vehicle::class, 'json');
        dd($vehicleRecord);

        return new JsonResponse(["data" => "test"]);
    }

//    #[Route('/products/{id}/lowest-price', name: 'lowest-price', methods: 'POST')]
//    public function lowestPrice(
//        Request $request,
//        int $id,
//        DTOSerializer $serializer,
//        PromotionsFilterInterface $promotionsFilter,
//        PromotionCache $promotionCache
//    ): Response
//    {
//
//        /** @var LowestPriceEnquiry $lowestPriceEnquiry */
//        $lowestPriceEnquiry = $serializer->deserialize(
//            $request->getContent(),
//            LowestPriceEnquiry::class,
//            'json'
//        );
//
//        $product = $this->repository->findOrFail($id);
//        $lowestPriceEnquiry->setProduct($product);
//
//        $promotions = $promotionCache->findValidForProduct($product, $lowestPriceEnquiry->getRequestDate());
//
//        $modifiedEnquiry = $promotionsFilter->apply($lowestPriceEnquiry, ...$promotions);
//
//        $responseContent = $serializer->serialize($modifiedEnquiry, 'json');
//
//        return new JsonResponse(data: $responseContent, status: Response::HTTP_OK, json: true);
//    }

//    #[Route('/products/{id}/promotions', name: 'promotions', methods: 'GET')]
//    public function promotions()
//    {
//
//    }

}
