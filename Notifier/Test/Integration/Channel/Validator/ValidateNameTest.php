<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare(strict_types=1);

namespace Magento\Notifier\Test\Integration\Channel\Validator;

use Magento\Framework\Exception\ValidatorException;
use Magento\Framework\ObjectManagerInterface;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\Notifier\Model\Channel;
use Magento\Notifier\Model\Channel\Validator\ValidateName;
use PHPUnit\Framework\TestCase;

class ValidateNameTest extends TestCase
{
    /**
     * @var ValidateName
     */
    private $subject;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $this->subject = $this->objectManager->get(ValidateName::class);
    }

    /**
     * @return array
     */
    public function invalidChannelDataProvider(): array
    {
        return [
            [
                'channelData' => [
                    'name' => ''
                ],
                'errorMessage' => 'Channel name is required'
            ],
            [
                'channelData' => [
                    'name' => '               '
                ],
                'errorMessage' => 'Channel name is required'
            ]
        ];
    }

    /**
     * @return array
     */
    public function validChannelDataProvider(): array
    {
        return [
            [
                'channelData' => [
                    'name' => 'A valid name'
                ]
            ]
        ];
    }

    /**
     * @param array $channelData
     * @param string $errorMessage
     * @dataProvider invalidChannelDataProvider
     */
    public function testShouldTriggerValidationException(array $channelData, string $errorMessage): void
    {
        $channel = $this->objectManager->create(
            Channel::class,
            [
                'data' => $channelData
            ]
        );

        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage($errorMessage);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->subject->execute($channel);
    }

    /**
     * @param array $channelData
     * @dataProvider validChannelDataProvider
     */
    public function testShouldValidateChannel(array $channelData): void
    {
        $channel = $this->objectManager->create(
            Channel::class,
            [
                'data' => $channelData
            ]
        );

        $this->subject->execute($channel);
    }
}
