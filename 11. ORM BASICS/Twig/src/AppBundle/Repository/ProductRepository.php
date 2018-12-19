<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    public function findByNameAndPrice()
    {
        //return $this->findBy(['name' => 'Pepsi Cola', 'price' => 4.35]);
        $query = $this
            ->getEntityManager()
            ->createQuery('
                SELECT p FROM AppBundle:Product p
                WHERE p.id = :id
            ')
            ->setParameter('id', 23);

        return $query->getResult();
    }
}