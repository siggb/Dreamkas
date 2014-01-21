<?php

namespace Lighthouse\CoreBundle\Tests\Document\TrialBalance;

use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProduct;
use Lighthouse\CoreBundle\Document\Sale\Product\SaleProduct;
use Lighthouse\CoreBundle\Document\TrialBalance\CostOfGoodCalculator;
use Lighthouse\CoreBundle\Document\TrialBalance\TrialBalance;
use Lighthouse\CoreBundle\Test\WebTestCase;

class CostOfGoodsTest extends WebTestCase
{
    public function testIndexRangeCreatedOnInvoiceConsecutiveInsert()
    {
        $productIds = $this->createProductsBySku(array('1', '2', '3'));

        $store1 = $this->createStore('701');

        $invoice11 = $this->createInvoice(array('sku' => 1, 'acceptanceDate' => '2014-01-12 12:23:13'), $store1);
        $this->createInvoiceProduct($invoice11, $productIds['1'], 105.678, 16.36, $store1);
        $this->createInvoiceProduct($invoice11, $productIds['3'], 320, 178.34, $store1);

        $invoice12 = $this->createInvoice(array('sku' => 2, 'acceptanceDate' => '2014-01-13 19:56:04'), $store1);
        $this->createInvoiceProduct($invoice12, $productIds['2'], 45.04, 189.67, $store1);
        $this->createInvoiceProduct($invoice12, $productIds['3'], 115.12, 176.51, $store1);

        $invoice13 = $this->createInvoice(array('sku' => 3, 'acceptanceDate' => '2014-01-13 20:03:14'), $store1);
        $this->createInvoiceProduct($invoice13, $productIds['1'], 111.67, 201.15, $store1);
        $this->createInvoiceProduct($invoice13, $productIds['3'], 115, 176.51, $store1);

        $invoice14 = $this->createInvoice(array('sku' => 4, 'acceptanceDate' => '2014-01-14 08:15:31'), $store1);
        $this->createInvoiceProduct($invoice14, $productIds['1'], 300.01, 201.15, $store1);

        $trialBalanceRepository = $this->getContainer()->get('lighthouse.core.document.repository.trial_balance');

        $storeProductRepository = $this->getContainer()->get('lighthouse.core.document.repository.store_product');

        foreach ($productIds as $productId) {
            $storeProductId = $storeProductRepository->getIdByStoreIdAndProductId($store1, $productId);
            $prevEndIndex = '0.000';
            $trailBalances = $trialBalanceRepository->findByStoreProductIdAndReasonType(
                $storeProductId,
                InvoiceProduct::REASON_TYPE
            );
            foreach ($trailBalances as $trailBalance) {
                $this->assertInstanceOf('Lighthouse\\CoreBundle\\Types\\Numeric\\Quantity', $trailBalance->startIndex);
                $this->assertSame($prevEndIndex, $trailBalance->startIndex->toString());
                $prevEndIndex = $trailBalance->endIndex->toString();
                $this->assertNotSame('0.000', $prevEndIndex);
            }
        }
    }

