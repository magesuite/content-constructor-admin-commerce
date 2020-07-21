<?php

namespace MageSuite\ContentConstructorAdminCommerce\Ui\DataProvider\Product\Form\Modifier;

class ContentConstructorContent extends \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier
{
    /**
     * @var \Magento\Catalog\Model\Locator\LocatorInterface
     */
    protected $locator;

    public function __construct(\Magento\Catalog\Model\Locator\LocatorInterface $locator)
    {
        $this->locator = $locator;
    }

    /**
     * @inheritDoc
     */
    public function modifyData(array $data)
    {
        $modelId = $this->locator->getProduct()->getId();
        $product = $this->locator->getProduct();

        $data[$modelId][self::DATA_SOURCE_DEFAULT]['content_constructor_content'] = $product->getContentConstructorContent();

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function modifyMeta(array $meta)
    {
        return $meta;
    }
}