<?php

namespace Keivan\StripeConnectorBundle;

use Keivan\StripeConnectorBundle\DataTransferObject\StripeCustomer;
use Keivan\StripeConnectorBundle\DataTransferObject\StripePlan;
use Stripe\StripeClient;

class StripeConnector
{
    private string $apiKey;
    private StripeClient $client;
    public function __construct(
        string $apiKey
    ) {
        $this->client = new StripeClient($apiKey);
    }

    public function createCustomer( string $company, string $email ): StripeCustomer
    {
        $customer = $this->client->customers->create([
            'name' => $company,
            'email' => $email,
        ]);

        return StripeCustomer::fromStripeCustomer($customer);
    }

    public function updateCustomer( string $customerId, string $company ): void
    {
        $this->client->customers->update(
            $customerId,
            [
                'name' => $company,
            ]
        );
    }

    public function allCustomers(  ): array
    {
        $customers = $this->client->customers->all();
        $result = [];
        foreach ($customers as $customer) {
            $result[] = StripeCustomer::fromStripeCustomer($customer);
        }
        return $result;
    }

    public function allPlans(  ): array
    {
        $plans = $this->client->plans->all();
        $result = [];
        foreach ($plans as $plan) {
            $product = $this->client->products->retrieve($plan->product);
            $result[] = StripePlan::fromStripePlan($plan, $product);
        }
        return $result;
    }

    public function activePlans(  ): array
    {
        $plans = $this->allPlans();
        return array_filter($plans, fn($plan) => $plan->active);
    }

    public function recurringPlans(  ): array
    {
        $plans = $this->allPlans();
        return array_filter($plans, fn($plan) => $plan->type === StripePlan::TYPE_LICENSED);
    }

    public function recurringActivePlans(  ): array
    {
        $plans = $this->activePlans();
        return array_filter($plans, fn($plan) => $plan->type === StripePlan::TYPE_LICENSED && $plan->active);
    }
}