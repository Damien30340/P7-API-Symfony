<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use OpenApi\Annotations as OA;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 * @method string getUserIdentifier()
 */
class Client implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Exclude()
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @OA\Property(description="The name of connexion")
     * @Groups({"register"})
     */
    private ?string $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"register"})
     * @Exclude()
     */
    private ?string $mail;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"register"})
     */
    private ?string $password;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"register"})
     * @Exclude()
     */
    private ?int $siren;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"register"})
     * @Exclude()
     */
    private ?string $phoneNumber;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="client", cascade={"persist", "remove"})
     * @var Collection<int, User>
     * @Exclude()
     */
    private $users;

    /**
     * @ORM\Column(type="json")
     * @Exclude()
     */
    private ?array $roles = [];

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSiren(): ?int
    {
        return $this->siren;
    }

    public function setSiren(?int $siren): self
    {
        $this->siren = $siren;

        return $this;
    }

    public function getPhoneNumber(): ?int
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setClient($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getClient() === $this) {
                $user->setClient(null);
            }
        }

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function getSalt()
    {
    }

    public function eraseCredentials()
    {
    }

    public function __call(string $name, array $arguments)
    {
    }
}