    public function testIndexRangeCreatedOnSaleConsecutiveInsert()
    {
        $productIds = $this->createProductsBySku(array('1', '2', '3'));

        $store1 = $this->createStore('701');

        $invoice11 = $this->createInvoice(array('sku' => 1, 'acceptanceDate' => '2014-01-10 12:23:13'), $store1);
        $this->createInvoiceProduct($invoice11, $productIds['1'], 16.36, 10.09, $store1);
        $this->createInvoiceProduct($invoice11, $productIds['2'], 10.067, 29.56, $store1);
        $this->createInvoiceProduct($invoice11, $productIds['3'], 20, 30.05, $store1);

        $sale1 = $this->factory->createSale($store1, '2014-01-11 13:45:09', 110.23);
        $this->factory->createSaleProduct(12.11, 9.102, $productIds['1'], $sale1);
        $this->factory->createSaleProduct(34.12, 7, $productIds['3'], $sale1);
        $this->factory->createSaleProduct(34.12, 1, $productIds['3'], $sale1);
        $this->factory->flush();

        $sale2 = $this->factory->createSale($store1, '2014-01-12 15:45:09', 110.23);
        $this->factory->createSaleProduct(34.99, 2.056, $productIds['2'], $sale2);
        $this->factory->createSaleProduct(35.15, 6, $productIds['3'], $sale2);
        $this->factory->flush();

        $sale3 = $this->factory->createSale($store1, '2014-01-12 15:45:10', 110.23);
        $this->factory->createSaleProduct(11.49, 4.56, $productIds['1'], $sale3);
        $this->factory->createSaleProduct(35.15, 2, $productIds['3'], $sale3);
        $this->factory->flush();

        $trialBalanceRepository = $this->getContainer()->get('lighthouse.core.document.repository.trial_balance');

        $storeProductRepository = $this->getContainer()->get('lighthouse.core.document.repository.store_product');

        $countAssertions = array(
            $productIds['1'] => 2,
            $productIds['2'] => 1,
            $productIds['3'] => 4,
        );

        foreach ($productIds as $productId) {
            $storeProductId = $storeProductRepository->getIdByStoreIdAndProductId($store1, $productId);
            $prevEndIndex = '0.000';
            $trailBalances = $trialBalanceRepository->findByStoreProductIdAndReasonType(
                $storeProductId,
                SaleProduct::REASON_TYPE
            );
            $this->assertEquals($countAssertions[$productId], $trailBalances->count(true));
            foreach ($trailBalances as $trailBalance) {
                $this->assertInstanceOf('Lighthouse\\CoreBundle\\Types\\Numeric\\Quantity', $trailBalance->startIndex);
                $this->assertSame($prevEndIndex, $trailBalance->startIndex->toString());
                $prevEndIndex = $trailBalance->endIndex->toString();
                $this->assertNotSame('0.000', $prevEndIndex);
            }
        }
    }

    public function testIndexRangeCreatedOnSaleInsertManyPositionsInOneSale()
    {
        $productId = $this->createProduct('1');

        $store = $this->createStore('701');

        $sale = $this->factory->createSale($store, '2014-01-11 13:45:09', 110.23);
        $this->factory->createSaleProduct(34.12, 3, $productId, $sale);
        $this->factory->createSaleProduct(34.12, 7, $productId, $sale);
        $this->factory->createSaleProduct(34.12, 1, $productId, $sale);
        $this->factory->flush();

        $trialBalanceRepository = $this->getContainer()->get('lighthouse.core.document.repository.trial_balance');

        $storeProductRepository = $this->getContainer()->get('lighthouse.core.document.repository.store_product');

        $storeProductId = $storeProductRepository->getIdByStoreIdAndProductId($store, $productId);
        $prevEndIndex = '0.000';
        $trailBalances = $trialBalanceRepository->findByStoreProductIdAndReasonType(
            $storeProductId,
            SaleProduct::REASON_TYPE
        );
        $this->assertEquals(3, $trailBalances->count(true));
        foreach ($trailBalances as $trailBalance) {
            $this->assertInstanceOf('Lighthouse\\CoreBundle\\Types\\Numeric\\Quantity', $trailBalance->startIndex);
            $this->assertSame($prevEndIndex, $trailBalance->startIndex->toString());
            $prevEndIndex = $trailBalance->endIndex->toString();
            $this->assertNotSame('0.000', $prevEndIndex);
        }
    }

