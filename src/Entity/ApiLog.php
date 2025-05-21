<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ApiLogRepository;


#[ORM\Table(name: 'api_logs')]
#[ORM\Entity(repositoryClass: ApiLogRepository::class)]
class ApiLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $loggedAt;

    #[ORM\Column(type: 'string', length: 50)]
    private string $type;

    #[ORM\Column(type: 'text')]
    private string $message;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $context = null;

    public function __construct(string $type, string $message, ?array $context = null)
    {
        $this->loggedAt = new \DateTimeImmutable();
        $this->type = $type;
        $this->message = $message;
        $this->context = $context;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLoggedAt(): \DateTimeInterface
    {
        return $this->loggedAt;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getContext(): ?array
    {
        return $this->context;
    }
}
