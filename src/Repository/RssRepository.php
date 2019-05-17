<?php

namespace App\Repository;

use App\Entity\Rss;
use App\Service\ExternalApiService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Rss|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rss|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rss[]    findAll()
 * @method Rss[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RssRepository extends ServiceEntityRepository
{
    const STATUS_PUBLISHED = 'published';
    const STATUS_QUEUED = 'queued';

    const MAX_RESULTS_DEFAULT = 20;

    /**
     * @var ExternalApiService
     */
    private $apiService;

    /**
     * RssRepository constructor.
     * @param RegistryInterface $registry
     * @param ExternalApiService $apiService
     */
    public function __construct(RegistryInterface $registry, ExternalApiService $apiService)
    {
        parent::__construct($registry, Rss::class);
        $this->apiService = $apiService;
    }


    /**
     * Find rss by status
     *
     * @param string $status
     * @param int $maxResults
     * @return Rss[]
     */
    public function findByStatus($status, $maxResults = self::MAX_RESULTS_DEFAULT)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.status = :val')
            ->setParameter('val', $status)
            ->orderBy('r.pub_date', 'DESC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult()
        ;
    }


    /**
     * Update database data from external API RSS
     *
     * @param string $status
     */
    public function updateFromApi($status)
    {
        $dataFromApi = $this->apiService->getRss($status, true);

        foreach ($dataFromApi['channel']['item'] as $item)
        {
            $rssEntity = $this->findOneByLinkId($item['link_id']);
            if (is_null($rssEntity)) {
                $rssEntity = new Rss();
            }
            $rssEntity->setLinkId($item['link_id']);
            $rssEntity->setTitle($item['title']);
            $rssEntity->setVotes($item['votes']);
            $rssEntity->setKarma($item['karma']);
            $rssEntity->setComments($item['comments'][0]);
            $rssEntity->setPubDate(new \DateTime($item['pubDate']));
            $rssEntity->setStatus($item['status']);
            $this->_em->persist($rssEntity);
            $this->_em->flush();
        }

    }

    /**
     * Find one rss by link_id
     *
     * @param integer $linkId
     * @return Rss|null
     */
    public function findOneByLinkId($linkId): ?Rss
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.link_id = :val')
            ->setParameter('val', $linkId)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }


    /**
     * @param string $status
     * @return bool
     */
    public static function isValidStatus($status)
    {
        return in_array($status, static::getValidStatus());
    }

    /**
     * @return array
     */
    public static function getValidStatus()
    {
        return [self::STATUS_PUBLISHED, self::STATUS_QUEUED];
    }
}
