<?php

declare(strict_types=1);

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use App\Repository\ElementRepository;
use Doctrine\ORM\Mapping as ORM;

/** @final */
#[ORM\Entity(repositoryClass: ElementRepository::class)]
class Element
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $ElementicNumber = null;

    #[ORM\Column(length: 2)]
    private ?string $symbol = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $relativeElementicMass = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getElementicNumber(): ?int
    {
        return $this->ElementicNumber;
    }

    public function setElementicNumber(int $ElementicNumber): static
    {
        $this->ElementicNumber = $ElementicNumber;

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

    public function getRelativeElementicMass(): ?float
    {
        return $this->relativeElementicMass;
    }

    public function setRelativeElementicMass(float $relativeElementicMass): static
    {
        $this->relativeElementicMass = $relativeElementicMass;

        return $this;
    }
}
