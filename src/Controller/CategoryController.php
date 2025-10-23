<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/categories')]
class CategoryController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function list(Request $request, CategoryRepository $repository): JsonResponse
    {
        $nombre = $request->query->get('nombre');
        $descripcion = $request->query->get('descripcion');
        $activoParam = $request->query->get('activo');

        $qb = $repository->createQueryBuilder('c');

        if ($nombre !== null && $nombre !== '') {
            $qb->andWhere('LOWER(c.nombre) LIKE :nombre')
               ->setParameter('nombre', '%' . mb_strtolower($nombre, 'UTF-8') . '%');
        }

        if ($descripcion !== null && $descripcion !== '') {
            $qb->andWhere('LOWER(c.descripcion) LIKE :descripcion')
               ->setParameter('descripcion', '%' . mb_strtolower($descripcion, 'UTF-8') . '%');
        }

        if ($activoParam !== null && $activoParam !== '') {
            $val = null;
            $s = strtolower((string)$activoParam);
            if ($s === '1' || $s === 'true' || $s === 't' || $s === 'yes' || $s === 'y') {
                $val = true;
            } elseif ($s === '0' || $s === 'false' || $s === 'f' || $s === 'no' || $s === 'n') {
                $val = false;
            }
            if ($val !== null) {
                $qb->andWhere('c.activo = :activo')->setParameter('activo', $val);
            }
        }

        $qb->orderBy('c.nombre', 'ASC');

        $categories = $qb->getQuery()->getResult();

        $data = array_map(function (Category $c) {
            return [
                'id' => (string) $c->getId(),
                'nombre' => $c->getNombre(),
                'descripcion' => $c->getDescripcion(),
                'identificador' => $c->getIdentificador(),
                'activo' => $c->isActivo(),
            ];
        }, $categories);

        return new JsonResponse($data);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(string $id, CategoryRepository $repository): JsonResponse
    {
        $category = $repository->find($id);
        if (!$category) {
            return new JsonResponse(['error' => 'Categoría no encontrada.'], 404);
        }

        return new JsonResponse([
            'id' => (string) $category->getId(),
            'nombre' => $category->getNombre(),
            'descripcion' => $category->getDescripcion(),
            'identificador' => $category->getIdentificador(),
            'activo' => $category->isActivo(),
        ]);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true) ?? [];

        $category = new Category();
        if (array_key_exists('nombre', $data)) {
            $category->setNombre((string) $data['nombre']);
        }
        if (array_key_exists('descripcion', $data)) {
            $category->setDescripcion((string) $data['descripcion']);
        }
        if (array_key_exists('identificador', $data)) {
            $category->setIdentificador($data['identificador'] !== null ? (string) $data['identificador'] : null);
        }
        if (array_key_exists('activo', $data)) {
            $category->setActivo((bool) $data['activo']);
        }

        // Validaciones simples
        if (!$category->getNombre() || !$category->getDescripcion()) {
            return new JsonResponse(['error' => 'Los campos nombre y descripcion son obligatorios.'], 400);
        }

        $em->persist($category);
        $em->flush();

        return new JsonResponse([
            'message' => 'Categoría creada.',
            'id' => (string) $category->getId(),
            'identificador' => $category->getIdentificador(),
        ], 201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(string $id, Request $request, EntityManagerInterface $em, CategoryRepository $repository): JsonResponse
    {
        $category = $repository->find($id);
        if (!$category) {
            return new JsonResponse(['error' => 'Categoría no encontrada.'], 404);
        }

        $data = json_decode($request->getContent(), true) ?? [];

        if (array_key_exists('nombre', $data)) {
            $category->setNombre((string) $data['nombre']);
        }
        if (array_key_exists('descripcion', $data)) {
            $category->setDescripcion((string) $data['descripcion']);
        }
        if (array_key_exists('identificador', $data)) {
            $category->setIdentificador($data['identificador'] !== null ? (string) $data['identificador'] : null);
        }
        if (array_key_exists('activo', $data)) {
            $category->setActivo((bool) $data['activo']);
        }

        $em->flush();

        return new JsonResponse(['message' => 'Categoría actualizada.']);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(string $id, EntityManagerInterface $em, CategoryRepository $repository): JsonResponse
    {
        $category = $repository->find($id);
        if (!$category) {
            return new JsonResponse(['error' => 'Categoría no encontrada.'], 404);
        }

        $em->remove($category);
        $em->flush();

        return new JsonResponse(['message' => 'Categoría eliminada.']);
    }
}
