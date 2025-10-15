<?php

namespace BlankFramework\Application;

use BlankFramework\ApplicationInterface\ApplicationInterface;
use BlankFramework\RoutingInterfaces\SimpleRouterInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Log\LoggerInterface;

class Application implements ApplicationInterface
{
    public function __construct(
        private SimpleRouterInterface $router,
        private LoggerInterface $logger,
    ) {
    }

    public function run(RequestInterface $request): void
    {
        try {
            $route = $this->router->routeRequest($request);
            $response = $route->handleRequest($request);
            $this->sendResponse($response);
        } catch (\Throwable $throwable) {
            $this->logger->error(
                'Exception occurred while routing request',
                [
                    'errorMessage' => $throwable->getMessage(),
                    'errorCode' => $throwable->getCode(),
                ]
            );
        }
    }

    private function sendResponse(ResponseInterface $response): void
    {
        $this->setHeaders($response->getHeaders());
        $this->setStatusCode($response->getStatusCode());
        $this->sendBody($response->getBody());
    }

    /**
     * @param array<string, string[]> $headers
     */
    private function setHeaders(array $headers): void
    {
        foreach ($headers as $header => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $header, $value), false);
            }
        }
    }

    private function setStatusCode(int $statusCode): void
    {
        http_response_code($statusCode);
    }

    private function sendBody(StreamInterface $body): void
    {
        echo $body->getContents();
    }
}
