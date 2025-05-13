<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Logger\Handler;

use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\FormattableHandlerInterface;
use Monolog\Handler\Handler;
use Monolog\Handler\HandlerInterface;
use Monolog\LogRecord;

class ChunkedHandler extends Handler implements FormattableHandlerInterface
{
    public const MAX_LOG_ENTRY_LENGTH = 31320;
    private HandlerInterface $nextHandler;
    private int $chunkSize = self::MAX_LOG_ENTRY_LENGTH;

    /**
     * @param HandlerInterface $nextHandler
     * @param int|null         $chunkSize
     */
    public function __construct(HandlerInterface $nextHandler, ?int $chunkSize = null)
    {
        $this->nextHandler = $nextHandler;
        if ($chunkSize !== null) {
            $this->chunkSize = $chunkSize;
        }
    }

    /**
     * @inheritDoc
     */
    public function isHandling($record): bool
    {
        return $this->nextHandler->isHandling($record);
    }

    /**
     * @param LogRecord|array<mixed> $record
     *
     * @return bool
     */
    public function handle(LogRecord|array $record): bool
    {
        // false means continue to bubble
        $return = false;

        $useArray = false;

        if (!\is_array($record) && \class_exists(LogRecord::class) && $record instanceof LogRecord) {
            $message = $record->message;
            /** @var array<mixed> $extra */
            $extra = $record->extra;
        } else {
            $message = $record['message'];
            /** @var array<mixed> $extra */
            $extra    = $record['extra'];
            $useArray = true;
        }
        if ($this->chunkSize > 0 && \is_string($message) && \strlen($message) > $this->chunkSize) {
            $chunks   = \str_split($message, $this->chunkSize);
            $total    = \count($chunks);
            $recordId = \md5($message);
            $extra    = \array_merge($extra, ['recordId' => $recordId]);
            foreach ($chunks as $key => $chunk) {
                $message = \sprintf("(part %d/%d) %s", $key, $total, $chunk);

                if ($useArray) {
                    $newRecord = [
                        'level'    => $record['level'],
                        'context'  => $record['context'],
                        'channel'  => $record['channel'],
                        'datetime' => $record['datetime'],
                        'extra'    => $extra,
                        'message'  => $message,
                    ];
                } else {
                    /** @var LogRecord $record */
                    $newRecord = new LogRecord(
                        $record->datetime,
                        $record->channel,
                        $record->level,
                        $message,
                        $record->context,
                        $extra,
                    );
                }

                /** @var LogRecord $newRecord */ // to force phpstan to shut up!
                $return = $this->nextHandler->handle($newRecord) ?: $return;
            }

            return $return;
        }
        /** @var LogRecord $record */ // to force phpstan to shut up!

        return $this->nextHandler->handle($record);
    }

    /**
     * @param FormatterInterface $formatter
     *
     * @return HandlerInterface
     * @throws \UnexpectedValueException
     */
    public function setFormatter(FormatterInterface $formatter): HandlerInterface
    {
        if (
            $this->nextHandler instanceof FormattableHandlerInterface
            || \method_exists($this->nextHandler, 'setFormatter')
        ) {
            return $this->nextHandler->setFormatter($formatter);
        }
        throw new \UnexpectedValueException(
            'The nested handler of type ' . \get_class($this->nextHandler) . ' does not support formatters.'
        );
    }

    /**
     * @return FormatterInterface
     * @throws \UnexpectedValueException
     */
    public function getFormatter(): FormatterInterface
    {
        if (
            $this->nextHandler instanceof FormattableHandlerInterface
            || \method_exists($this->nextHandler, 'getFormatter')
        ) {
            return $this->nextHandler->getFormatter();
        }
        throw new \UnexpectedValueException(
            'The nested handler of type ' . \get_class($this->nextHandler) . ' does not support formatters.'
        );
    }
}
