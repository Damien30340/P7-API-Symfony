<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @Hateoas\Relation(
 *     name = "self",
 *     href = @Hateoas\Route(
 *         "user_show",
 *         parameters = { "id" = "expr(object.getId())" },
 *         absolute = true,
 *     ),
 *     attributes={"actions":{"show": "GET"}},
 *     exclusion = @Hateoas\Exclusion(groups = {"details"})
 * )
 * * @Hateoas\Relation(
 *     name = "create",
 *     href = @Hateoas\Route(
 *         "user_create",
 *         absolute = true,
 *     ),
 *     attributes={"actions":{"create": "POST"}},
 *     exclusion = @Hateoas\Exclusion(groups = {"details"})
 * )
 * @Hateoas\Relation(
 *     name = "update",
 *     href = @Hateoas\Route(
 *         "user_update",
 *         parameters = { "id" = "expr(object.getId())" },
 *         absolute = true,
 *     ),
 *     attributes={"actions":{"update": "PUT"}},
 *     exclusion = @Hateoas\Exclusion(groups = {"details"})
 * )
 * * @Hateoas\Relation(
 *     name = "delete",
 *     href = @Hateoas\Route(
 *         "user_delete",
 *         parameters = { "id" = "expr(object.getId())" },
 *         absolute = true,
 *     ),
 *     attributes={"actions":{"delete": "DELETE"}},
 *     exclusion = @Hateoas\Exclusion(groups = {"details"})
 * )
 */
class User extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"list", "details"})
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"list", "details"})
     */
    private ?string $nickName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"details"})
     */
    private ?string $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"details"})
     */
    private ?string $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list", "details"})
     */
    private ?string $mail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"details"})
     */
    private ?string $phoneNumber;

    /**
     * @ORM\OneToMany(targetEntity=Address::class, mappedBy="user", cascade={"persist", "remove"})
     * @var Collection<int, Address>
     * @Groups({"details"})
     */
    private $address;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="users", cascade={"persist"})
     */
    private ?Client $client;

    public function __construct()
    {
        $this->address = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNickName(): ?string
    {
        return $this->nickName;
    }

    public function setNickName(?string $nickName): self
    {
        $this->nickName = $nickName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

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

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * @return Collection|Address[]
     */
    public function getAddress(): Collection
    {
        return $this->address;
    }

    public function addAddress(Address $address): self
    {
        if (!$this->address->contains($address)) {
            $this->address[] = $address;
            $address->setUser($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        if ($this->address->removeElement($address)) {
            // set the owning side to null (unless already changed)
            if ($address->getUser() === $this) {
                $address->setUser(null);
            }
        }

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }
}
