<?php

namespace Tests;

use App\Domain\Department\DepartmentNotFoundException;
use Dotenv\Dotenv;

class DepartmentRouteTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        $dotenv = Dotenv::createImmutable( __DIR__ . "/../");
        $dotenv->load();
    }

    public function testListDepartments()
    {
        $app = $this->getAppInstance();

        $request = $this->createRequest('GET', $_ENV["APP_BASE_PATH"] . "/departments");
        $response = $app->handle($request);
        $jsonResponse = $this->parseJson($response);

        $this->assertIsArray($jsonResponse);
        $this->assertCount(4, $jsonResponse);

        $dep = $jsonResponse[0];

        $this->assertEquals("development", $dep["name"]);
        $this->assertEquals("1", $dep["id"]);
    }

    public function testGetById()
    {
        $app = $this->getAppInstance();

        $request = $this->createRequest('GET', $_ENV["APP_BASE_PATH"] . "/department/1");
        $response = $app->handle($request);
        $dep = $this->parseJson($response);

        $this->assertIsArray($dep);
        $this->assertCount(2, $dep);

        $this->assertEquals(1, $dep["id"]);
        $this->assertEquals("development", $dep["name"]);
    }

    /**
     * Creation test. Since we want to modify the newly department, we return also
     * the app, since getAppInstance reset the database every time it gets called.
     *
     * @return array The new employee's ID and the app.
     *
     * @throws \Exception
     */
    public function testCreate(): array
    {
        $app = $this->getAppInstance();

        $request = $this->createJSONRequest(
            'POST',
            $_ENV["APP_BASE_PATH"] . "/department",
            json_decode('{
               "name": "Quality assurance"   
            }', true),
        );
        $response = $app->handle($request);
        $dep = $this->parseJson($response);

        $this->assertIsArray($dep);
        $this->assertCount(2, $dep);

        $this->assertEquals("Quality assurance", $dep["name"]);
        $this->assertEquals(5, $dep["id"]);

        return array($dep["id"], $app);
    }

    /**
     * @depends testCreate
     * @throws \Exception
     */
    public function testUpdate(array $data)
    {
        list($depId, $app) = $data;

        // we just update the name
        $request = $this->createJSONRequest(
            'PUT',
            $_ENV["APP_BASE_PATH"] . "/department/$depId",
            json_decode('{
               "name": "Customer happiness"    
            }', true),
        );
        $response = $app->handle($request);
        $response = $this->parseJson($response);

        $this->assertIsBool($response);
        $this->assertTrue($response);

        $request = $this->createRequest('GET', $_ENV["APP_BASE_PATH"] . "/department/$depId");
        $response = $app->handle($request);
        $dep = $this->parseJson($response);

        $this->assertEquals("Customer happiness", $dep["name"]);
    }

    /**
     * @depends testCreate
     * @throws \Exception
     */
    public function testDelete(array $data)
    {
        list($depId, $app) = $data;

        // we just update the name
        $request = $this->createRequest(
            'DELETE',
            $_ENV["APP_BASE_PATH"] . "/department/$depId",
        );
        $response = $app->handle($request);
        $response = $this->parseJson($response);

        $this->assertIsBool($response);
        $this->assertTrue($response);
    }

    /**
     * @depends testCreate
     * @param array $data
     */
    public function testExceptionFromMissingDepartment(array $data)
    {
        list($depId, $app) = $data;
        $this->expectException(DepartmentNotFoundException::class);
        $request = $this->createRequest('GET', $_ENV["APP_BASE_PATH"] . "/department/$depId");
        $app->handle($request);
    }
}