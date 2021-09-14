<?php

declare(strict_types=1);

namespace MageSuite\ContentConstructorAdminCommerce\Model;

use Magento\Framework\Serialize\SerializerInterface;

class ContentConstructorDataProcessor implements \MageSuite\ContentConstructorAdminCommerce\Api\ContentConstructorDataProcessorInterface
{
    public const PARAM_COMPONENTS = 'components';
    public const PARAM_CONTENT = 'content';
    public const PARAM_CONTENT_CONSTRUCTOR_CONTENT = 'content_constructor_content';

    protected SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function cleanRedundantContent(\Magento\Framework\App\RequestInterface $request): void
    {
        $data = $request->getPostValue();
        $components = $data[self::PARAM_COMPONENTS] ?? '';

        if (!$this->hasContentConstructorData($components)) {
            return;
        }

        $request->setPostValue(self::PARAM_CONTENT_CONSTRUCTOR_CONTENT, $components);
        $request->setPostValue(self::PARAM_CONTENT, '');
    }

    private function hasContentConstructorData(string $components): bool
    {
        try {
            $componentsArray = $this->serializer->unserialize($components);

            return !empty($componentsArray);
        } catch (\Exception $e) {
            return false;
        }
    }
}
