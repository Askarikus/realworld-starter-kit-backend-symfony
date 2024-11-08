<?php

declare(strict_types=1);

namespace App\Entity;

use App\Contracts\PasswordHasherInterface;
use App\Dto\User\UserDto;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
#[ORM\HasLifecycleCallbacks]
class User extends AbstractEntity implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Column(type: 'string', unique: true)]
    private string $email;

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $password;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $username;

    #[ORM\Column(type: 'string', length: 2048, nullable: true)]
    private ?string $bio = null;

    #[ORM\Column(type: 'string', length: 1024, nullable: true)]
    private ?string $image = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Article::class)]
    private Collection $articles;

    /**
     * @var string[]
     */
    #[ORM\Column(type: 'json')]
    private array $roles = [];

    public function getName(): ?string
    {
        return $this->username;
    }

    public function setName(?string $username): void
    {
        $this->username = $username;
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

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(string $bio): void
    {
        $this->bio = $bio;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param string[] $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getJson(): array
    {
        return UserDto::fromModel($this)->jsonSerialize();
    }
}
