<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\PaginatedRepresentation;

class PaginationCollection
{
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function setManager($manager)
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * Get paginated data
     * @return PaginatedRepresentation
     */
    public function getPaginationResult($entityClass, $route, $currentPage, $limit, $params, $order): PaginatedRepresentation
    {
        $offset = ($currentPage - 1) * $limit;

        $repo = $this->manager->getRepository($entityClass);
        $total = count($repo->findBy($params));
        $numberOfPages = ceil($total / $limit);
        $data = $repo->findBy($params, $order, $limit, $offset);

        $collection = new CollectionRepresentation($data);


        return  new PaginatedRepresentation(
            $collection,
            $route,
            array(),
            $currentPage,
            $limit,
            $numberOfPages,
            'page',
            'limit',
            true,
            $total
        );
    }
}