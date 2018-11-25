<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    private function getData(): array
    {
        return [
            0 => ['firstName' => 'Peter', 'lastName' => 'Adams'],
            1 => ['firstName' => 'George', 'lastName' => 'Lucas'],
            2 => ['firstName' => 'Maria', 'lastName' => 'Proud'],
        ];
    }

    /**
     * @Route("/users", methods={"GET"})
     * @return JsonResponse
     */
    public function getList(): JsonResponse
    {
        $data = $this->getData();

        return $this->json($data);
    }

    /**
     * @Route("/users/{id}", methods={"GET"})
     * @param $id
     * @return JsonResponse
     */
    public function getOne($id): JsonResponse
    {
        $data = $this->getData();

        return $this->json($data[$id]);
    }

    /**
     * @Route("/users", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $newData = $request->request->get('post_param');
        $data = $this->getData();
        $newId = \count($data);
        $data[$newId] = $newData;

        return $this->json($newId);
    }

    /**
     * @Route("/users/{id}", methods={"PUT"})
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function edit(int $id, Request $request): JsonResponse
    {
        $newData = json_decode($request->getContent());
        $data = $this->getData();
        $data[$id] = $newData;

        return $this->json($data);
    }

    /**
     * @Route("/users/{id}", methods={"DELETE"})
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $data = $this->getData();
        unset($data[$id]);

        return $this->json($data);
    }
}
