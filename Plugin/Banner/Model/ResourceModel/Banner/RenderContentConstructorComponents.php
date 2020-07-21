<?php

namespace MageSuite\ContentConstructorAdminCommerce\Plugin\Banner\Model\ResourceModel\Banner;

class RenderContentConstructorComponents
{
    /**
     * @var \MageSuite\ContentConstructorAdminCommerce\Model\ResourceModel\DynamicBlock
     */
    protected $dynamicBlockResource;

    /**
     * @var \Magento\Framework\View\Element\BlockFactory
     */
    protected $blockFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    public function __construct(
        \MageSuite\ContentConstructorAdminCommerce\Model\ResourceModel\DynamicBlock $dynamicBlockResource,
        \Magento\Framework\View\Element\BlockFactory $blockFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->dynamicBlockResource = $dynamicBlockResource;
        $this->blockFactory = $blockFactory;
        $this->logger = $logger;
    }

    public function aroundGetStoreContent(\Magento\Banner\Model\ResourceModel\Banner $subject, \Closure $proceed, $bannerId, $storeId)
    {
        $contentConstructorContent = $this->dynamicBlockResource->getContentConstructorContent($bannerId, $storeId);

        if (empty($contentConstructorContent)) {
            return $proceed($bannerId, $storeId);
        }

        $components = json_decode($contentConstructorContent, true);

        if (empty($components)) {
            return $proceed($bannerId, $storeId);
        }

        $html = '';

        foreach ($components as $component) {
            try {
                $componentBlock = $this->blockFactory->createBlock(\MageSuite\ContentConstructorFrontend\Block\Component::class, [
                    'data' => $component
                ]);

                $html .= $componentBlock->toHtml();
            } catch (\Exception $exception) {
                $this->logger->warning($exception->getMessage());
            }
        }

        return $html;
    }
}
