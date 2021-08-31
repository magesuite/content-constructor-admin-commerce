<?php

namespace MageSuite\ContentConstructorAdminCommerce\Plugin\CatalogStaging\Controller\Adminhtml\Category\Update\Save;

class SaveComponents
{
    protected \MageSuite\ContentConstructorAdminCommerce\Api\ContentConstructorDataProcessorInterface $requestCleaner;

    public function __construct(
        \MageSuite\ContentConstructorAdminCommerce\Api\ContentConstructorDataProcessorInterface $requestCleaner
    ) {
        $this->requestCleaner = $requestCleaner;
    }

    public function beforeExecute(\Magento\CatalogStaging\Controller\Adminhtml\Category\Update\Save $subject)
    {
        $request = $subject->getRequest();

        $this->requestCleaner->cleanRedundantContent($request);
    }
}
