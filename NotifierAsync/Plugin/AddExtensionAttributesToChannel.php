<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magento\NotifierAsync\Plugin;

use Magento\NotifierApi\Api\ChannelRepositoryInterface;
use Magento\NotifierApi\Api\Data\ChannelInterface;
use Magento\NotifierApi\Api\Data\ChannelExtensionFactory;
use Magento\NotifierAsync\Model\ResourceModel\GetChannelExtensionAttributes;

class AddExtensionAttributesToChannel
{
    /**
     * @var GetChannelExtensionAttributes
     */
    private $getChannelExtensionAttributes;

    /**
     * @var ChannelExtensionFactory
     */
    private $channelExtensionFactory;

    /**
     * @param GetChannelExtensionAttributes $getChannelExtensionAttributes
     * @param ChannelExtensionFactory $channelExtensionFactory
     */
    public function __construct(
        GetChannelExtensionAttributes $getChannelExtensionAttributes,
        ChannelExtensionFactory $channelExtensionFactory
    ) {
        $this->getChannelExtensionAttributes = $getChannelExtensionAttributes;
        $this->channelExtensionFactory = $channelExtensionFactory;
    }

    /**
     * @param ChannelInterface $channel
     */
    private function addExtensionAttributes(ChannelInterface $channel): void
    {
        if ($channel->getExtensionAttributes() !== null) {
            $channel->setExtensionAttributes($this->channelExtensionFactory->create());
        }

        $extAttrs = $this->getChannelExtensionAttributes->execute((int) $channel->getId());

        $channel->getExtensionAttributes()->setSendAsync((bool) $extAttrs['send_async']);
    }

    /**
     * @param ChannelRepositoryInterface $subject
     * @param ChannelInterface $result
     * @return ChannelInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGet(ChannelRepositoryInterface $subject, ChannelInterface $result): ChannelInterface
    {
        $this->addExtensionAttributes($result);
        return $result;
    }

    /**
     * @param ChannelRepositoryInterface $subject
     * @param ChannelInterface $result
     * @return ChannelInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetByCode(ChannelRepositoryInterface $subject, ChannelInterface $result): ChannelInterface
    {
        $this->addExtensionAttributes($result);
        return $result;
    }
}
