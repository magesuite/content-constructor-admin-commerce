<?php

namespace MageSuite\ContentConstructorAdminCommerce\Plugin\Banner\Controller\Ajax\Load;

class AddProductTileHandler
{
    /**
     * @var \Magento\Framework\Controller\ResultFactory
     */
    protected $resultFactory;

    public function __construct(\Magento\Framework\Controller\ResultFactory $resultFactory)
    {
        $this->resultFactory = $resultFactory;
    }

    public function beforeExecute()
    {
        $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);
    }
}
