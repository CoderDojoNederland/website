<?php

namespace CoderDojo\WebsiteBundle\Repository;

use CoderDojo\WebsiteBundle\Entity\Article;
use CoderDojo\WebsiteBundle\Entity\Category;
use CoderDojo\WebsiteBundle\Entity\CocRequest;
use Doctrine\ORM\EntityRepository;

class CocRequestRepository extends EntityRepository
{
    /**
     * @return CocRequest[]
     */
    public function getReadyToExpire()
    {
        return $this->createQueryBuilder('c')
            ->where('c.expiresAt < :now')
            ->andWhere('c.status != :expired')
            ->setParameter('now', new \DateTime)
            ->setParameter('expired', CocRequest::STATUS_EXPIRED)
            ->getQuery()
            ->getResult();
    }
}
