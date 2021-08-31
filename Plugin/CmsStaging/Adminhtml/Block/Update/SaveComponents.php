<?php

namespace MageSuite\ContentConstructorAdminCommerce\Plugin\CmsStaging\Adminhtml\Block\Update;

class SaveComponents
{
    protected \MageSuite\ContentConstructorAdminCommerce\Api\ContentConstructorDataProcessorInterface $requestCleaner;

    public function __construct(
        \MageSuite\ContentConstructorAdminCommerce\Api\ContentConstructorDataProcessorInterface $requestCleaner
    ) {
        $this->requestCleaner = $requestCleaner;
    }

    public function beforeExecute(\Magento\CmsStaging\Controller\Adminhtml\Block\Update\Save $subject)
    {
        $request = $subject->getRequest();

        $this->requestCleaner->cleanRedundantContent($request);
    }
}
