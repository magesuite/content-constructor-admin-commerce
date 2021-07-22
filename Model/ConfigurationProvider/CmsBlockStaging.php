<?php

namespace MageSuite\ContentConstructorAdminCommerce\Model\ConfigurationProvider;

class CmsBlockStaging implements \MageSuite\ContentConstructorAdmin\Block\Adminhtml\ContentConstructor\ConfigurationProvider
{
    /**
     * @var \MageSuite\ContentConstructorAdmin\Repository\Xml\XmlToComponentConfigurationMapper
     */
    protected $xmlToComponentConfiguration;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \Magento\Framework\Api\FilterBuilderFactory
     */
    protected $filterBuilderFactory;

    /**
     * @var \Magento\CmsStaging\Model\Block\DataProviderFactory
     */
    protected $dataProviderFactory;

    public function __construct(
        \MageSuite\ContentConstructorAdmin\Repository\Xml\XmlToComponentConfigurationMapper $xmlToComponentConfiguration,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Api\FilterBuilderFactory $filterBuilderFactory,
        \Magento\CmsStaging\Model\Block\DataProviderFactory $dataProviderFactory
    )
    {
        $this->xmlToComponentConfiguration = $xmlToComponentConfiguration;
        $this->registry = $registry;
        $this->request = $request;
        $this->filterBuilderFactory = $filterBuilderFactory;
        $this->dataProviderFactory = $dataProviderFactory;
    }

    public function getExistingComponentsConfiguration() {
        $filterBuilder = $this->filterBuilderFactory->create();

        /** @var \Magento\CmsStaging\Model\Block\DataProvider $dataProvider */
        $dataProvider = $this->dataProviderFactory->create([
            'name' => 'cmsstaging_block_update_form_data_source',
            'primaryFieldName' => 'block_id',
            'requestFieldName' => 'block_id',
        ]);

        $id = $this->request->getParam($dataProvider->getRequestFieldName(), null);
        $filter = $filterBuilder->setField($dataProvider->getPrimaryFieldName())
            ->setValue($id)
            ->create();

        $dataProvider->addFilter($filter);

        $blockData = $dataProvider->getData();

        $blockData = $blockData[$id] ?? null;

        $configuration = null;

        if ($blockData !== null) {
            $configuration = $blockData['content_constructor_content'];
        }

        if(empty($configuration)) {
            $configuration = json_encode([]);
        }

        return $configuration;
    }

    public function getPageType()
    {
        return 'cmsstaging_block_update_form.cmsstaging_block_update_form';
    }
}
