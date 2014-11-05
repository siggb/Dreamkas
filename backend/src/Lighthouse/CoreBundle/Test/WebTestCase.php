<?php

namespace Lighthouse\CoreBundle\Test;

use DateTime;
use Lighthouse\CoreBundle\Document\Product\Type\UnitType;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Test\Client\JsonRequest;
use Lighthouse\CoreBundle\Test\Client\Client;
use Lighthouse\CoreBundle\Test\Factory\CatalogFactory;
use PHPUnit_Framework_ExpectationFailedException;

/**
 * @codeCoverageIgnore
 */
class WebTestCase extends ContainerAwareTestCase
{
    /**
     * @var Client
     */
    protected $client;

    protected function setUp()
    {
        $this->client = static::createClient();
        $this->clearMongoDb();
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->client = null;
    }

    /**
     * @param \stdClass|string $token
     * @param string $method
     * @param string $uri
     * @param array $data
     * @param array $parameters
     * @param array $server
     * @param bool $changeHistory
     * @return array
     * @throws \Exception
     */
    protected function clientJsonRequest(
        $token,
        $method,
        $uri,
        $data = null,
        array $parameters = array(),
        array $server = array(),
        $changeHistory = false
    ) {
        $request = new JsonRequest($uri, $method);

        $request->parameters = $parameters;
        $request->server = $server;
        $request->changeHistory = $changeHistory;

        if ($token) {
            $request->setAccessToken($token);
        }

        $request->setJsonData($data);
        $request->setJsonHeaders();

        return $this->client->jsonRequest($request);
    }

    /**
     * @param string|array $extra
     * @param null|string $subCategoryId
     * @param bool|string $putProductId string id of product to be updated
     * @return string product id
     */
    protected function createProduct($extra = '', $subCategoryId = null, $putProductId = false)
    {
        if ($subCategoryId == null) {
            $subCategoryId = $this->factory()->catalog()->getSubCategory()->id;
        }

        $productData = array(
            'name' => 'Кефир "Веселый Молочник" 1% 950гр',
            'type' => UnitType::TYPE,
            'barcode' => uniqid('', true),
            'purchasePrice' => 3048,
            'vat' => 10,
            'vendor' => 'Вимм-Билль-Данн',
            'vendorCountry' => 'Россия',
            'info' => 'Классный кефирчик, употребляю давно, всем рекомендую для поднятия тонуса',
            'subCategory' => $subCategoryId,
        );

        if (is_array($extra)) {
            $productData = $extra + $productData;
        } else {
            $productData['name'].= $extra;
            $productData['barcode'].= $extra;
        }

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $method = ($putProductId) ? 'PUT' : 'POST';
        $url = '/api/1/products' . (($putProductId) ? '/' . $putProductId : '');
        $request = new JsonRequest($url, $method, $productData);
        $postResponse = $this->client->jsonRequest($request, $accessToken);

        $responseCode = ($putProductId) ? 200 : 201;
        $this->assertResponseCode($responseCode);
        Assert::assertJsonHasPath('id', $postResponse);

        return $postResponse['id'];
    }

    /**
     * @param string $productId
     * @param array $data
     */
    protected function updateProduct($productId, array $data)
    {
        $this->createProduct($data, null, $productId);
    }

    /**
     * @param array $names
     * @param bool $unique
     * @throws \PHPUnit_Framework_AssertionFailedError
     * @return array|string[] sku => id
     */
    protected function createProductsByNames(array $names, $unique = false)
    {
        if ($unique) {
            $names = array_unique($names);
        }
        $products = array();
        $failedNames = array();
        foreach ($names as $name) {
            try {
                $products[$name] = $this->createProductByName($name);
            } catch (\PHPUnit_Framework_AssertionFailedError $e) {
                $failedNames[] = $name;
            }
        }
        if (count($failedNames) > 0) {
            throw new \PHPUnit_Framework_AssertionFailedError(
                sprintf('Failed to create products with following names: %s', implode(', ', $failedNames))
            );
        }
        return $products;
    }

    /**
     * @param string $name
     * @param string $subCategoryId
     * @return string
     */
    protected function createProductByName($name, $subCategoryId = null)
    {
        return $this->createProduct(array('name' => $name, 'barcode' => $name), $subCategoryId);
    }

