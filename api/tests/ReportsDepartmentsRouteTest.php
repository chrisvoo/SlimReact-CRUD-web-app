<?php

namespace Tests;

use Dotenv\Dotenv;

class ReportsDepartmentsRouteTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        $dotenv = Dotenv::createImmutable( __DIR__ . "/../");
        $dotenv->load();
    }

    public function testHighestSalaries()
    {
        $app = $this->getAppInstance();

        $request = $this->createRequest('GET', $_ENV["APP_BASE_PATH"] . "/report/highest_salaries");
        $response = $app->handle($request);
        $jsonResponse = $this->parseJson($response);

        $this->assertIsArray($jsonResponse);
        $this->assertCount(4, $jsonResponse);

        foreach ($jsonResponse as $row) {
            switch ($row["name"]) {
                case "development":
                    $this->assertEquals(71000, $row["num_employees"]);
                    break;
                case "marketing":
                    $this->assertEquals(61500, $row["num_employees"]);
                    break;
                case "management":
                    $this->assertEquals(0, $row["num_employees"]);
                    break;
                case "sales":
                    $this->assertEquals(42356.23046875, $row["num_employees"]);
                    break;
                default:
                    $this->fail("Unexpected record! ". $row["name"] . " - " . $row["num_employees"]);
            }
        }
    }

    public function testExpensiveDepartments()
    {
        $app = $this->getAppInstance();

        $request = $this->createRequest('GET', $_ENV["APP_BASE_PATH"] . "/report/expensive_departments");
        $response = $app->handle($request);
        $jsonResponse = $this->parseJson($response);

        $this->assertIsArray($jsonResponse);
        $this->assertCount(1, $jsonResponse);

        $result = $jsonResponse[0];

        $this->assertEquals("development", $result["name"]);
        $this->assertEquals(3, $result["num_employees"]);
    }
}