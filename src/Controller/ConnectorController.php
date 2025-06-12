<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Controller;

use Jawira\CaseConverter\CaseConverterException;
use Jtl\Connector\Core\Application\Application;
use Jtl\Connector\Core\Authentication\TokenValidatorInterface;
use Jtl\Connector\Core\Checksum\ChecksumInterface;
use Jtl\Connector\Core\Connector\ConnectorInterface;
use Jtl\Connector\Core\Definition\Model;
use Jtl\Connector\Core\Definition\RelationType;
use Jtl\Connector\Core\Exception\AuthenticationException;
use Jtl\Connector\Core\Exception\DefinitionException;
use Jtl\Connector\Core\Exception\JsonException as CoreJsonException;
use Jtl\Connector\Core\Exception\MissingRequirementException;
use Jtl\Connector\Core\Linker\ChecksumLinker;
use Jtl\Connector\Core\Linker\IdentityLinker;
use Jtl\Connector\Core\Logger\Processor\WarningProcessor;
use Jtl\Connector\Core\Model\Ack;
use Jtl\Connector\Core\Model\Authentication;
use Jtl\Connector\Core\Model\ConnectorIdentification;
use Jtl\Connector\Core\Model\ConnectorServerInfo;
use Jtl\Connector\Core\Model\Features;
use Jtl\Connector\Core\Model\Identities;
use Jtl\Connector\Core\Model\Session;
use Jtl\Connector\Core\Serializer\Json;
use Jtl\Connector\Core\System\Check;
use Jtl\Connector\Core\Utilities\Str;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Base Config Controller
 *
 * @access public
 */
class ConnectorController implements LoggerAwareInterface
{
    protected string                   $featuresPath;
    protected ChecksumLinker           $checksumLinker;
    protected IdentityLinker           $linker;
    protected LoggerInterface          $logger;
    protected \SessionHandlerInterface $sessionHandler;
    protected TokenValidatorInterface  $tokenValidator;

    /**
     * ConnectorController constructor.
     *
     * @param string                   $featuresPath
     * @param ChecksumLinker           $checksumLinker
     * @param IdentityLinker           $linker
     * @param \SessionHandlerInterface $sessionHandler
     * @param TokenValidatorInterface  $tokenValidator
     */
    public function __construct(
        string                   $featuresPath,
        ChecksumLinker           $checksumLinker,
        IdentityLinker           $linker,
        \SessionHandlerInterface $sessionHandler,
        TokenValidatorInterface  $tokenValidator,
    ) {
        $this->featuresPath   = $featuresPath;
        $this->checksumLinker = $checksumLinker;
        $this->linker         = $linker;
        $this->sessionHandler = $sessionHandler;
        $this->tokenValidator = $tokenValidator;
        $this->logger         = new NullLogger();
    }


    /**
     * @return bool
     * @throws MissingRequirementException
     */
    public function init(): bool
    {
        Check::run();

        return true;
    }

    /**
     * @return Features
     * @throws CoreJsonException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \JsonException
     */
    public function features(): Features
    {
        $features = $this->fetchFeaturesData();

        $entities = [];
        if (isset($features['entities']) && \is_array($features['entities'])) {
            $entities = $features['entities'];
        }

        $flags = [];
        if (isset($features['flags']) && \is_array($features['flags'])) {
            $flags = $features['flags'];
        }

        return Features::create($entities, $flags);
    }

    /**
     * @return array<mixed>
     * @throws CoreJsonException
     * @throws \InvalidArgumentException
     * @throws \JsonException
     * @throws \RuntimeException
     */
    protected function fetchFeaturesData(): array
    {
        if (($fileContent = \file_get_contents($this->featuresPath)) === false) {
            throw new \RuntimeException('$fileContent must not be false.');
        }
        if (!\is_array(($jsonDecode = Json::decode($fileContent, true)))) {
            throw new \RuntimeException('JsonDecode must return an array.');
        }

        return $jsonDecode;
    }

