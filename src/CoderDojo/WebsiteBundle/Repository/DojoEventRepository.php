<?php

namespace CoderDojo\WebsiteBundle\Repository;

use CoderDojo\WebsiteBundle\Entity\DojoEvent;
use Doctrine\ORM\EntityRepository;

class DojoEventRepository extends EntityRepository
{
    /**
     * Fetch all upcoming events
     *
     * @param null $max
     * @return DojoEvent[]
     */
    public function getAllUpcomingEvents($max = null)
    {
        $return = $this->createQueryBuilder('e')
            ->join('e.dojo', 'd')
            ->having('e.date >= :today')
            ->setParameter('today', date('Y-m-d'))
            ->orderBy('e.date', 'ASC')
            ->addOrderBy('d.city', 'ASC')
            ->getQuery();

        if ($max) {
            $return->setMaxResults($max);
        }

        return $return->getResult();
    }

    public function getCodeWeek2020Events()
    {
        return $this->createQueryBuilder('e')
            ->where('e.date >= :start_date')
            ->andWhere('e.date <= :end_date')
            ->orderBy('e.date', 'ASC')
            ->setParameter('start_date', new \DateTime('2020-10-10'))
            ->setParameter('end_date', new \DateTime('2020-10-25'))
            ->getQuery()
            ->getResult();
    }

    public function getOnlineEvents()
    {
        $qb = $this->createQueryBuilder('e');

        return $qb
            ->where('e.eventType = :type')
            ->setParameter('type', DojoEvent::TYPE_ONLINE)
            ->orderBy('e.date', 'ASC')
            ->getQuery()
            ->getResult()
        ;

    }

    public function getWVDMWHEvents()
    {
        return $this->createQueryBuilder('e')
                    ->where('e.date >= :start_date')
                    ->andWhere('e.date <= :end_date')
                    ->orderBy('e.date', 'ASC')
                    ->setParameter('start_date', new \DateTime('2019-11-08'))
                    ->setParameter('end_date', new \DateTime('2019-11-15'))
                    ->getQuery()
                    ->getResult();
    }
}
