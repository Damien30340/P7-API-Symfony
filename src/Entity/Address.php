<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AddressRepository::class)
 */
class Address
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="smallint")
     * @Groups({"details"})
     */
    private ?int $number;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"details"})
     */
    private ?string $street;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"details"})
     */
    private ?int $postCode;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"details"})
     */
    private ?string $city;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"details"})
     */
    private ?string $country;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="address")
     */
    private ?User $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getPostCode(): ?int
    {
        return $this->postCode;
    }

    public function setPostCode(int $postCode): self
    {
        $this->postCode = $postCode;

        return $this;
    }

    public function setCity(string $city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string|null
     */

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }


}
