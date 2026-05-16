<?php
/**
 * Copyright (c) 2025. Volodymyr Hryvinskyi. All rights reserved.
 * Author: Volodymyr Hryvinskyi <volodymyr@hryvinskyi.com>
 * GitHub: https://github.com/hryvinskyi
 */
declare(strict_types=1);

namespace Hryvinskyi\ErrorReporting\Plugin\Notification;

use Hryvinskyi\ErrorReporting\Model\ErrorLogFactory;
use Hryvinskyi\ErrorReporting\Model\Notification\NotificationDispatcher;
use Hryvinskyi\ErrorReporting\Model\ResourceModel\ErrorLog as ErrorLogResource;
use Psr\Log\LoggerInterface;

class LogDispatchPlugin
{
    public function __construct(
        private readonly ErrorLogFactory $errorLogFactory,
        private readonly ErrorLogResource $errorLogResource,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * Log error and notification results after every dispatch
     *
     * @param NotificationDispatcher $subject
     * @param array<string, bool> $result
     * @param array<string, mixed> $errorData
     * @return array<string, bool>
     */
    public function afterDispatch(
        NotificationDispatcher $subject,
        array $result,
        array $errorData
    ): array {
        try {
            $errorLog = $this->errorLogFactory->create();
            $errorLog->setData([
                'error_hash'         => $errorData['error']['hash'] ?? '',
                'error_type'         => $errorData['error']['type'] ?? '',
                'error_message'      => $errorData['error']['message'] ?? '',
                'error_file'         => $errorData['error']['file'] ?? '',
                'error_line'         => $errorData['error']['line'] ?? null,
                'severity'           => $errorData['error']['severity'] ?? '',
                'request_url'        => $errorData['request']['url'] ?? '',
                'store_id'           => $errorData['frontend_store']['id'] ?? null,
                'request_area'       => $errorData['area'] ?? '',
                'notifications_sent' => json_encode($result),
            ]);
            $this->errorLogResource->save($errorLog);
        } catch (\Throwable $e) {
            $this->logger->error('Failed to log error dispatch: ' . $e->getMessage());
        }

        return $result;
    }
}
