<?php

namespace Tests;

use App\Domain\Employee\EmployeeNotFoundException;
use Dotenv\Dotenv;

class EmployeeRouteTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        $dotenv = Dotenv::createImmutable( __DIR__ . "/../");
        $dotenv->load();
    }

    public function testListEmployees()
    {
        $app = $this->getAppInstance();

        $request = $this->createRequest('GET', $_ENV["APP_BASE_PATH"] . "/employees");
        $response = $app->handle($request);
        $jsonResponse = $this->parseJson($response);

        $this->assertIsArray($jsonResponse);
        $this->assertCount(7, $jsonResponse);

        $found = false;
        foreach ($jsonResponse as $employee) {
            if ($employee["firstName"] === "Mike") {
                $found = true;
                $this->assertEquals("Preston", $employee["lastName"]);
                $this->assertEquals(1, $employee["id"]);
                $this->assertEquals(2, $employee["departmentId"]);
            }
        }
        $this->assertTrue($found);

    }

    public function testGetById()
    {
        $app = $this->getAppInstance();

        $request = $this->createRequest('GET', $_ENV["APP_BASE_PATH"] . "/employee/1");
        $response = $app->handle($request);
        $employee = $this->parseJson($response);

        $this->assertIsArray($employee);
        $this->assertCount(6, $employee);

        $this->assertEquals("Mike", $employee["firstName"]);
        $this->assertEquals("Preston", $employee["lastName"]);
        $this->assertEquals(1, $employee["id"]);
        $this->assertEquals(2, $employee["departmentId"]);
    }

    /**
     * Creation test. Since we want to modify the newly employee, we return also
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
            $_ENV["APP_BASE_PATH"] . "/employee",
            json_decode('{
               "firstName": "Cristina",
               "lastName": "Scabbia",
               "salary": 56895,
               "departmentId": 1      
            }', true),
        );
        $response = $app->handle($request);
        $employee = $this->parseJson($response);

        $this->assertIsArray($employee);
        $this->assertCount(6, $employee);

        $this->assertEquals("Cristina", $employee["firstName"]);
        $this->assertEquals("Scabbia", $employee["lastName"]);
        $this->assertEquals(8, $employee["id"]);
        $this->assertEquals(56895.00, $employee["salary"]);
        $this->assertEquals(1, $employee["departmentId"]);

        return array($employee["id"], $app);
    }

    /**
     * @depends testCreate
     * @throws \Exception
     */
    public function testUpdate(array $data)
    {
        list($empId, $app) = $data;

        // we just update the name
        $request = $this->createJSONRequest(
            'PUT',
            $_ENV["APP_BASE_PATH"] . "/employee/$empId",
            json_decode('{
               "firstName": "Marta"    
            }', true),
        );
        $response = $app->handle($request);
        $response = $this->parseJson($response);

        $this->assertIsBool($response);
        $this->assertTrue($response);

        $request = $this->createRequest('GET', $_ENV["APP_BASE_PATH"] . "/employee/$empId");
        $response = $app->handle($request);
        $employee = $this->parseJson($response);

        $this->assertEquals("Marta", $employee["firstName"]);
        $this->assertEquals("Scabbia", $employee["lastName"]);
        $this->assertEquals(8, $employee["id"]);
        $this->assertEquals(56895.00, $employee["salary"]);
        $this->assertEquals(1, $employee["departmentId"]);
    }

    /**
     * @depends testCreate
     * @throws \Exception
     */
    public function testDelete(array $data)
    {
        list($empId, $app) = $data;

        // we just update the name
        $request = $this->createRequest(
            'DELETE',
            $_ENV["APP_BASE_PATH"] . "/employee/$empId",
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
    public function testExceptionFromMissingEmployee(array $data)
    {
        list($empId, $app) = $data;
        $this->expectException(EmployeeNotFoundException::class);
        $request = $this->createRequest('GET', $_ENV["APP_BASE_PATH"] . "/employee/$empId");
        $app->handle($request);
    }
}