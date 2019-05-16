<?php

namespace App\Service;

use App\Entity\Rss;
use App\Exception\ExternalApiError;
use App\Exception\RssStatusNotValid;
use App\Repository\RssRepository;
use GuzzleHttp;
use Psr\SimpleCache\CacheInterface;

class ExternalApiService
{
    const API_ENDPOINT   = 'https://www.meneame.net/rss';
    const CACHE_LIFETIME = 20; // 20 seconds
    const CACHE_KEY_RSS = 'rss';
    const CACHE_KEY_RSS_QUEUED = 'rss-queued';

    /**
     * @var GuzzleHttp\Client
     */
    private $client;


    /**
     * @var array
     */
    private $params = [];

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * ExternalApiService constructor.
     *
     * @param RedisCacheService $cache
     */
    public function __construct(RedisCacheService $cache)
    {
        $this->initCurlApiClient();

        $this->cache = $cache;
    }


    /**
     * Initialize Guzzle Client
     */
    private function initCurlApiClient()
    {
        $this->client = new GuzzleHttp\Client(['base_uri' => self::API_ENDPOINT]);
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array $params
     *
     * @return $this
     */
    public function setParams(array $params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @param string $param
     * @param string $value
     *
     * @return $this
     */
    public function addParam($param, $value)
    {
        $this->params[$param] = $value;

        return $this;
    }

    /**
     * @return GuzzleHttp\Client
     */
    public function getClient(): GuzzleHttp\Client
    {
        return $this->client;
    }

    /**
     * @param boolean $forceApi
     * @param string $status (published or queued from Rss entity)
     *
     * @return array
     */
    public function getRss($status, $forceApi = false)
    {
        // Validate status
        if (RssRepository::isValidStatus($status) === false)
        {
            throw new RssStatusNotValid(sprintf('Status %s is not valid status: %s', $status, implode(', ', RssRepository::getValidStatus())));
        }

        $cacheKey = $this->getCacheKeyFromStatus($status);

        if (false === $forceApi && $this->cache->has($cacheKey))
        {
            $rssData = json_decode($this->cache->get($cacheKey), true);
        }
        else
        {
            $this->setParamsFromStatus($status);
            $apiDataResponse = $this->doApiCall();
            $rssData = json_decode($apiDataResponse, true);
            $this->cache->set($cacheKey, $apiDataResponse, self::CACHE_LIFETIME);
        }
        return $rssData;
    }

    /**
     * @param string $status
     * @return $this
     */
    private function setParamsFromStatus($status)
    {
        if ($status == RssRepository::STATUS_QUEUED) {
            $this->addParam('status', RssRepository::STATUS_QUEUED);
        }

        return $this;
    }

    /**
     * @param string $status
     * @return string
     */
    private function getCacheKeyFromStatus($status)
    {
        return ($status == RssRepository::STATUS_QUEUED) ? self::CACHE_KEY_RSS_QUEUED : self::CACHE_KEY_RSS;
    }

    /**
     * Get external api data.
     *
     * @throws ExternalApiError
     * @return string
     */
    private function doApiCall()
    {
        // Get external api response
        $response = $this->getClient()->request('GET',self::API_ENDPOINT . '?' . http_build_query($this->getParams()));
        if ($response->getStatusCode() != 200)
        {
            throw new ExternalApiError('External API error response');
        }
        else
        {
            $apiResponse = str_replace('meneame:', '', (string)$response->getBody());
            $xml = simplexml_load_string($apiResponse);

            return json_encode($xml);
        }
    }
}