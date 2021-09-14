<?php

declare(strict_types=1);

/**
 * @magentoAppArea adminhtml
 */
class ContentConstructorDataProcessorTest extends \Magento\TestFramework\TestCase\AbstractBackendController
{
    private const BLOCK_IDENTIFIER = 'default_store_block';
    private const REQUEST_PATH = 'backend/cmsstaging/block/update_save';

    private ?\Magento\Cms\Api\GetBlockByIdentifierInterface $getBlockByIdentifier;
    private ?\Magento\Framework\Serialize\SerializerInterface $serializer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->getBlockByIdentifier = $this->_objectManager->create(\Magento\Cms\Api\GetBlockByIdentifierInterface::class);
        $this->serializer = $this->_objectManager->create(\Magento\Framework\Serialize\SerializerInterface::class);
    }

    /**
     * @magentoDataFixture Magento/Cms/_files/block_default_store.php
     * @dataProvider dataProviderContent
     */
    public function testModifyRequestContentData(
        string $content,
        string $components,
        bool $contentShouldBeEmpty
    ): void {
        $params = $this->getRequestParams($content, $components);

        $this->getRequest()->setPostValue($params);
        $this->dispatch(self::REQUEST_PATH);

        $postParams = $this->getRequest()->getPostValue();
        $this->assertEquals(
            $contentShouldBeEmpty,
            empty($postParams[\MageSuite\ContentConstructorAdminCommerce\Model\ContentConstructorDataProcessor::PARAM_CONTENT])
        );

        $responseBody = $this->getResponse()->getBody();
        $response = $this->serializer->unserialize($responseBody);

        $this->assertFalse($response['error']);
    }

    private function getRequestParams(string $content, string $components): array
    {
        $block = $this->getBlockByIdentifier->execute(
            self::BLOCK_IDENTIFIER,
            \Magento\Store\Model\Store::DEFAULT_STORE_ID
        );

        return [
            'block_id' => $block->getId(),
            'title' => $block->getTitle(),
            'identifier' => $block->getIdentifier(),
            'content' => $content,
            'creation_time' => $block->getCreationTime(),
            'update_time' => $this->getUpdateTime(),
            'is_active' => '0',
            'row_id' => $block->getData('row_id'),
            'created_in' => '1',
            'updated_in' => '1630411020',
            'content_constructor_content' => '[]',
            '_first_store_id' => '0',
            'store_code' => 'default',
            'store_id' =>
                [
                    0 => '0',
                ],
            'staging' =>
                [
                    'mode' => 'save',
                    'update_id' => '',
                    'name' => 'test-update',
                    'description' => '',
                    'start_time' => $this->getStagingStartTime(),
                    'end_time' => '',
                    'select_id' => '1630324800',
                ],
            'components' => $components,
            'use_default' =>
                [
                    'custom_layout_update' => '0',
                ],
        ];
    }

    private function getUpdateTime(): string
    {
        return date(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT);
    }

    private function getStagingStartTime(): string
    {
        $today = new \DateTime();
        $today->modify('+1 day');

        return $today->format('Y-m-d\TH:i:s\Z');
    }

    public function dataProviderContent(): array
    {
        return [
            [
                '<p>test-content</p>', '', false,
            ],
            [
                '<p>test-content</p>', '[]', false,
            ],
            [
                '<p>test-content</p>', '["value"]', true,
            ],
        ];
    }
}
