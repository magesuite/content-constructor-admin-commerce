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

    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    private $eventManager;

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Framework\Event\ManagerInterface $eventManager
    ) {
        $this->connection = $resourceConnection->getConnection();
        $this->eventManager = $eventManager;
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
            ->from(['main_table' => $this->getDynamicBlockContentsTable()], 'content_constructor_content')
            ->where('main_table.banner_id = ?', $bannerId)
            ->where('main_table.store_id IN (?)', [$storeId, 0])
            ->order('main_table.store_id DESC');

        $this->eventManager->dispatch(
            'magento_banner_resource_banner_content_select_init',
            ['select' => $select, 'banner_id' => $bannerId]
        );

        return $this->connection->fetchOne($select);
    }

    protected function getDynamicBlockContentsTable()
    {
        return $this->connection->getTableName(self::DYNAMIC_BLOCK_CONTENTS_TABLE_NAME);
    }
}