    /**
     * @param string $productId
     * @param array $barcodes
     */
    protected function updateProductBarcodes($productId, array $barcodes)
    {
        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/products/' . $productId . '/barcodes',
            array(
                'barcodes' => $barcodes,
            )
        );
        $this->assertResponseCode(200);
    }

    /**
     * @param array $catalog
     * @return array
     */
    protected function createCatalog(array $catalog)
    {
        $catalogIds = array();
        foreach ($catalog as $groupName => $categories) {
            $groupId = $this->factory()->catalog()->createGroup($groupName)->id;
            $catalogIds[$groupName] = $groupId;
            foreach ($categories as $categoryName => $subCategories) {
                $categoryId = $this->factory()->catalog()->createCategory($groupId, $categoryName)->id;
                $catalogIds[$categoryName] = $categoryId;
                foreach ($subCategories as $subCategoryName => $void) {
                    $subCategoryId = $this->factory()->catalog()->createSubCategory($categoryId, $subCategoryName)->id;
                    $catalogIds[$subCategoryName] = $subCategoryId;
                }
            }
        }
        return $catalogIds;
    }

    /**
     * @param mixed $json
     * @param array $assertions
     * @param bool  $contains
     */
    protected function performJsonAssertions($json, array $assertions, $contains = false)
    {
        foreach ($assertions as $path => $expected) {
            if (null === $expected) {
                Assert::assertNotJsonHasPath($path, $json);
            } elseif ($contains) {
                Assert::assertJsonPathContains($expected, $path, $json);
            } else {
                Assert::assertJsonPathEquals($expected, $path, $json);
            }
        }
    }

    /**
     * @param string $productId
     * @param array $assertions
     */
    protected function assertProduct($productId, array $assertions)
    {
        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $request = new JsonRequest('/api/1/products/' . $productId);
        $request->setAccessToken($accessToken);

        $productJson = $this->client->jsonRequest($request);

        $this->assertResponseCode(200);

        $this->performJsonAssertions($productJson, $assertions);
    }

    /**
     * @param string $storeId
     * @param string $productId
     * @param array  $assertions
     * @param string $message
     * @throws \PHPUnit_Framework_ExpectationFailedException
     */
    protected function assertStoreProduct($storeId, $productId, array $assertions, $message = '')
    {
        $accessToken = $this->factory()->oauth()->authAsStoreManager($storeId);

        $storeProductJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/products/' . $productId
        );

        $this->assertResponseCode(200);

        try {
            $this->performJsonAssertions($storeProductJson, $assertions);
        } catch (PHPUnit_Framework_ExpectationFailedException $e) {
            $message.= ($message) ? '. ' . $e->getMessage() : $e->getMessage();
            throw new PHPUnit_Framework_ExpectationFailedException(
                $message,
                $e->getComparisonFailure(),
                $e->getPrevious()
            );
        }
    }

    /**
     * @param string $storeId
     * @param string $productId
     * @param int $inventory
     * @param float $lastPurchasePrice
     */
    protected function assertStoreProductTotals($storeId, $productId, $inventory, $lastPurchasePrice = null)
    {
        $assertions = array(
            'inventory' => $inventory,
            'lastPurchasePrice' => $lastPurchasePrice
        );

        $this->assertStoreProduct($storeId, $productId, $assertions);
    }

    /**
     * @param string $name
     * @return string
     */
    protected function createGroup($name = CatalogFactory::DEFAULT_GROUP_NAME)
    {
        return $this->factory()->catalog()->createGroup($name)->id;
    }

    /**
     * @param string $groupId
     * @param string $name
     * @return string
     */
    protected function createCategory($groupId = null, $name = CatalogFactory::DEFAULT_CATEGORY_NAME)
    {
        return $this->factory()->catalog()->createCategory($groupId, $name)->id;
    }

    /**
     * @param string $categoryId
     * @param string $name
     * @return string
     */
    protected function createSubCategory($categoryId = null, $name = CatalogFactory::DEFAULT_SUBCATEGORY_NAME)
    {
        return $this->factory()->catalog()->createSubCategory($categoryId, $name)->id;
    }

    /**
     * @param string $storeId
     * @param string $productId
     * @param array $productData
     */
    public function updateStoreProduct(
        $storeId,
        $productId,
        array $productData = array()
    ) {
        $accessToken = $this->factory()->oauth()->authAsStoreManager($storeId);

        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $storeId . '/products/' . $productId,
            $productData
        );

        $this->assertResponseCode(200);
    }

    /**
     * @param integer $expectedCode
     * @param string $message
     */
    public function assertResponseCode($expectedCode, $message = '')
    {
        Assert::assertResponseCode($expectedCode, $this->client, $message);
    }

    /**
     * @param string $name
     * @param string $value
     * @return mixed
     */
    public function createConfig($name = 'test-config', $value = 'test-config-value')
    {
        $configData = array(
            'name' => $name,
            'value' => $value,
        );

        $accessToken = $this->factory()->oauth()->authAsRole('ROLE_ADMINISTRATOR');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            "POST",
            "/api/1/configs",
            $configData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonPathEquals($name, 'name', $postResponse);
        Assert::assertJsonPathEquals($value, 'value', $postResponse);
        Assert::assertJsonHasPath('id', $postResponse);

        return $postResponse['id'];
    }

    /**
     * @param string $configId
     * @param string $name
     * @param string $value
     * @return string
     */
    public function updateConfig($configId, $name = 'test-config', $value = 'test-config-value')
    {
        $configData = array(
            'name' => $name,
            'value' => $value,
        );

        $accessToken = $this->factory()->oauth()->authAsRole('ROLE_ADMINISTRATOR');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            "PUT",
            "/api/1/configs/" . $configId,
            $configData
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($name, 'name', $postResponse);
        if ($value !== '') {
            Assert::assertJsonPathEquals($value, 'value', $postResponse);
        }
        Assert::assertJsonPathEquals($configId, 'id', $postResponse);

        return $postResponse['id'];
    }

    /**
     * @param $accessToken
     * @param string $storeId
     * @param string $orderId
     * @param string $supplierId
     * @param array $orderProducts
     */
    protected function assertOrder($accessToken, $storeId, $orderId, $supplierId, array $orderProducts)
    {
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/orders/' . $orderId
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($supplierId, 'supplier.id', $response);
        Assert::assertJsonPathEquals(10001, 'number', $response);
        Assert::assertJsonPathCount(count($orderProducts), 'products.*.id', $response);
        foreach ($orderProducts as $orderProduct) {
            Assert::assertJsonPathEquals($orderProduct['quantity'], 'products.*.quantity', $response);
            Assert::assertJsonPathEquals($orderProduct['product'], 'products.*.product.product.id', $response);
        }
    }

    /**
     * @param string $modify
     * @param string $format
     * @return string
     */
    protected function createDate($modify, $format = DateTime::ISO8601)
    {
        $date = new DateTime('now');
        $date->modify($modify);
        return $date->format($format);
    }
}