    /**
     * @param Ack $ack
     *
     * @return bool
     * @throws CaseConverterException
     * @throws DefinitionException
     * @throws \InvalidArgumentException
     */
    public function ack(Ack $ack): bool
    {
        foreach ($ack->getIdentities() as $modelName => $identities) {
            $normalizedName = Str::toPascalCase($modelName);

            file_put_contents(Application::LOG_DIRECTORY . '/names.log', $normalizedName . PHP_EOL . PHP_EOL, FILE_APPEND);

            if (!Model::isModel($normalizedName)) {
                $this->logger->warning(
                    'ACK: Unknown core entity ({name})! Skipping related ack\'s...',
                    ['name' => $normalizedName, WarningProcessor::SEND_TO_WAWI => true]
                );
                continue;
            }
            if ($identities !== null) {
                foreach ($identities as $identity) {
                    $this->linker->save($identity->getEndpoint(), $identity->getHost(), $normalizedName);
                }
            }
        }

        // Checksum linking
        foreach ($ack->getChecksums() as $checksum) {
            if (($checksum instanceof ChecksumInterface) && !$this->checksumLinker->save($checksum)) {
                $context = [
                    'endpoint'                      => $checksum->getForeignKey()->getEndpoint(),
                    'host'                          => $checksum->getForeignKey()->getHost(),
                    'type'                          => $checksum->getType(),
                    WarningProcessor::SEND_TO_WAWI  => true
                ];

                $this->logger->warning(
                    'Could not save checksum for endpoint ({endpoint}), host ({host}) and type ({type})',
                    $context
                );
            }
        }

        return true;
    }

    /**
     * @param Authentication $auth
     *
     * @return Session
     * @throws AuthenticationException
     * @throws \RuntimeException
     * @throws InvalidArgumentException
     */
    public function auth(Authentication $auth): Session
    {
        if (empty($auth->getToken())) {
            throw AuthenticationException::tokenMissing();
        }

        if ($this->tokenValidator->validate($auth->getToken()) === false) {
            $context = [
                'token' => $auth->getToken(),
                'ip'    => $_SERVER['REMOTE_ADDR'],
            ];
            $this->logger->warning('Unauthorized access with token ({token}) from ip ({ip})', $context);
            throw AuthenticationException::failed();
        }

        $sessionId = \session_id();
        if ($sessionId === false) {
            throw new \RuntimeException('sessionId must not be false.');
        }

        return (new Session())
            ->setSessionId($sessionId)
            ->setLifetime((int)\ini_get('session.gc_maxlifetime'));
    }

    /**
     * @param ConnectorInterface $endpointConnector
     *
     * @return ConnectorIdentification
     */
    public function identify(ConnectorInterface $endpointConnector): ConnectorIdentification
    {
        $returnBytes = static function ($data): int {
            $data = \trim($data);
            $len  = \strlen($data);
            if ($data === '-1') {
                return -1;
            }
            $value = (int)\substr($data, 0, $len - 1);
            $unit  = \strtolower(\substr($data, $len - 1));
            switch ($unit) {
                case 'g':
                    $value *= 1024;
                    break;
                case 'k':
                    $value /= 1024;
                    break;
            }

            return (int)\round($value);
        };

        $serverInfo = (new ConnectorServerInfo())
            ->setMemoryLimit($returnBytes(\ini_get('memory_limit')))
            ->setExecutionTime((int)\ini_get('max_execution_time'))
            ->setPostMaxSize($returnBytes(\ini_get('post_max_size')))
            ->setUploadMaxFilesize($returnBytes(\ini_get('upload_max_filesize')));

        return (new ConnectorIdentification())
            ->setEndpointVersion($endpointConnector->getEndpointVersion())
            ->setPlatformName($endpointConnector->getPlatformName())
            ->setPlatformVersion($endpointConnector->getPlatformVersion())
            ->setProtocolVersion(Application::PROTOCOL_VERSION)
            ->setServerInfo($serverInfo);
    }

    /**
     * @return bool
     */
    public function finish(): bool
    {
        if (\session_status() === \PHP_SESSION_ACTIVE) {
            \session_destroy();
        }

        return true;
    }

    /**
     * @param Identities|null $identities
     *
     * @return bool
     * @throws DefinitionException
     * @throws \InvalidArgumentException
     */
    public function clear(?Identities $identities = null): bool
    {
        if ($identities !== null && \count($identities->getIdentities()) > 0) {
            $identitiesArr = $identities->getIdentities();
            foreach ($identitiesArr as $relationType => $relationIdentities) {
                if (empty($relationIdentities)) {
                    $this->linker->clear(RelationType::getIdentityType($relationType));
                } else {
                    if (\is_array($relationIdentities)) {
                        foreach ($relationIdentities as $identity) {
                            $endpointId = empty($identity->getEndpoint()) ? null : $identity->getEndpoint();
                            $this->linker->delete(
                                RelationType::getModelName($relationType),
                                $endpointId,
                                $identity->getHost()
                            );
                        }
                    }
                }
            }
        } else {
            $this->linker->clear();
        }

        return true;
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