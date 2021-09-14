<?php

namespace MageSuite\ContentConstructorAdminCommerce\Test\Unit\Model;

use MageSuite\ContentConstructorAdminCommerce\Model\ContentConstructorDataProcessor;
use PHPUnit\Framework\TestCase;

class ContentConstructorDataProcessorTest extends TestCase
{
    private ?ContentConstructorDataProcessor $cleaner;

    protected function setUp(): void
    {
        $serializer = new \Magento\Framework\Serialize\Serializer\Json();
        $this->cleaner = new \MageSuite\ContentConstructorAdminCommerce\Model\ContentConstructorDataProcessor($serializer);
    }

    /**
     * @dataProvider dataProviderContent
     */
    public function testCleanRedundantContent(?string $componentsData, int $setPostCallsNumber): void
    {
        $postData = [
            'components' => $componentsData,
        ];

        $request = $this->createMock(\Magento\Framework\App\Request\Http::class);
        $request->expects($this->once())->method('getPostValue')->willReturn($postData);
        $request->expects($this->exactly($setPostCallsNumber))->method('setPostValue');

        $this->cleaner->cleanRedundantContent($request);
    }

    public function dataProviderContent(): array
    {
        return [
            [
                '', 0,
            ],
            [
                '[]', 0,
            ],
            [
                '["value"]', 2,
            ],
        ];
    }
}
