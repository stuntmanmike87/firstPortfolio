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

namespace App\Factory;

use App\Entity\Element;
use App\Repository\ElementRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Element>
 *
 * @method        Element|Proxy                     create(array|callable $attributes = [])
 * @method static Element|Proxy                     createOne(array $attributes = [])
 * @method static Element|Proxy                     find(object|array|mixed $criteria)
 * @method static Element|Proxy                     findOrCreate(array $attributes)
 * @method static Element|Proxy                     first(string $sortedField = 'id')
 * @method static Element|Proxy                     last(string $sortedField = 'id')
 * @method static Element|Proxy                     random(array $attributes = [])
 * @method static Element|Proxy                     randomOrCreate(array $attributes = [])
 * @method static ElementRepository|RepositoryProxy repository()
 * @method static Element[]|Proxy[]                 all()
 * @method static Element[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Element[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Element[]|Proxy[]                 findBy(array $attributes)
 * @method static Element[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Element[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 *
 * @phpstan-method        Proxy<Element> create(array|callable $attributes = [])
 * @phpstan-method static Proxy<Element> createOne(array $attributes = [])
 * @phpstan-method static Proxy<Element> find(object|array|mixed $criteria)
 * @phpstan-method static Proxy<Element> findOrCreate(array $attributes)
 * @phpstan-method static Proxy<Element> first(string $sortedField = 'id')
 * @phpstan-method static Proxy<Element> last(string $sortedField = 'id')
 * @phpstan-method static Proxy<Element> random(array $attributes = [])
 * @phpstan-method static Proxy<Element> randomOrCreate(array $attributes = [])
 * @phpstan-method static RepositoryProxy<Element> repository()
 * @phpstan-method static list<Proxy<Element>> all()
 * @phpstan-method static list<Proxy<Element>> createMany(int $number, array|callable $attributes = [])
 * @phpstan-method static list<Proxy<Element>> createSequence(iterable|callable $sequence)
 * @phpstan-method static list<Proxy<Element>> findBy(array $attributes)
 * @phpstan-method static list<Proxy<Element>> randomRange(int $min, int $max, array $attributes = [])
 * @phpstan-method static list<Proxy<Element>> randomSet(int $number, array $attributes = [])
 */
final class ElementFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]
    protected function getDefaults(): array
    {
        return [
            'ElementicNumber' => self::faker()->randomNumber(),
            'name' => self::faker()->text(255),
            'relativeElementicMass' => self::faker()->randomFloat(),
            'symbol' => self::faker()->text(2),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Element $element): void {})
        ;
    }

    #[\Override]
    protected static function getClass(): string
    {
        return Element::class;
    }
}
