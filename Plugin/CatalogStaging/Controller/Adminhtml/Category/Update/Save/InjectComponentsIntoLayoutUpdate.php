<?php

namespace MageSuite\ContentConstructorAdminCommerce\Plugin\CatalogStaging\Controller\Adminhtml\Category\Update\Save;

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

    public function beforeExecute(\Magento\CatalogStaging\Controller\Adminhtml\Category\Update\Save $subject)
    {
        $data = $subject->getRequest()->getPostValue();

        if(isset($data['components']) and !empty($data['components'])) {
            $components = json_decode($data['components'], true);

            if(!empty($components)){
                $layoutUpdateXml = $this->configurationToXmlMapper->map($components, $data['custom_layout_update']);

                $subject->getRequest()->setPostValue('custom_layout_update', $layoutUpdateXml);
                $subject->getRequest()->setPostValue('content', '');
            }
        }
    }
}
