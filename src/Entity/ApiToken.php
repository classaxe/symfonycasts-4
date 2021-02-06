<?php

namespace App\Entity;

use App\Repository\ApiTokenRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ApiTokenRepository::class)
 */
class ApiToken
{
    // Since all properties are now set in the constructor, and all the setters have been deleted,
    // this class is now Immutable:
    // Once instantiated, this object's data can never be changed
    // unless setters are specifically added to manipulate the data, e.g. extend expiry by one hour.

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expiresAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="apiTokens")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct(User $user)
    {
        $this->token = bin2hex(random_bytes(60));
        $this->user = $user;
        $this->expiresAt = new DateTime('+1 hour');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function getExpiresAt(): ? DateTimeInterface
    {
        return $this->expiresAt;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function isExpired(): bool
    {
        return $this->getExpiresAt() <= new DateTime();
    }

}
