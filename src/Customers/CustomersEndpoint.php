<?php

declare(strict_types=1);

namespace HelpScout\Api\Customers;

use HelpScout\Api\Endpoint;
use HelpScout\Api\Entity\PagedCollection;
use HelpScout\Api\Http\Hal\HalPagedResources;
use HelpScout\Api\Http\Hal\HalResource;

class CustomersEndpoint extends Endpoint
{
    /**
     * @param Customer $customer
     *
     * @return int|null
     */
    public function create(Customer $customer): ?int
    {
        return $this->restClient->createResource(
            $customer,
            '/v2/customers'
        );
    }

    /**
     * @param Customer $customer
     */
    public function update(Customer $customer): void
    {
        $this->restClient->updateResource(
            $customer,
            sprintf('/v2/customers/%d', $customer->getId())
        );
    }

    /**
     * @param int                  $id
     * @param CustomerRequest|null $customerRequest
     *
     * @return Customer
     */
    public function get(int $id, CustomerRequest $customerRequest = null): Customer
    {
        $customerResource = $this->restClient->getResource(
            Customer::class,
            sprintf('/v2/customers/%d', $id)
        );

        return $this->hydrateCustomerWithSubEntities(
            $customerResource,
            $customerRequest ?: new CustomerRequest()
        );
    }

    /**
     * @param CustomerFilters|null $customerFilters
     * @param CustomerRequest|null $customerRequest
     *
     * @return Customer[]|PagedCollection
     */
    public function list(
        CustomerFilters $customerFilters = null,
        CustomerRequest $customerRequest = null
    ): PagedCollection {
        $uri = '/v2/customers';
        if ($customerFilters) {
            $params = $customerFilters->getParams();
            if (!empty($params)) {
                $uri .= '?'.http_build_query($params);
            }
        }

        return $this->loadCustomers(
            $uri,
            $customerRequest ?: new CustomerRequest()
        );
    }

    /**
     * @param string          $uri
     * @param CustomerRequest $customerRequest
     *
     * @return Customer[]|PagedCollection
     */
    private function loadCustomers(
        string $uri,
        CustomerRequest $customerRequest
    ): PagedCollection {
        /** @var HalPagedResources $customerResources */
        $customerResources = $this->restClient->getResources(
            Customer::class,
            'customers',
            $uri
        );
        $customers = $customerResources->map(
            function (HalResource $customerResource) use ($customerRequest) {
                return $this->hydrateCustomerWithSubEntities($customerResource, $customerRequest);
            }
        );

        return new PagedCollection(
            $customers,
            $customerResources->getPageMetadata(),
            $customerResources->getLinks(),
            function (string $uri) use ($customerRequest) {
                return $this->loadCustomers($uri, $customerRequest);
            }
        );
    }

    /**
     * @param HalResource     $customerResource
     * @param CustomerRequest $customerRequest
     *
     * @return Customer
     */
    private function hydrateCustomerWithSubEntities(
        HalResource $customerResource,
        CustomerRequest $customerRequest
    ): Customer {
        $customerLoader = new CustomerLoader(
            $this->restClient,
            $customerResource,
            $customerRequest->getLinks()
        );
        $customerLoader->load();

        return $customerResource->getEntity();
    }
}
