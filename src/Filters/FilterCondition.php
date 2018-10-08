<?php

namespace Segura\SDK\Common\Filters;

use Segura\SDK\Common\Exceptions\FilterConditionNotFoundException;

class FilterCondition implements \JsonSerializable
{
    const CONDITION_EQUAL                 = '=';
    const CONDITION_NOT_EQUAL             = '!=';
    const CONDITION_GREATER_THAN          = '>';
    const CONDITION_LESS_THAN             = '<';
    const CONDITION_GREATER_THAN_OR_EQUAL = '>=';
    const CONDITION_LESS_THAN_OR_EQUAL    = '<=';
    const CONDITION_LIKE                  = 'LIKE';
    const CONDITION_NOT_LIKE              = 'NOT LIKE';
    const CONDITION_IN                    = 'IN';
    const CONDITION_NOT_IN                = 'NOT IN';

    protected $column;
    protected $value;
    protected $condition;

    public function __construct($column, $value, $condition = self::CONDITION_EQUAL)
    {
        $this
            ->setColumn($column)
            ->setValue($value)
            ->setCondition($condition);
    }

    /**
     * @return mixed
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @param mixed $column
     *
     * @return FilterCondition
     */
    public function setColumn($column)
    {
        $this->column = $column;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     *
     * @return FilterCondition
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getCondition(): string
    {
        return $this->condition;
    }

    /**
     * @param string $condition
     *
     * @throws FilterConditionNotFoundException
     *
     * @return FilterCondition
     */
    public function setCondition(string $condition): FilterCondition
    {
        switch ($condition) {
            case self::CONDITION_EQUAL:
            case self::CONDITION_NOT_EQUAL:
            case self::CONDITION_GREATER_THAN:
            case self::CONDITION_LESS_THAN:
            case self::CONDITION_GREATER_THAN_OR_EQUAL:
            case self::CONDITION_LESS_THAN_OR_EQUAL:
            case self::CONDITION_LIKE:
            case self::CONDITION_NOT_LIKE:
            case self::CONDITION_IN:
            case self::CONDITION_NOT_IN:
                break;
            default:
                throw new FilterConditionNotFoundException("Unsupported condition: '{$condition}");
        }
        $this->condition = $condition;
        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'column' => $this->getColumn(),
            'condition' => $this->getCondition(),
            'value' => $this->getValue(),
        ];
    }
}
