<?php
/**
 * Copyright (c) 2025. Volodymyr Hryvinskyi. All rights reserved.
 * Author: Volodymyr Hryvinskyi <volodymyr@hryvinskyi.com>
 * GitHub: https://github.com/hryvinskyi
 */
declare(strict_types=1);

namespace Hryvinskyi\ErrorReporting\Model;

use Hryvinskyi\ErrorReporting\Model\ResourceModel\ErrorLog as ErrorLogResource;
use Magento\Framework\Model\AbstractModel;

class ErrorLog extends AbstractModel
{
    protected function _construct(): void
    {
        $this->_init(ErrorLogResource::class);
    }
}
