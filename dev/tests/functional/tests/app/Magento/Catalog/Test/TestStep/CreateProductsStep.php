<?php
/**
 * @copyright Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 */

namespace Magento\Catalog\Test\TestStep;

use Mtf\Fixture\FixtureFactory;
use Mtf\Fixture\FixtureInterface;
use Mtf\TestStep\TestStepInterface;

/**
 * Class CreateProductsStep
 * Create products using handler
 */
class CreateProductsStep implements TestStepInterface
{
    /**
     * Products names in data set
     *
     * @var string
     */
    protected $products;

    /**
     * Product data
     *
     * @var array
     */
    protected $data;

    /**
     * Factory for Fixtures
     *
     * @var FixtureFactory
     */
    protected $fixtureFactory;

    /**
     * Preparing step properties
     *
     * @constructor
     * @param FixtureFactory $fixtureFactory
     * @param string $products
     * @param array $data [optional]
     */
    public function __construct(FixtureFactory $fixtureFactory, $products, array $data = [])
    {
        $this->products = $products;
        $this->data = $data;
        $this->fixtureFactory = $fixtureFactory;
    }

    /**
     * Create products
     *
     * @return array
     */
    public function run()
    {
        $products = [];
        $productsDataSets = explode(',', $this->products);
        foreach ($productsDataSets as $key => $productDataSet) {
            $productDataSet = explode('::', $productDataSet);
            $fixtureClass = $productDataSet[0];
            $dataSet = isset($productDataSet[1]) ? $productDataSet[1] : '';
            $data = isset($this->data[$key]) ? $this->data[$key] : [];
            /** @var FixtureInterface[] $products */
            $products[$key] = $this->fixtureFactory->createByCode(
                trim($fixtureClass),
                ['dataSet' => trim($dataSet), 'data' => $data]
            );
            if ($products[$key]->hasData('id') === false) {
                $products[$key]->persist();
            }
        }

        return ['products' => $products];
    }
}
