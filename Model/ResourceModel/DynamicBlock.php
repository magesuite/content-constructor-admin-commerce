<?php

namespace MageSuite\ContentConstructorAdminCommerce\Model\ResourceModel;

class DynamicBlock
{
    const DYNAMIC_BLOCK_CONTENTS_TABLE_NAME = 'magento_banner_content';
    const CONTENT_CONSTRUCTOR_CONTENT_COLUMN_NAME = 'content_constructor_content';

    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    protected $connection;

    public function __construct(\Magento\Framework\App\ResourceConnection $resourceConnection)
    {
        $this->connection = $resourceConnection->getConnection();
    }

    public function addContentConstructorToDynamicBlock($bannerId, $storeId, $components)
    {
        $this->connection->insertOnDuplicate(
            $this->getDynamicBlockContentsTable(),
            ['banner_id' => $bannerId, 'store_id' => $storeId, self::CONTENT_CONSTRUCTOR_CONTENT_COLUMN_NAME => $components],
            [self::CONTENT_CONSTRUCTOR_CONTENT_COLUMN_NAME]
        );
    }

    public function getContentConstructorContent($bannerId, $storeId)
    {
        $select = $this->connection
            ->select()
            ->from($this->getDynamicBlockContentsTable(), self::CONTENT_CONSTRUCTOR_CONTENT_COLUMN_NAME)
            ->where('banner_id = ?', $bannerId)
            ->where('store_id IN (?)', [$storeId, 0])
            ->order('store_id DESC');

        return $this->connection->fetchOne($select);
    }

    protected function getDynamicBlockContentsTable()
    {
        return $this->connection->getTableName(self::DYNAMIC_BLOCK_CONTENTS_TABLE_NAME);
    }
}
