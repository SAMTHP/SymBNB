<?php

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;

class PaginationService 
{
    private $entityClass;
    private $limit = 10;
    private $currentPage = 1;
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    
    public function getData()
    {
        // Calculate offset
        $offset = $this->currentPage * $this->limit - $this->limit;

        // Ask to repository to find elements
        $repo = $this->manager->getRepository($this->entityClass);
        $data = $repo->findBy(
            [],
            [],
            $this->limit,
            $offset
        );
        // Return the elements
        return $data;
    }
    
    public function getPages()
    {
        // Total pages
        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findAll());

        // Defining of total pages
        $total_pages = ceil($total / $this->limit);

        return $total_pages;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
        return $this;
    }

    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * Get the value of currentPage
     */ 
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * Set the value of currentPage
     *
     * @return  self
     */ 
    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;

        return $this;
    }
}
