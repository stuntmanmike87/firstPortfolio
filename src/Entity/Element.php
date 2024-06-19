<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ElementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/** @final */
#[UniqueEntity(fields: ['atomicNumber', 'symbol', 'name'], message: 'We have already this element')]
#[ORM\Entity(repositoryClass: ElementRepository::class)]
class Element
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::INTEGER, unique: true)]
    #[Assert\NotBlank()]
    private ?int $atomicNumber = null;

    #[ORM\Column(type: Types::STRING, length: 2, unique: true)]
    #[Assert\NotBlank()]
    private ?string $symbol = null;

    #[ORM\Column(type: Types::STRING, length: 30, unique: true)]
    #[Assert\NotBlank()]
    private ?string $name = null;

    #[ORM\Column(type: Types::FLOAT)]
    #[Assert\NotBlank()]
    private ?float $relativeAtomicMass = null;

    #[ORM\Column(type: Types::STRING, length: 4, unique: true)]
    #[Assert\NotBlank()]
    private ?string $crystallineSpaceGroup = null;

    /** @var Collection<int, Compound> $compound */
    #[ORM\ManyToMany(targetEntity: Compound::class, inversedBy: 'element')]
    private Collection $compound;

    public function __construct()
    {
        $this->compound = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAtomicNumber(): ?int
    {
        return $this->atomicNumber;
    }

    public function setAtomicNumber(int $ElementicNumber): static
    {
        $this->atomicNumber = $ElementicNumber;

        return $this;
    }

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): static
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getRelativeAtomicMass(): ?float
    {
        return $this->relativeAtomicMass;
    }

    public function setRelativeAtomicMass(float $relativeAtomicMass): static
    {
        $this->relativeAtomicMass = $relativeAtomicMass;

        return $this;
    }

    public function getCrystallineSpaceGroup(): ?string
    {
        return $this->crystallineSpaceGroup;
    }

    public function setCrystallineSpaceGroup(string $crystallineSpaceGroup): self
    {
        $this->crystallineSpaceGroup = $crystallineSpaceGroup;

        return $this;
    }

    /**
     * @return Collection<int, Compound>
     */
    public function getCompoundEntity(): Collection
    {
        return $this->compound;
    }

    public function addCompound(Compound $compound): static
    {
        if (!$this->compound->contains($compound)) {
            $this->compound->add($compound);
        }

        return $this;
    }

    public function removeCompound(Compound $compound): static
    {
        $this->compound->removeElement($compound);

        return $this;
    }
}
