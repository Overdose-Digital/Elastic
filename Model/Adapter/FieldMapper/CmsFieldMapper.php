<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Overdose\Elastic\Model\Adapter\FieldMapper;

use Magento\Elasticsearch\Model\Adapter\FieldMapperInterface;

/**
 * Class ProductFieldMapper provides field name by attribute code and retrieve all attribute types
 */
class CmsFieldMapper implements FieldMapperInterface
{
    /**
     * Get field name.
     *
     * @param string $attributeCode
     * @param array $context
     * @return string
     */
    public function getFieldName($attributeCode, $context = [])
    {
        return $attributeCode;
    }

    /**
     * Get all attributes types.
     *
     * @param array $context
     * @return array
     */
    public function getAllAttributesTypes($context = [])
    {
        return [];
    }
}
