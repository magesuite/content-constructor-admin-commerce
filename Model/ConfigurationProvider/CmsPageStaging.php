<?php

namespace MageSuite\ContentConstructorAdminCommerce\Model\ConfigurationProvider;

class CmsPageStaging implements \MageSuite\ContentConstructorAdmin\Block\Adminhtml\ContentConstructor\ConfigurationProvider
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
     * @var \Magento\CmsStaging\Model\Page\DataProviderFactory
     */
    protected $dataProviderFactory;

    public function __construct(
        \MageSuite\ContentConstructorAdmin\Repository\Xml\XmlToComponentConfigurationMapper $xmlToComponentConfiguration,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Api\FilterBuilderFactory $filterBuilderFactory,
        \Magento\CmsStaging\Model\Page\DataProviderFactory $dataProviderFactory
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
            'name' => 'cmsstaging_page_update_form_data_source',
            'primaryFieldName' => 'page_id',
            'requestFieldName' => 'page_id',
        ]);

        $id = $this->request->getParam($dataProvider->getRequestFieldName(), null);
        $filter = $filterBuilder->setField($dataProvider->getPrimaryFieldName())
            ->setValue($id)
            ->create();

        $dataProvider->addFilter($filter);

        $pageData = $dataProvider->getData();

        $pageData = $pageData[$id] ?? null;

        $configuration = null;

        if ($pageData !== null) {
            $configuration = $pageData['content_constructor_content'];
        }

        if(empty($configuration)) {
            $configuration = json_encode([]);
        }

        return $configuration;
    }

    public function getPageType()
    {
        return 'cmsstaging_page_update_form.cmsstaging_page_update_form';
    }
}