    /**
     * @param float $start
     * @param float $end
     * @param array $expectedSkus
     * @dataProvider findInvoiceByRangeIndexProvider
     */
    public function testFindInvoiceByRangeIndex($start, $end, array $expectedSkus)
    {
        $productId = $this->createProduct('1');
        $store = $this->createStore('701');
        $storeProductRepository = $this->getContainer()->get('lighthouse.core.document.repository.store_product');
        $storeProductId = $storeProductRepository->getIdByStoreIdAndProductId($store, $productId);

        $invoiceId1 = $this->createInvoice(array('sku' => 1, 'acceptanceDate' => '2014-01-12 12:23:12'), $store);
        $this->createInvoiceProduct($invoiceId1, $productId, 5, 10.09, $store);
        $invoiceId2 = $this->createInvoice(array('sku' => 2, 'acceptanceDate' => '2014-01-12 13:23:12'), $store);
        $this->createInvoiceProduct($invoiceId2, $productId, 3, 10.09, $store);
        $invoiceId3 = $this->createInvoice(array('sku' => 3, 'acceptanceDate' => '2014-01-12 14:23:12'), $store);
        $this->createInvoiceProduct($invoiceId3, $productId, 2, 10.09, $store);

        $trialBalanceRepository = $this->getContainer()->get('lighthouse.core.document.repository.trial_balance');
        $numericFactory = $this->getContainer()->get('lighthouse.core.types.numeric.factory');
        $startIndex = $numericFactory->createQuantity($start);
        $endIndex = $numericFactory->createQuantity($end);
        $cursor = $trialBalanceRepository->findByIndexRange(
            InvoiceProduct::REASON_TYPE,
            $storeProductId,
            $startIndex,
            $endIndex
        );
        $this->assertEquals(count($expectedSkus), $cursor->count(true));
        foreach ($expectedSkus as $expectedSku) {
            /* @var TrialBalance $trialBalance */
            $trialBalance = $cursor->getNext();
            $this->assertNotNull($trialBalance);
            $this->assertEquals($expectedSku, $trialBalance->reason->getReasonParent()->sku);
        }
    }

    /**
     * @return array
     */
    public function findInvoiceByRangeIndexProvider()
    {
        return array(
            '0,5 - exact range #1' => array(
                0,
                5,
                array(1)
            ),
            '5,8 - exact range #2' => array(
                5,
                8,
                array(2)
            ),
            '8,10 - exact range #3' => array(
                8,
                10,
                array(3),
            ),
            '0,1 - start/inside #1' => array(
                0,
                1,
                array(1)
            ),
            '1,3 - inside #1' => array(
                1,
                3,
                array(1)
            ),
            '0,6' => array(
                0,
                6,
                array(1,2)
            ),
            '4,6' => array(
                4,
                6,
                array(1,2)
            ),
            '5,6' => array(
                5,
                6,
                array(2)
            ),
            '6,8' => array(
                6,
                8,
                array(2)
            ),
            '10,11' => array(
                10,
                11,
                array()
            ),
            '1,9' => array(
                1,
                9,
                array(1,2,3)
            ),
            '0,11' => array(
                1,
                9,
                array(1,2,3)
            ),
            '11,14' => array(
                11,
                14,
                array()
            )
        );
    }

    /**
     * @param float $start
     * @param float $end
     * @param string $expectedCostOfGoods
     * @dataProvider costOfGoodsCalculateByIndexRangeProvider
     */
    public function testCostOfGoodsCalculateByIndexRange($start, $end, $expectedCostOfGoods)
    {
        $productId = $this->createProduct('1');
        $store = $this->createStore('701');
        $storeProductRepository = $this->getContainer()->get('lighthouse.core.document.repository.store_product');
        $storeProductId = $storeProductRepository->getIdByStoreIdAndProductId($store, $productId);

        $invoiceId1 = $this->createInvoice(array('sku' => 1, 'acceptanceDate' => '2014-01-12 12:23:12'), $store);
        $this->createInvoiceProduct($invoiceId1, $productId, 5, 11.09, $store);
        $invoiceId2 = $this->createInvoice(array('sku' => 2, 'acceptanceDate' => '2014-01-12 13:23:12'), $store);
        $this->createInvoiceProduct($invoiceId2, $productId, 3, 12.13, $store);
        $invoiceId3 = $this->createInvoice(array('sku' => 3, 'acceptanceDate' => '2014-01-12 14:23:12'), $store);
        $this->createInvoiceProduct($invoiceId3, $productId, 2, 10.09, $store);

        $numericFactory = $this->getContainer()->get('lighthouse.core.types.numeric.factory');
        /* @var CostOfGoodCalculator $costOfGoodsCalculator */
        $costOfGoodsCalculator = $this->getContainer()->get('lighthouse.core.document.trial_balance.calculator');
        $startIndex = $numericFactory->createQuantity($start);
        $endIndex = $numericFactory->createQuantity($end);
        $costOfGoods = $costOfGoodsCalculator->calculateByIndexRange($storeProductId, $startIndex, $endIndex);
        $this->assertSame($expectedCostOfGoods, $costOfGoods->toNumber());
    }

