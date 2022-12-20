<?php

namespace Bikelec\GoogleSheets\Cron;

use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use MageWorx\OptionTemplates\Model\ResourceModel\Group;
use MageWorx\OptionTemplates\Model\ResourceModel\GroupFactory;
use Magento\CatalogInventory\Model\Stock\StockItemRepository;
use Magento\Catalog\Model\Product\Attribute\Source\Status;

class UpdateStocks
{

    /**
     * Group option factory
     *
     * @var GroupFactory
     */
    protected GroupFactory$groupFactory;

    /**
     * Stock Item Repository
     *
     * @var StockItemRepository
     */
    protected StockItemRepository $stockItemRepository;

    /**
     * @var CollectionFactory
     */
    protected CollectionFactory $productCollectionFactory;

    /**
     * Class constructor
     *
     * @param GroupFactory $groupFactory
     * @param StockItemRepository $stockItemRepository
     * @param CollectionFactory $productCollectionFactory
     */
    public function __construct(
        GroupFactory $groupFactory,
        StockItemRepository $stockItemRepository,
        CollectionFactory $productCollectionFactory
    ) {
        $this->groupFactory = $groupFactory;
        $this->stockItemRepository = $stockItemRepository;
        $this->productCollectionFactory = $productCollectionFactory;
    }

    /**
     * Executor
     */
    public function execute()
    {

        $data = [];
        $products = $this->getProductCollection()
            ->addStoreFilter('1')
            ->addPriceData()
            ->addAttributeToSelect('special_from_date')
            ->addAttributeToSelect('special_to_date')
            ->addAttributeToSelect('special_price');

        foreach ($products as $product) {
            $data[]['sku'] = $product->getSku();
            $data[]['type_id'] = $product->getTypeId();
            $data[]['price'] = $product->getPrice();
            $data[]['final_price'] = $product->getFinalPrice();
            $data[]['price_component'] = $product->getPriceComponent();
            $data[]['special_price'] = $product->getSpecialPrice();
            $data[]['special_from_date'] = $product->getSpecialFromDate();
            $data[]['special_to_date'] = $product->getSpecialToDate();
        }

        $options = $products;
    }

    /**
     * Get all existing Option Templates
     *
     * @return array
     */
    private function getOptionTemplates()
    {
        return $this->groupFactory->create()->getAllGroupsData();
    }

    /**
     * Get all enabled Products
     *
     * @return Collection
     */
    public function getProductCollection()
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addAttributeToFilter('status', Status::STATUS_ENABLED);
        return $collection;
    }

    /**
     * Get values for spreadsheet.
     *
     * @param array $fields
     * @param array $data
     * @return array
     *   The values for spreadsheet.
     */
    private function getValues(array $fields, array $data): array
    {
        $headers = array_map(static function ($item) {
            return str_replace('<br>', "\n", $item);
        }, array_values($fields));

        $rows = array_map(static function ($item) {
            return array_values($item);
        }, $data);

        return array_merge([$headers], array_values($rows));
    }

}
