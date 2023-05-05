<?php

namespace Keivan\StripeConnectorBundle;

use Keivan\StripeConnectorBundle\DataTransferObject\StripeCustomer;
use Keivan\StripeConnectorBundle\DataTransferObject\StripePlan;
use Stripe\Exception\ApiErrorException;
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

    /**
     * Create a new customer
     *
     * @param string $company
     * @param string $email
     * @return StripeCustomer
     * @throws ApiErrorException
     */
    public function createCustomer( string $company, string $email ): StripeCustomer
    {
        $customer = $this->client->customers->create([
            'name' => $company,
            'email' => $email,
        ]);

        return StripeCustomer::fromStripeCustomer($customer);
    }

    /**
     * Update a customer
     *
     * @param string $customerId
     * @param string $company
     * @return void
     * @throws ApiErrorException
     */
    public function updateCustomer( string $customerId, string $company ): void
    {
        $this->client->customers->update(
            $customerId,
            [
                'name' => $company,
            ]
        );
    }

    /**
     * Get all customers
     *
     * @return array<StripeCustomer>
     * @throws ApiErrorException
     */
    public function allCustomers(  ): array
    {
        $customers = $this->client->customers->all();
        $result = [];
        foreach ($customers as $customer) {
            $result[] = StripeCustomer::fromStripeCustomer($customer);
        }
        return $result;
    }

    /**
     * Get all plans
     *
     * @return array<StripePlan>
     * @throws ApiErrorException
     */
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

    /**
     * Get all active plans
     *
     * @return array<StripePlan>
     * @throws ApiErrorException
     */
    public function activePlans(  ): array
    {
        $plans = $this->allPlans();
        return array_filter($plans, fn($plan) => $plan->active);
    }

    /**
     * Get all recurring plans
     *
     * @return array<StripePlan>
     * @throws ApiErrorException
     */
    public function recurringPlans(  ): array
    {
        $plans = $this->allPlans();
        return array_filter($plans, fn($plan) => $plan->type === StripePlan::TYPE_LICENSED);
    }

    /**
     * Get all active recurring plans
     *
     * @return array<StripePlan>
     * @throws ApiErrorException
     */
    public function recurringActivePlans(  ): array
    {
        $plans = $this->activePlans();
        return array_filter($plans, fn($plan) => $plan->type === StripePlan::TYPE_LICENSED && $plan->active);
    }
}