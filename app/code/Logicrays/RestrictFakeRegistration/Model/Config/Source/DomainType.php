<?php
/**
 * Logicrays
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Logicrays
 * @package     Logicrays_RestrictFakeRegistration
 * @copyright   Copyright (c) Logicrays (https://www.logicrays.com/)
 */
declare(strict_types=1);
 
namespace Logicrays\RestrictFakeRegistration\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class DomainType implements ArrayInterface
{
   
    public const INCLUSIVE_LIST = 1;
    public const EXCLUSIVE_LIST = 2;

    /**
     * Method return array of DomainType options.
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];
        foreach ($this->toArray() as $value => $label) {
            $options[] = [
                'value' => $value,
                'label' => $label
            ];
        }

        return $options;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [
                    self::INCLUSIVE_LIST => __('Inclusive List'),
                    self::EXCLUSIVE_LIST => __('Exclusive List')
                ];
    }
}
