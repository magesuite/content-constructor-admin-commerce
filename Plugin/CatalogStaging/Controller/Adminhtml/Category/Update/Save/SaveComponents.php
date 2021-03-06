<?php

namespace MageSuite\ContentConstructorAdminCommerce\Plugin\CatalogStaging\Controller\Adminhtml\Category\Update\Save;

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

    public function beforeExecute(\Magento\CatalogStaging\Controller\Adminhtml\Category\Update\Save $subject)
    {
        $data = $subject->getRequest()->getPostValue();

        if(isset($data['components']) and !empty($data['components'])) {
            $components = $data['components'];

            if(!empty($components)){
                $subject->getRequest()->setPostValue('content_constructor_content', $components);
                $subject->getRequest()->setPostValue('content', '');
            }
        }
    }
}
