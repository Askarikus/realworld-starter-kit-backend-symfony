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
#[ORM\HasLifecycleCallbacks]
class User extends AbstractEntity implements PasswordAuthenticatedUserInterface
{

    #[ORM\Column(type: 'string', unique: true)]
    private string $email;

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $password;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $name;

    #[ORM\Column(type: 'string', length: 2048, nullable: true)]
    private ?string $bio = null;

    #[ORM\Column(type: 'string', length: 1024, nullable: true)]
    private ?string $image = null;

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

    public function getBio(): string
    {
        return $this->bio;
    }

    public function setBio(string $bio): void
    {
        $this->bio = $bio;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    public function getJson(): array
    {
        return UserDto::fromModel($this)->jsonSerialize();
    }

}
