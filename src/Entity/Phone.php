<?php

namespace App\Entity;

use App\Repository\PhoneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PhoneRepository::class)
 * @Hateoas\Relation(
 *     name = "self",
 *     href = @Hateoas\Route(
 *         "phone_show",
 *         parameters = { "id" = "expr(object.getId())" },
 *         absolute = true,
 *     ),
 *     attributes={"actions":{"show": "GET"}},
 *     exclusion = @Hateoas\Exclusion(groups = {"details", "list"})
 * )
 */
class Phone extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list", "details"})
     */
    private ?string $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"details"})
     */
    private ?string $description;

    /**
     * @ORM\Column(type="float")
     * @Groups({"list", "details"})
     */
    private ?float $price;

    /**
     * @ORM\OneToMany(targetEntity=Picture::class, mappedBy="phone", cascade={"persist", "remove"})
     * @Groups({"list", "details"})
     * @var Collection<int, Picture>
     */
    private $picture;

    /**
     * @ORM\ManyToOne(targetEntity=Brand::class, inversedBy="phones", cascade={"persist", "remove"})
     * @Groups({"list", "details"})
     */
    private $brand;

    public function __construct()
    {
        $this->picture = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection|Picture[]
     */
    public function getPicture(): Collection
    {
        return $this->picture;
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->picture->contains($picture)) {
            $this->picture[] = $picture;
            $picture->setPhone($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->picture->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getPhone() === $this) {
                $picture->setPhone(null);
            }
        }

        return $this;
    }

    public function getBrand()
    {
        return $this->brand;
    }

    public function setBrand($brand): self
    {
        $this->brand = $brand;

        return $this;
    }
}
