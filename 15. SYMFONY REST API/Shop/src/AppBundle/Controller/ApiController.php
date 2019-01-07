<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Form\ProductType;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ApiController
 * @Route("/api")
 * @package AppBundle\Controller
 */
class ApiController extends Controller
{
    /**
     * @Route("/products", methods={"GET"})
     */
    public function getProducts(): Response
    {
        $products = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findAll();

        return $this->response($products);
    }

    /**
     * @Route("/products", methods={"POST"})
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function createProduct(Request $request): Response
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        return $this->response($product);
    }

    /**
     * @Route("/products/{id}", methods={"GET"})
     * @param int $id
     * @return Response
     */
    public function getProduct(int $id): Response
    {
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        return $this->response($product);
    }

    /**
     * @Route("/products/{id}", methods={"PUT"})
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function updateProduct(Request $request, int $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->find(Product::class, $id);

        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        $em->persist($product);
        $em->flush();

        return $this->response($product);
    }

    /**
     * @Route("/products/{id}", methods={"DELETE"})
     * @param int $id
     * @return Response
     */
    public function deleteProduct(int $id): Response
    {
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        return $this->response($id);
    }

    /**
     * @Route("/countries", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function getCountries(Request $request): Response
    {
        /** @var Connection $db */
        $db = $this->getDoctrine()->getConnection();
        $term = $request->get('term');
        $sql = '
            SELECT country_name FROM geography.countries
            WHERE country_name LIKE :term
            LIMIT 20;
        ';
        $countries = $db->fetchAll($sql, ['term' => $term . '%']);

        return $this->response($countries);
    }

    private function response($data): Response
    {
        $serializer = $this->get('jms_serializer');

        return new Response($serializer->serialize($data, 'json'), 200,
            ['content-type' => 'application/json']);
    }
}
