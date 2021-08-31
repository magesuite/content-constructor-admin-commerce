<?php

namespace MageSuite\ContentConstructorAdminCommerce\Model\ConfigurationProvider;

class ProductStaging implements \MageSuite\ContentConstructorAdmin\Block\Adminhtml\ContentConstructor\ConfigurationProvider
{
    public const PAGE_TYPE = 'catalogstaging_update_form.catalogstaging_update_form';

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
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Api\FilterBuilderFactory $filterBuilderFactory,
        \Magento\CatalogStaging\Model\Product\DataProviderFactory $dataProviderFactory
    ) {
        $this->request = $request;
        $this->filterBuilderFactory = $filterBuilderFactory;
        $this->dataProviderFactory = $dataProviderFactory;
    }

    public function getExistingComponentsConfiguration()
    {
        $filterBuilder = $this->filterBuilderFactory->create();

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

        return $productData[$id]['product'][\MageSuite\ContentConstructorAdminCommerce\Model\ContentConstructorDataProcessor::PARAM_CONTENT_CONSTRUCTOR_CONTENT] ?? json_encode([]);
    }

    public function getPageType()
    {
        return self::PAGE_TYPE;
    }
}
