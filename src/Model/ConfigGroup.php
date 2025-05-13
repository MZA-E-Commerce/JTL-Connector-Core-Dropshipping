<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Config group holds several configItems and settings
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class ConfigGroup extends AbstractIdentity
{
    /** @var string Optional internal comment to differantiate config groups by comment name */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('comment')]
    #[Serializer\Accessor(getter: 'getComment', setter: 'setComment')]
    protected string $comment = '';

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('imagePath')]
    #[Serializer\Accessor(getter: 'getImagePath', setter: 'setImagePath')]
    protected string $imagePath = '';

    /** @var int Optional maximum number allowed selections. Default 0 for no maximum limitation. */
    #[Serializer\Type('integer')]
    #[Serializer\SerializedName('maximumSelection')]
    #[Serializer\Accessor(getter: 'getMaximumSelection', setter: 'setMaximumSelection')]
    protected int $maximumSelection = 0;

    /** @var int Optional minimum number required selections. Default 0 for no minimum requirement. */
    #[Serializer\Type('integer')]
    #[Serializer\SerializedName('minimumSelection')]
    #[Serializer\Accessor(getter: 'getMinimumSelection', setter: 'setMinimumSelection')]
    protected int $minimumSelection = 0;

    /** @var int Optional sort order number */
    #[Serializer\Type('integer')]
    #[Serializer\SerializedName('sort')]
    #[Serializer\Accessor(getter: 'getSort', setter: 'setSort')]
    protected int $sort = 0;

    /** @var int Config group item type. 0: Checkbox, 1:Radio, 2, Dropdown, 3: Multiselect */
    #[Serializer\Type('integer')]
    #[Serializer\SerializedName('type')]
    #[Serializer\Accessor(getter: 'getType', setter: 'setType')]
    protected int $type = 0;

    /** @var ConfigGroupI18n[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\ConfigGroupI18n>')]
    #[Serializer\SerializedName('i18ns')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $i18ns = [];

    /**
     * @return string Optional internal comment to differantiate config groups by comment name
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @param string $comment Optional internal comment to differantiate config groups by comment name
     *
     * @return $this
     */
    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return string
     */
    public function getImagePath(): string
    {
        return $this->imagePath;
    }

    /**
     * @param string $imagePath
     *
     * @return $this
     */
    public function setImagePath(string $imagePath): self
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    /**
     * @return int Optional maximum number allowed selections. Default 0 for no maximum limitation.
     */
    public function getMaximumSelection(): int
    {
        return $this->maximumSelection;
    }

    /**
     * @param int $maximumSelection Optional maximum number allowed selections. Default 0 for no maximum limitation.
     *
     * @return $this
     */
    public function setMaximumSelection(int $maximumSelection): self
    {
        $this->maximumSelection = $maximumSelection;

        return $this;
    }

    /**
     * @return int Optional minimum number required selections. Default 0 for no minimum requirement.
     */
    public function getMinimumSelection(): int
    {
        return $this->minimumSelection;
    }

    /**
     * @param int $minimumSelection Optional minimum number required selections.
     *                                  Default 0 for no minimum requirement.
     *
     * @return $this
     */
    public function setMinimumSelection(int $minimumSelection): self
    {
        $this->minimumSelection = $minimumSelection;

        return $this;
    }

    /**
     * @return int Optional sort order number
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * @param int $sort Optional sort order number
     *
     * @return $this
     */
    public function setSort(int $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @return int Config group item type. 0: Checkbox, 1:Radio, 2, Dropdown, 3: Multiselect
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type Config group item type. 0: Checkbox, 1:Radio, 2, Dropdown, 3: Multiselect
     *
     * @return $this
     */
    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param ConfigGroupI18n $i18n
     *
     * @return $this
     */
    public function addI18n(ConfigGroupI18n $i18n): self
    {
        $this->i18ns[] = $i18n;

        return $this;
    }

    /**
     * @return ConfigGroupI18n[]
     */
    public function getI18ns(): array
    {
        return $this->i18ns;
    }

    /**
     * @param ConfigGroupI18n ...$i18ns
     *
     * @return $this
     */
    public function setI18ns(ConfigGroupI18n ...$i18ns): self
    {
        $this->i18ns = $i18ns;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearI18ns(): self
    {
        $this->i18ns = [];

        return $this;
    }
}
