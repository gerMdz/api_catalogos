<?php

namespace App\Service;

use App\Entity\ApiLog;
use Doctrine\ORM\EntityManagerInterface;

class LoggerService
{


    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function log(string $type, string $message, ?array $context = null): void
    {


        $log = new ApiLog($type, $message, $context);
        $this->em->persist($log);
        $this->em->flush();
    }

}
