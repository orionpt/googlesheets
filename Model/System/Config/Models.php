<?php
/**
 * @category  Bikelec
 * @package   Bikelec_GoogleSheets
 * @author    Álvaro MArtins
 * @copyright 2022 bikelec (http://www.bikelec.es)
 */
namespace Bikelec\GoogleSheets\Model\System\Config;

use MageWorx\OptionTemplates\Model\GroupFactory;

class Models implements \Magento\Framework\Data\OptionSourceInterface
{

    /**
     * Group option factory
     *
     * @var GroupFactory
     */
    protected $groupFactory;

    /**
     *
     * @param GroupFactory $groupFactory
     */
    public function __construct(
        GroupFactory $groupFactory
    ) {
        $this->groupFactory = $groupFactory;
    }

    /**
     * @inheritdoc
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => 1, 'label' => __('Yellow')],
            ['value' => 2, 'label' => __('Red')],
            ['value' => 3, 'label' => __('Blue')],
            ['value' => 4, 'label' => __('Black')],
            ['value' => 5, 'label' => __('Silver')],
            ['value' => 6, 'label' => __('Christmas')]
        ];
    }

    /**
     * Get Options collection
     *
     */
    public function getOptionsCollection()
    {
        return $this->groupFactory->create()->getCollection();
    }
}
