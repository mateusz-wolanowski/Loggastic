<?php

namespace Locastic\ActivityLog\Metadata\LoggableContext\Factory;

use ApiPlatform\Core\Util\ReflectionClassRecursiveIterator;
use Locastic\ActivityLog\Annotation\Loggable;
use Locastic\ActivityLog\Metadata\LoggableContext\LoggableContextCollection;
use Doctrine\Common\Annotations\Reader;
use Symfony\Contracts\Cache\CacheInterface;

class CachedLoggableContextCollectionFactory implements LoggableContextCollectionFactoryInterface
{
    public const CACHE_KEY = 'loggable_name_collection';

    private CacheInterface $cache;
    private LoggableContextCollectionFactoryInterface $decorated;

    public function __construct(LoggableContextCollectionFactoryInterface $decorated, CacheInterface $cache)
    {
        $this->cache = $cache;
        $this->decorated = $decorated;
    }

    /**
     * {@inheritdoc}
     */
    public function create(): LoggableContextCollection
    {
        return $this->cache->get(self::CACHE_KEY, function () {
            return $this->decorated->create();
        });
    }
}