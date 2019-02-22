<?php

namespace MageSuite\ContentConstructorAdminCommerce\Model\ConfigurationProvider;

class ProductStaging implements \MageSuite\ContentConstructorAdmin\Block\Adminhtml\ContentConstructor\ConfigurationProvider
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
     * @var \Magento\CatalogStaging\Model\Product\DataProviderFactory
     */
    protected $dataProviderFactory;

    public function __construct(
        \MageSuite\ContentConstructorAdmin\Repository\Xml\XmlToComponentConfigurationMapper $xmlToComponentConfiguration,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Api\FilterBuilderFactory $filterBuilderFactory,
        \Magento\CatalogStaging\Model\Product\DataProviderFactory $dataProviderFactory
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

        /** @var \Magento\CmsStaging\Model\Page\DataProvider $dataProvider */
        $dataProvider = $this->dataProviderFactory->create([
            'name' => 'catalogstaging_update_form_data_source',
            'primaryFieldName' => 'entity_id',
            'requestFieldName' => 'id',
        ]);

        $id = $this->request->getParam($dataProvider->getRequestFieldName(), null);
        $filter = $filterBuilder->setField($dataProvider->getPrimaryFieldName())
            ->setValue($id)
            ->create();

        $dataProvider->addFilter($filter);

        $productData = $dataProvider->getData();

        $productData = $productData[$id]['product'] ?? null;

        $configuration = [];

        if ($productData !== null and isset($productData['custom_layout_update'])) {
            $configuration = $this->xmlToComponentConfiguration->map($productData['custom_layout_update']);
        }

        return json_encode($configuration);
    }

    public function getPageType()
    {
        return 'catalogstaging_update_form.catalogstaging_update_form';
    }
}