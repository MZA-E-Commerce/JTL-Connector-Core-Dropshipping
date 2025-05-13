<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Config;

use Noodlehaus\ConfigInterface;

interface ConfigSchemaConfigInterface extends ConfigInterface
{
    /**
     * @return ConfigSchema
     */
    public function getConfigSchema(): ConfigSchema;

    /**
     * @param ConfigSchema $configSchema
     *
     * @return $this
     */
    public function setConfigSchema(ConfigSchema $configSchema): self;
}
