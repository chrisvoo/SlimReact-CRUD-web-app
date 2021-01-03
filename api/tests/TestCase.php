<?php
declare(strict_types=1);

namespace Tests;

use App\Application\SlimApp;
use Exception;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Request as SlimRequest;
use Slim\Psr7\Uri;
use PDO;

class TestCase extends PHPUnit_TestCase
{
    /**
     * @return App
     * @throws Exception
     */
    protected function getAppInstance(): App
    {
        $app = (new SlimApp())->getAppInstance();

        $setupTestsSql = file_get_contents(__DIR__ . "/../scripts/setup_tests.sql");
        /**
         * @var PDO $pdo
         */
        $pdo = $app->getContainer()->get(PDO::class);
        $statements = preg_split("/;/", $setupTestsSql);
        foreach ($statements as $statement) {
            if (trim($statement) === "") {
                continue;
            }
            $pdo->exec($statement);
        }

        return $app;
    }

    /**
     * It parses the response as JSON.
     *
     * @param Response $response The response.
     * @return mixed The associative array.
     */
    protected function parseJson(Response $response)
    {
        $payload = (string) $response->getBody();
        return json_decode($payload, true);
    }

    /**
     * @param string $method
     * @param string $path
     * @return Request
     */
    protected function createRequest(
        string $method,
        string $path
    ): Request {
        $uri = new Uri('', '', 8080, $path);
        $handle = fopen('php://temp', 'w+');
        $headers = [
            'HTTP_ACCEPT' => 'application/json',
        ];
        $serverParams = [];
        $cookies = [];
        $stream = (new StreamFactory())->createStreamFromResource($handle);

        $h = new Headers();
        foreach ($headers as $name => $value) {
            $h->addHeader($name, $value);
        }

        return new SlimRequest($method, $uri, $h, $cookies, $serverParams, $stream);
    }

    /**
     * It creates/updates a new entity.
     *
     * @param string $method
     * @param string $path
     * @param array $data The JSON body.
     * @return Request
     */
    protected function createJSONRequest(
        string $method,
        string $path,
        array $data
    ): Request {
        $uri = new Uri('', '', 8080, $path);
        $handle = fopen('php://temp', 'w+');
        $headers = [
            'accept' => 'application/json',
            "Content-type" => "application/json;charset=utf-8"
        ];
        $serverParams = [];
        $cookies = [];
        $stream = (new StreamFactory())->createStreamFromResource($handle);

        $h = new Headers();
        foreach ($headers as $name => $value) {
            $h->addHeader($name, $value);
        }

        $req = new SlimRequest($method, $uri, $h, $cookies, $serverParams, $stream);
        return $req->withParsedBody($data);
    }
}
