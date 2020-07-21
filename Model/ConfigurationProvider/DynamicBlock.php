<?php

namespace MageSuite\ContentConstructorAdminCommerce\Model\ConfigurationProvider;

class DynamicBlock implements \MageSuite\ContentConstructorAdmin\Block\Adminhtml\ContentConstructor\ConfigurationProvider
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \MageSuite\ContentConstructorAdminCommerce\Model\ResourceModel\DynamicBlock
     */
    protected $dynamicBlockResource;

    /**
     * @var \MageSuite\ContentConstructorAdmin\Repository\Xml\XmlToComponentConfigurationMapper
     */
    protected $xmlToComponentConfiguration;

    public function __construct(
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\RequestInterface $request,
        \MageSuite\ContentConstructorAdminCommerce\Model\ResourceModel\DynamicBlock $dynamicBlockResource,
        \MageSuite\ContentConstructorAdmin\Repository\Xml\XmlToComponentConfigurationMapper $xmlToComponentConfiguration

    ) {
        $this->registry = $registry;
        $this->request = $request;
        $this->dynamicBlockResource = $dynamicBlockResource;
        $this->xmlToComponentConfiguration = $xmlToComponentConfiguration;
    }

    public function getExistingComponentsConfiguration()
    {
        /** @var \Magento\Banner\Model\Banner $dynamicBlock */
        $dynamicBlock = $this->registry->registry('current_banner');

        $emptyConfiguration = json_encode([]);

        if ($dynamicBlock === null) {
            return $emptyConfiguration;
        }

        $storeId = $storeId = $this->request->getParam('store', \Magento\Store\Model\Store::DEFAULT_STORE_ID);
        $contentConstructorContent = $this->dynamicBlockResource->getContentConstructorContent($dynamicBlock->getId(), $storeId);

        if (empty($contentConstructorContent)) {
            return $emptyConfiguration;
        }

        return $contentConstructorContent;
    }

    public function getPageType()
    {
        return 'banner_form.banner_form';
    }
}
