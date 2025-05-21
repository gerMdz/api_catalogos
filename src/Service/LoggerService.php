<?php

namespace App\Service;

use App\Entity\ApiLog;
use Doctrine\ORM\EntityManagerInterface;

class LoggerService
{

    private const VALID_TYPES = ['error', 'warning', 'data_corruption'];

    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function log(string $type, string $message, ?array $context = null): void
    {
        if (!in_array($type, self::VALID_TYPES, true)) {
            throw new \InvalidArgumentException("Tipo de log inválido: '$type'. Valores permitidos: " . implode(', ', self::VALID_TYPES));
        }

        $log = new ApiLog($type, $message, $context);
        $this->em->persist($log);
        $this->em->flush();
    }

}
