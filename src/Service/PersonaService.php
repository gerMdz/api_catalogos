<?php

namespace App\Service;

use Doctrine\DBAL\Connection;

class PersonaService
{
    private Connection $connection;

    public function __construct(Connection $default)
    {
        $this->connection = $default;
    }

    public function obtenerPersonas(): array
    {
        $sql = "SELECT id, apellido, nombre, documento FROM personas LIMIT 10";
        return $this->connection->fetchAllAssociative($sql);
    }
}
