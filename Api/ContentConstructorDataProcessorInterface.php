<?php

declare(strict_types=1);

namespace MageSuite\ContentConstructorAdminCommerce\Api;

interface ContentConstructorDataProcessorInterface
{
    /**
     * If request contains data from Content Constructor (field: components), then default `content` data
     * should be cleaned to prevent content redundancy at the CMS elements.
     */
    public function cleanRedundantContent(\Magento\Framework\App\RequestInterface $request): void;
}
