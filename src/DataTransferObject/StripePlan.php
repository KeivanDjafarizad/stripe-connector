<?php

namespace Keivan\StripeConnectorBundle\DataTransferObject;

use Stripe\Plan;
use Stripe\Product;

final class StripePlan
{
    const TYPE_METERED = 'metered';
    const TYPE_LICENSED = 'licensed';

    public function __construct(
        public readonly string $id,
        public readonly string $currency,
        public readonly string $productId,
        public readonly string $productName,
        public readonly string $productDescription,
        public readonly string $type,
        public readonly bool $active,
        public readonly float $unitAmount
    ) { }

    public static function fromStripePlan( Plan $plan, Product $product ): self
    {
        return new self(
            id: $plan->id,
            currency: $plan->currency,
            productId: $plan->product,
            productName: $product->name,
            productDescription: $product->description,
            type: $plan->usage_type,
            active: $plan->active,
            unitAmount: $plan->amount ?? 0,
        );
    }
}