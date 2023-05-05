<?php

namespace Keivan\StripeConnectorBundle\DataTransferObject;

use Stripe\Customer;

final class StripeCustomer
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string|null $id = null,
    ) { }

    public static function fromStripeCustomer( Customer $customer ): self
    {
        return new self(
            name: $customer->name,
            email: $customer->email,
            id: $customer->id,
        );
    }
}