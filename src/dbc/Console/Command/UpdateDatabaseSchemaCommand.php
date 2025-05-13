<?php

declare(strict_types=1);

namespace Jtl\Connector\Dbc\Console\Command;

use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateDatabaseSchemaCommand extends AbstractDbManagerCommand
{
    public const
        OPTION_FORCE = 'force';

    /** @var string */
    protected static $defaultName = 'database:update-schema'; // phpcs:ignore

    /**
     * @return void
     * @throws InvalidArgumentException
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Creates/Updates the database schema')
            ->addOption(
                self::OPTION_FORCE,
                null,
                InputOption::VALUE_OPTIONAL,
                'Execute statements directly, instead of returning them to the output.',
                false
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws \Throwable
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $force = $input->getOption(self::OPTION_FORCE) !== false;
        if ($this->dbManager->hasSchemaUpdates()) {
            if ($force) {
                $this->dbManager->updateDatabaseSchema();
                $output->writeln('Schema updated.');
            } else {
                foreach ($this->dbManager->getSchemaUpdates() as $statement) {
                    $output->writeln(\sprintf('%s;', $statement));
                }
            }
        } else {
            $output->writeln('Nothing to update.');
        }

        return self::SUCCESS;
    }
}
