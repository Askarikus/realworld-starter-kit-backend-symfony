<?php

declare(strict_types=1);

namespace App\Entity;

use App\Dto\User\UserDto;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use App\Contracts\PasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
class User extends AbstractEntity implements PasswordAuthenticatedUserInterface
{

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: 'string', unique: true)]
    private string $email;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $password = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password, PasswordHasherInterface $passwordHasher): void
    {
        if (null === $password) {
            return;
        }

        $this->password = $passwordHasher->hash($this, $password);
    }

    public function getJson(): array
    {
        return UserDto::fromModel($this)->jsonSerialize();
    }

}
