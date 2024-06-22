<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CompoundRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/** @final */
#[ORM\Entity(repositoryClass: CompoundRepository::class)]
class Compound
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)] 
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, unique: true)]
    #[Assert\NotBlank()]
    private ?string $name = null;

    #[ORM\Column(type: Types::STRING, unique: true)]
    #[Assert\NotBlank()]
    private ?string $formula = null;

    #[ORM\Column(type: Types::STRING, length: 5, unique: true)]
    #[Assert\NotBlank()]
    private ?string $crystallineSpaceGroup = null;

    /** @var Collection<int, Element> $element */
    #[ORM\ManyToMany(targetEntity: Element::class, mappedBy: 'compound')]
    private Collection $element;

    public function __construct()
    {
        $this->element = new ArrayCollection();
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

    public function getFormula(): ?string
    {
        return $this->formula;
    }

    public function getCrystallineSpaceGroup(): ?string
    {
        return $this->crystallineSpaceGroup;
    }

    /**
     * @return Collection<int, Element>
     */
    public function getElementEntity(): Collection
    {
        return $this->element;
    }

    public function addElementEntity(Element $element): static
    {
        if (!$this->element->contains($element)) {
            $this->element->add($element);
            $element->addCompound($this);
        }

        return $this;
    }

    public function removeElementEntity(Element $element): static
    {
        if ($this->element->removeElement($element)) {
            $element->removeCompound($this);
        }

        return $this;
    }
}
