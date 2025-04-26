<?php

namespace App\Controller;

use App\Entity\Role;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/roles')]
class RoleController extends AbstractController
{
    #[Route('', name: 'api_roles_list', methods: ['GET'])]
    public function list(RoleRepository $roleRepository): JsonResponse
    {
        $roles = $roleRepository->findAll();
        $data = [];

        foreach ($roles as $role) {
            $data[] = [
                'id' => $role->getId(),
                'nombre' => $role->getNombre(),
                'slug' => $role->getSlug(),
            ];
        }

        return $this->json($data);
    }

    #[Route('', name: 'api_roles_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $payload = json_decode($request->getContent(), true);

        $role = new Role();
        $role->setNombre($payload['nombre'] ?? '');
        $role->setSlug($payload['slug'] ?? '');

        $em->persist($role);
        $em->flush();

        return $this->json(['message' => 'Rol creado con éxito'], 201);
    }

    #[Route('/{id}', name: 'api_roles_update', methods: ['PUT'])]
    public function update(string $id, Request $request, RoleRepository $roleRepository, EntityManagerInterface $em): JsonResponse
    {
        $role = $roleRepository->find($id);

        if (!$role) {
            return $this->json(['error' => 'Rol no encontrado'], 404);
        }

        $payload = json_decode($request->getContent(), true);

        if (isset($payload['nombre'])) {
            $role->setNombre($payload['nombre']);
        }
        if (isset($payload['slug'])) {
            $role->setSlug($payload['slug']);
        }

        $em->flush();

        return $this->json(['message' => 'Rol actualizado con éxito']);
    }

    #[Route('/{id}', name: 'api_roles_delete', methods: ['DELETE'])]
    public function delete(string $id, RoleRepository $roleRepository, EntityManagerInterface $em): JsonResponse
    {
        $role = $roleRepository->find($id);

        if (!$role) {
            return $this->json(['error' => 'Rol no encontrado'], 404);
        }

        $em->remove($role);
        $em->flush();

        return $this->json(['message' => 'Rol eliminado con éxito']);
    }
}