    /**
     * @return array
     */
    public function costOfGoodsCalculateByIndexRangeProvider()
    {
        return array(
            '0,5 - exact range #1' => array(
                0,
                5,
                55.45
            ),
            '5,8 - exact range #2' => array(
                5,
                8,
                36.39
            ),
            '8,10 - exact range #3' => array(
                8,
                10,
                20.18,
            ),
            '0,1 - start/inside #1' => array(
                0,
                1,
                11.09
            ),
            '1,3 - inside #1' => array(
                1,
                3,
                22.18
            ),
            '0,6' => array(
                0,
                6,
                67.58
            ),
            '4,6' => array(
                4,
                6,
                23.22
            ),
            '5,6' => array(
                5,
                6,
                12.13
            ),
            '6,8' => array(
                6,
                8,
                24.26
            ),
            '10,11' => array(
                10,
                11,
                0.0
            ),
            '1,9' => array(
                1,
                9,
                90.84
            ),
            '0,11' => array(
                1,
                9,
                90.84
            ),
            '11,14' => array(
                11,
                14,
                0.0
            )
        );
    }

    public function testCostOfGoodsCalculate()
    {
        $store = $this->factory->getStore("1");
        $product1 = $this->createProduct("1");
        $product2 = $this->createProduct("2");

        $invoice1 = $this->createInvoice(array('sku' => '1'), $store);
        $this->createInvoiceProduct($invoice1, $product1, 1.345, 23.77, $store);
        $this->createInvoiceProduct($invoice1, $product1, 2.332, 0.1, $store);
        $this->createInvoiceProduct($invoice1, $product1, 3, 13.3, $store);
        $this->createInvoiceProduct($invoice1, $product1, 4.23, 14, $store);
        $invoice2 = $this->createInvoice(array('sku' => '2'), $store);
        $this->createInvoiceProduct($invoice2, $product1, 5.7, 17.99, $store);
        // Total quantity = 16.607
        $this->factory->flush();

        $sale1 = $this->factory->createSale($store, "-1 hour", 10.533495);
        $saleProduct1 = $this->factory->createSaleProduct(2.333, 2.435, $product1, $sale1);
        $saleProduct2 = $this->factory->createSaleProduct(2.333, 1.32, $product1, $sale1);
        $saleProduct3 = $this->factory->createSaleProduct(2.333, 0.76, $product1, $sale1);

        $sale2 = $this->factory->createSale($store, "now", 6.2991);
        $saleProduct4 = $this->factory->createSaleProduct(2.333, 1, $product1, $sale2);
        $saleProduct5 = $this->factory->createSaleProduct(2.333, 1.7, $product1, $sale2);
        // Total quantity = 7.215

        $this->factory->flush();

        // Calculate CostOfGoods

        /* @var CostOfGoodCalculator $costOfGoodsCalculator */
        $costOfGoodsCalculator = $this->getContainer()->get('lighthouse.core.document.trial_balance.calculator');
        $count = $costOfGoodsCalculator->calculateUnprocessedTrialBalances();
        $this->assertEquals(5, $count);

        $trialBalanceRepository = $this->getContainer()->get("lighthouse.core.document.repository.trial_balance");

        $trialBalanceSaleProduct1 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId((string) $saleProduct1->id, SaleProduct::REASON_TYPE);
        $this->assertEquals('32.08', $trialBalanceSaleProduct1->costOfGoods->toString());
        $trialBalanceSaleProduct2 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId((string) $saleProduct2->id, SaleProduct::REASON_TYPE);
        $this->assertEquals('1.16', $trialBalanceSaleProduct2->costOfGoods->toString());
        $trialBalanceSaleProduct3 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId((string) $saleProduct3->id, SaleProduct::REASON_TYPE);
        $this->assertEquals('10.11', $trialBalanceSaleProduct3->costOfGoods);
        $trialBalanceSaleProduct4 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId((string) $saleProduct4->id, SaleProduct::REASON_TYPE);
        $this->assertEquals('13.30', $trialBalanceSaleProduct4->costOfGoods->toString());
        $trialBalanceSaleProduct5 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId((string) $saleProduct5->id, SaleProduct::REASON_TYPE);
        $this->assertEquals('22.98', $trialBalanceSaleProduct5->costOfGoods->toString());
    }
}