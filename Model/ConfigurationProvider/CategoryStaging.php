<?php

namespace MageSuite\ContentConstructorAdminCommerce\Model\ConfigurationProvider;

class CategoryStaging implements \MageSuite\ContentConstructorAdmin\Block\Adminhtml\ContentConstructor\ConfigurationProvider
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
     * @var \Magento\CatalogStaging\Model\Category\DataProviderFactory
     */
    protected $dataProviderFactory;

    public function __construct(
        \MageSuite\ContentConstructorAdmin\Repository\Xml\XmlToComponentConfigurationMapper $xmlToComponentConfiguration,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Api\FilterBuilderFactory $filterBuilderFactory,
        \Magento\CatalogStaging\Model\Category\DataProviderFactory $dataProviderFactory
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

        $categoryData = $categoryData[$id] ?? null;


        $configuration = null;

        if ($categoryData !== null and isset($categoryData['content_constructor_content']) and !empty($categoryData['content_constructor_content'])) {
            $configuration = $categoryData['content_constructor_content'];
        }

        if(empty($configuration)) {
            $configuration = json_encode([]);
        }

        return $configuration;
    }

    public function getPageType()
    {
        return 'catalogstaging_category_update_form.catalogstaging_category_update_form';
    }
}