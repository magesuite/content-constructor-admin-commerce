<?php

namespace MageSuite\ContentConstructorAdminCommerce\Plugin\CatalogStaging\Controller\Adminhtml\Product\Save;

class SaveComponents {
    /**
     * @var \MageSuite\ContentConstructorAdmin\Repository\Xml\ComponentConfigurationToXmlMapper
     */
    protected $configurationToXmlMapper;

    /**
     * @var \MageSuite\ContentConstructorAdmin\Repository\Xml\XmlToComponentConfigurationMapper
     */
    protected $xmlToComponentConfigurationMapper;

    public function __construct(
        \MageSuite\ContentConstructorAdmin\Repository\Xml\ComponentConfigurationToXmlMapper $configurationToXmlMapper,
        \MageSuite\ContentConstructorAdmin\Repository\Xml\XmlToComponentConfigurationMapper $xmlToComponentConfigurationMapper
    )
    {
        $this->configurationToXmlMapper = $configurationToXmlMapper;
        $this->xmlToComponentConfigurationMapper = $xmlToComponentConfigurationMapper;
    }

    public function beforeExecute(\Magento\CatalogStaging\Controller\Adminhtml\Product\Save $subject)
    {
        $data = $subject->getRequest()->getPostValue();
        $product = $data['product'];

        if(isset($data['components']) and !empty($data['components'])) {
            $components = $data['components'];

            if(!empty($components)){
                $product['content_constructor_content'] = $components;
                $product['content'] = '';

                $subject->getRequest()->setPostValue('product', $product);
            }
        }
    }
}
