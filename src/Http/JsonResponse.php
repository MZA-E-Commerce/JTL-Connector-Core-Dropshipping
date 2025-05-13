<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Http;

use Doctrine\Common\Annotations\AnnotationException;
use InvalidArgumentException;
use Jawira\CaseConverter\CaseConverterException;
use JMS\Serializer\Exception\LogicException;
use JMS\Serializer\Exception\NotAcceptableException;
use JMS\Serializer\Exception\RuntimeException;
use JMS\Serializer\Exception\UnsupportedFormatException;
use JMS\Serializer\Serializer;
use Jtl\Connector\Core\Definition\Event;
use Jtl\Connector\Core\Event\RpcEvent;
use Jtl\Connector\Core\Exception\DefinitionException;
use Jtl\Connector\Core\Rpc\Method;
use Jtl\Connector\Core\Rpc\RequestPacket;
use Jtl\Connector\Core\Rpc\ResponsePacket;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse as SymfonyJsonResponse;
use TypeError;

class JsonResponse extends SymfonyJsonResponse implements LoggerAwareInterface
{
    protected LoggerInterface $logger;
    protected EventDispatcher $eventDispatcher;
    protected Serializer      $serializer;

    /**
     * JsonResponse constructor.
     *
     * @param EventDispatcher                     $eventDispatcher
     * @param Serializer                          $serializer
     * @param mixed|null                          $data
     * @param int                                 $status
     * @param array<string, string|string[]|null> $headers
     * @param bool                                $json
     *
     * @throws InvalidArgumentException
     * @throws TypeError
     */
    public function __construct(
        EventDispatcher $eventDispatcher,
        Serializer      $serializer,
        mixed           $data = null,
        int             $status = 200,
        array           $headers = [],
        bool            $json = false
    ) {
        $this->logger          = new NullLogger();
        $this->eventDispatcher = $eventDispatcher;
        $this->serializer      = $serializer;
        parent::__construct($data, $status, $headers, $json);
    }

    /**
     * @param RequestPacket  $requestPacket
     * @param ResponsePacket $responsePacket
     *
     * @return $this
     * @throws CaseConverterException
     * @throws DefinitionException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws AnnotationException
     * @throws \JMS\Serializer\Exception\InvalidArgumentException
     * @throws LogicException
     * @throws NotAcceptableException
     * @throws UnsupportedFormatException
     * @throws \Psr\Log\InvalidArgumentException
     */
    public function prepareAndSend(RequestPacket $requestPacket, ResponsePacket $responsePacket): self
    {
        $method       = Method::createFromRpcMethod($requestPacket->getMethod());
        $responseData = $responsePacket->toArray($this->serializer);
        $dataIndex    = $responsePacket->getError() !== null ? 'error' : 'result';
        $event        = new RpcEvent($responseData[$dataIndex] ?? [], $method->getController(), $method->getAction());
        $this->eventDispatcher->dispatch($event, Event::createRpcEventName(Event::AFTER));
        $responseData[$dataIndex] = $event->getData();
        $this->setData($responseData);
        $this->logger->debug($this->content);
        return $this->send();
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}
