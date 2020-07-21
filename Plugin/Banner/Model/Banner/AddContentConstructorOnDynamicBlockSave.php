<?php

namespace MageSuite\ContentConstructorAdminCommerce\Plugin\Banner\Model\Banner;

class AddContentConstructorOnDynamicBlockSave
{
    /**
     * @var \MageSuite\ContentConstructorAdminCommerce\Model\ResourceModel\DynamicBlock
     */
    protected $dynamicBlockResource;

    public function __construct(\MageSuite\ContentConstructorAdminCommerce\Model\ResourceModel\DynamicBlock $dynamicBlockResource)
    {
        $this->dynamicBlockResource = $dynamicBlockResource;
    }

    public function afterAfterSave(\Magento\Banner\Model\Banner $subject)
    {
        if ($subject->hasComponents()) {
            $this->dynamicBlockResource->addContentConstructorToDynamicBlock($subject->getBannerId(), $subject->getStoreId(), $subject->getComponents());
        }

        return $subject;
    }
}
