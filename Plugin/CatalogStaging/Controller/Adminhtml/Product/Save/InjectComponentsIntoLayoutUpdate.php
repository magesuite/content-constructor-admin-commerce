<?php

namespace MageSuite\ContentConstructorAdminCommerce\Plugin\CatalogStaging\Controller\Adminhtml\Product\Save;

class InjectComponentsIntoLayoutUpdate {
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
            $components = json_decode($data['components'], true);

            if(!empty($components)){
                $layoutUpdateXml = $this->configurationToXmlMapper->map($components, $product['custom_layout_update']);

                $product['custom_layout_update'] = $layoutUpdateXml;
                $product['content'] = '';

                $subject->getRequest()->setPostValue('product', $product);
            }
        }
    }
}
