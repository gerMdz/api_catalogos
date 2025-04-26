<?php

namespace App\Controller;

use App\Entity\UsuarioPanel;
use App\Entity\Role;
use App\Repository\UsuarioPanelRepository;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/usuarios')]
class UsuarioPanelController extends AbstractController
{
    #[Route('', name: 'api_usuarios_list', methods: ['GET'])]
    public function list(UsuarioPanelRepository $usuarioRepository): JsonResponse
    {
        $usuarios = $usuarioRepository->findAll();
        $data = [];

        foreach ($usuarios as $usuario) {
            $data[] = [
                'id' => $usuario->getId(),
                'email' => $usuario->getEmail(),
                'nombre' => $usuario->getNombre(),
                'roles' => array_map(fn($role) => $role->getSlug(), $usuario->getRoleEntities()->toArray()),
            ];
        }

        return $this->json($data);
    }

    #[Route('', name: 'api_usuarios_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, RoleRepository $roleRepository, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $payload = json_decode($request->getContent(), true);

        if (empty($payload['email']) || empty($payload['password']) || empty($payload['roles'])) {
            return $this->json(['error' => 'Email, password y roles son obligatorios.'], 400);
        }

        $usuario = new UsuarioPanel();
        $usuario->setEmail($payload['email']);
        $usuario->setPassword($passwordHasher->hashPassword($usuario, $payload['password']));

        foreach ($payload['roles'] as $roleId) {
            $role = $roleRepository->find($roleId);
            if ($role) {
                $usuario->addRole($role);
            }
        }

        $em->persist($usuario);
        $em->flush();

        return $this->json(['message' => 'Usuario creado con éxito'], 201);
    }


    #[Route('/{id}', name: 'api_usuarios_update', methods: ['PUT'])]
    public function update(string $id, Request $request, UsuarioPanelRepository $usuarioRepository,
                           RoleRepository $roleRepository, EntityManagerInterface $em,
                           UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $usuario = $usuarioRepository->find($id);

        if (!$usuario) {
            return $this->json(['error' => 'Usuario no encontrado.'], 404);
        }

        $payload = json_decode($request->getContent(), true);

        if (empty($payload['email']) || empty($payload['roles']) || empty($payload['nombre'])) {
            return $this->json(['error' => 'Nombre, email y roles son obligatorios.'], 400);
        }

        $usuario->setEmail($payload['email']);
        $usuario->setNombre($payload['nombre']);

        if (!empty($payload['password'])) {
            $usuario->setPassword($passwordHasher->hashPassword($usuario, $payload['password']));
        }

        // Limpiar roles actuales
        $usuario->getRoleEntities()->clear();

        foreach ($payload['roles'] as $roleId) {
            $role = $roleRepository->find($roleId);
            if ($role) {
                $usuario->addRole($role);
            }
        }

        $em->flush();

        return $this->json(['message' => 'Usuario actualizado con éxito']);
    }


    #[Route('/{id}', name: 'api_usuarios_delete', methods: ['DELETE'])]
    public function delete(string $id, UsuarioPanelRepository $usuarioRepository, EntityManagerInterface $em): JsonResponse
    {
        $usuario = $usuarioRepository->find($id);

        if (!$usuario) {
            return $this->json(['error' => 'Usuario no encontrado'], 404);
        }

        $em->remove($usuario);
        $em->flush();

        return $this->json(['message' => 'Usuario eliminado con éxito']);
    }
}
