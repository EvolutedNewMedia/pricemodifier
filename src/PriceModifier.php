<?php
namespace Evoluted\PriceModifier;

use Evoluted\PriceModifier\Interfaces\BasketInterface;

/**
 * The PriceModifier package providers a way of making advanced pricing alterations
 * for e-commerce applications.
 *
 * This typically would be used as part of a Discount system to handle anything
 * from a simple percent-off style discount, to more complex discounts such as
 * buy 2, get 1 free.
 *
 * This package provides the ability to load in your own custom discount modifiers
 * as well as your own discount rule handler, from which you can check the items
 * in your basket and then allow/deny the use of different loaded discount
 * modifiers.
 *
 * @package 	PriceModifier
 * @author 		Rick Mills <rick@evoluted.net>
 * @author		Evoluted New Media <developers@evoluted.net>
 * @license     http://mit-license.org/
 *
 * @link		https://github.com/evolutednewmedia/pricemodifier
 *
 */
class PriceModifier
{
    /**
     * @var Evoluted\PriceModifier\Interfaces\BasketInterface
     */
    protected $basket;

    /**
     * Stores the name of the discount restriction handler. Defaults to a basic
     * handler with no options.
     *
     * @var Evoluted\PriceModifier\Interfaces\DiscountRestrictionsInterface
     */
    protected $discountRestrictionsHandler = 'Evoluted\PriceModifier\DiscountRestrictions\Basic';

    /**
     * Stores the discount modifier classes and their alias used to reference them.
     *
     * @var Evoluted\PriceModifier\Interfaces\DiscountModifierInterface
     */
    protected $discountModifiers = [
        'percentage' => 'Evoluted\PriceModifier\DiscountModifiers\PercentageDiscountModifier',
        'fixed' => 'Evoluted\PriceModifier\DiscountModifiers\FixedDiscountModifier'
    ];

    /**
     * Consruct the price modifier
     *
     * @param BasketInterface $basket specifies the basket class to use
     */
    public function __construct(BasketInterface $basket = null)
    {
        $this->setBasket($basket);
    }

    /**
     * Set the basket interface, generally used during initialisation and then
     * after a basked is modified.
     *
     * @param BasketInterface $basket Basket class
     * @return $this
     */
    public function setBasket(BasketInterface $basket)
    {
        $this->basket = $basket;
        return $this;
    }

    /**
     * Return the basket class
     *
     * @return BasketInterface Basket class
     */
    public function basket()
    {
        return $this->basket;
    }

    /**
     * Return all discount modifiers, useful for checking what ones are currently
     * available for use.
     *
     * @return array Discount Modifiers array (alias => class)
     */
    public function getDiscountModifiers()
    {
        return $this->discountModifiers;
    }

    /**
     * Load and return a discount modifier based on its alias
     *
     * @param  string $alias modifier alias
     * @return DiscountModifierInterface Returns the discount modifier class or false if none found
     */
    public function getDiscountModifier($alias)
    {
        if (array_key_exists($alias, $this->discountModifiers)) {
            return new $this->discountModifiers[$alias]();
        }

        return false;
    }

    /**
     * Add a new discount modifier. Generally used when you want to add a custom
     * modifier for your project.
     *
     * @param string $alias alias of the discount modifier to use
     * @param string $class namespaced path to the pre-loaded discount modifier class you are adding
     * @return void
     */
    public function addDiscountModifier($alias, $class)
    {
        $this->discountModifiers[$alias] = $class;
    }

    /**
     * Add a new discount rule
     *
     * @param array $discountRule Rule to add to the basket
     * @return void
     */
    public function addDiscountRule($discountRule)
    {
        $this->basket()->discountRules[] = $discountRule;
    }

    /**
     * Apply all discounts to the basket
     *
     * @return BasketInterface Modified basket object
     */
    public function applyDiscounts()
    {
        $this->applyDiscountRestrictions();

        if (empty($this->basket()->discountRules)) {
            return $this->basket();
        }

        foreach ($this->basket()->discountRules as $discountRule) {
            if (array_key_exists($discountRule['id'], $this->basket()->validDiscounts)) {
                $class = $this->discountModifiers[$discountRule['discountType']];
                $discountModifier = new $class($discountRule['id'], $discountRule, $this->basket());

                $this->setBasket($discountModifier->applyDiscount());
            }
        }

        return $this->basket();
    }

    /**
     * Get the total discounted from the basket
     *
     * @return decimal Discount Total
     */
    public function getDiscountTotal()
    {
        return $this->basket()->getDiscountTotal();
    }

    /**
     * Set the discount restriction handler. Ideal if you want to connect to your
     * own discount restrictions database to handle discount rules.
     *
     * @param string $handler Namespaced path to your custom handler
     * @return void
     */
    public function setDiscountRestrictionHandler($handler)
    {
        $this->discountRestrictionsHandler = $handler;
    }

    /**
     * Apply all loaded discount restrictions to your basket.
     *
     * @return BasketInterface Returns the modified basket object
     */
    public function applyDiscountRestrictions()
    {
        $restrictionsHandler = new $this->discountRestrictionsHandler($this->basket());
        $restrictionsHandler->setRestrictions();

        $this->setBasket($restrictionsHandler->basket());
    }
}
