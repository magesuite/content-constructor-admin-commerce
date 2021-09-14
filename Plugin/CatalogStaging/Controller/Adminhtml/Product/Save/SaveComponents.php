<?php

namespace MageSuite\ContentConstructorAdminCommerce\Plugin\CatalogStaging\Controller\Adminhtml\Product\Save;

class SaveComponents
{
    protected \Magento\Framework\Serialize\SerializerInterface $serializer;

    public function __construct(
        \Magento\Framework\Serialize\SerializerInterface $serializer
    ) {
        $this->serializer = $serializer;
    }

    public function beforeExecute(\Magento\CatalogStaging\Controller\Adminhtml\Product\Save $subject)
    {
        try {
            $data = $subject->getRequest()->getPostValue();
            $components = $data[\MageSuite\ContentConstructorAdminCommerce\Model\ContentConstructorDataProcessor::PARAM_COMPONENTS] ?? '';
            $componentsArray = $this->serializer->unserialize($components);

            if (empty($componentsArray)) {
                return;
            }

            $product = $data['product'];

            $product[\MageSuite\ContentConstructorAdminCommerce\Model\ContentConstructorDataProcessor::PARAM_CONTENT_CONSTRUCTOR_CONTENT] = $components;
            $product[\MageSuite\ContentConstructorAdminCommerce\Model\ContentConstructorDataProcessor::PARAM_CONTENT] = '';

            $subject->getRequest()->setPostValue('product', $product);
        } catch (\Exception $e) {
            return;
        }
    }
}
