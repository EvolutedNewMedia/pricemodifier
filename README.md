#PriceModifier

> An extendable, framework agnostic price modification package, ideal for handling complex discount types in e-commerce applications.

---

##Overview
The aim of this plugin is to provide a reusable way of handling discounts on e-commerce systems. It does this by using discount modifiers, which individually handle your different types of discounts (e.g a percentage off), and discount restrictions, which allow you to use your own database to do things like setting a discount code to only be valid with certain products, under certain conditions.

Using your own discount restriction handler, this allows you to create complex discount options such as:

- Buy 2, get 1 free
- Buy 1, get second half price
- Get product B free when you buy product A

Because of how the basket object is managed, it makes it possible to not only add discounts onto a complete basket, but you can also add new basket items either via your discount restrictions, or discount price modifiers.

##Installation

This project can be installed via [Composer](https://getcomposer.org):

``` bash
$ composer require evoluted/pricemodifer