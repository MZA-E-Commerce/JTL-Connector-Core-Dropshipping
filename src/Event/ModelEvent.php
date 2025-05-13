<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Event;

use Jtl\Connector\Core\Model\AbstractModel;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class ModelAfterActionEvent
 *
 * @package Jtl\Connector\Core\Event
 */
class ModelEvent extends Event
{
    protected AbstractModel $model;

    /**
     * ModelAfterActionEvent constructor.
     *
     * @param AbstractModel $model
     */
    public function __construct(AbstractModel $model)
    {
        $this->model = $model;
    }

    /**
     * @return AbstractModel
     */
    public function getModel(): AbstractModel
    {
        return $this->model;
    }
}
