<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DeviceControllerTest extends WebTestCase
{
    /** @var  Application $application */
    protected static $application;
    /**
     * @var \Symfony\Bundle\FrameworkBundle\KernelBrowser
     */
    private static $client;

    protected static function getApplication()
    {
        if (null === self::$application) {
            $kernel = self::bootKernel();
            self::$application = new Application($kernel);
            self::$application->setAutoExit(false);
        }
        return self::$application;
    }

    protected static function runCommand($command)
    {
        $command = sprintf('%s --quiet', $command);
        try {
            return self::getApplication()->run(new StringInput($command));
        } catch (\Exception $e) {
            self::fail("Arreur dde chargement de l'applicaiton");
        }
    }

    protected function setUp(): void
    {
        self::$client = static::createClient();
        self::runCommand('doctrine:database:drop --force');
        self::runCommand('doctrine:database:create');
        self::runCommand('doctrine:schema:create');
        self::runCommand('doctrine:fixtures:load --append --no-interaction --group=test-devices');
        parent::setUp();
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown(): void
    {
        self::runCommand('doctrine:database:drop --force');
        parent::tearDown();
    }

    public function testListAction()
    {


        self::$client->request(Request::METHOD_GET, '/devices?limit=20&offset=5');
        $response = self::$client->getResponse();
        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);
        self::assertIsArray($content);

        self::assertCount(2, $content);
        self::assertArrayHasKey("data", $content, 'Missing key "data" ');
        self::assertArrayHasKey("meta", $content, 'Missing key "meta" ');
        $meta = $content['meta'];
        self::assertArrayHasKey("limit", $meta, 'Missing key "limit" ');
        self::assertEquals(20, $meta['limit'], 'bad "limit" value');
        self::assertArrayHasKey("current_items", $meta, 'Missing key "current_items" ');
        self::assertEquals(20, $meta['current_items'], 'bad "current_items" value');
        self::assertArrayHasKey("total_items", $meta, 'Missing key "total_items"');
        self::assertEquals(40, $meta['total_items'], 'bad "total_items" value');
    }

    public function testCreateAction()
    {

    }

    public function testShowAction()
    {

    }
}
