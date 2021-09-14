<?php

namespace MageSuite\ContentConstructorAdminCommerce\Model\ConfigurationProvider;

class CategoryStaging implements \MageSuite\ContentConstructorAdmin\Block\Adminhtml\ContentConstructor\ConfigurationProvider
{
    public const PAGE_TYPE = 'catalogstaging_category_update_form.catalogstaging_category_update_form';

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \Magento\Framework\Api\FilterBuilderFactory
     */
    protected $filterBuilderFactory;

    /**
     * @var \Magento\CatalogStaging\Model\Category\DataProviderFactory
     */
    protected $dataProviderFactory;

    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Api\FilterBuilderFactory $filterBuilderFactory,
        \Magento\CatalogStaging\Model\Category\DataProviderFactory $dataProviderFactory
    ) {
        $this->request = $request;
        $this->filterBuilderFactory = $filterBuilderFactory;
        $this->dataProviderFactory = $dataProviderFactory;
    }

    public function getExistingComponentsConfiguration()
    {
        $filterBuilder = $this->filterBuilderFactory->create();

        $dataProvider = $this->dataProviderFactory->create([
            'name' => 'catalogstaging_category_update_form_data_source',
            'primaryFieldName' => 'entity_id',
            'requestFieldName' => 'id',
        ]);

        $id = $this->request->getParam($dataProvider->getRequestFieldName(), null);
        $filter = $filterBuilder->setField($dataProvider->getPrimaryFieldName())
            ->setValue($id)
            ->create();

        $dataProvider->addFilter($filter);
        $categoryData = $dataProvider->getData();

        return $categoryData[$id][\MageSuite\ContentConstructorAdminCommerce\Model\ContentConstructorDataProcessor::PARAM_CONTENT_CONSTRUCTOR_CONTENT] ?? json_encode([]);
    }

    public function getPageType()
    {
        return self::PAGE_TYPE;
    }
}
