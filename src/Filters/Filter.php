<?php

namespace Segura\SDK\Common\Filters;

class Filter
{
    protected $order;
    protected $orderDirection;
    protected $limit;
    protected $offset;
    protected $wheres;

    public static function Factory()
    {
        return new Filter();
    }

    /**
     * @return mixed
     */
    public function getOrderDirection()
    {
        return $this->orderDirection;
    }

    /**
     * @param mixed $orderDirection
     *
     * @return Filter
     */
    public function setOrderDirection($orderDirection)
    {
        $this->orderDirection = $orderDirection;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param string $order Column to order by
     * @param string $orderDirection ASC|DESC
     *
     * @return Filter
     */
    public function setOrder($order, $orderDirection = null)
    {
        $this->order = $order;
        if ($orderDirection) {
            $this->setOrderDirection($orderDirection);
        }
        return $this;
    }

    /**
     * @param $column
     * @param $value
     * @param string $condition
     *
     * @return $this
     */
    public function addWhere($column, $value, $condition = '=')
    {
        $this->wheres[] = new FilterCondition($column, $value, $condition);
        return $this;
    }

    public function getHeaderJson()
    {
        return json_encode($this->getHeaders());
    }

    public function getHeaders()
    {
        return array_filter([
            'limit' => $this->getLimit(),
            'offset' => $this->getOffset(),
            'wheres' => $this->getWheres(),
            'order' => array_filter([
                'column' => $this->getOrder(),
                'direction' => $this->getOrderDirection(),
            ]),
        ]);
    }

    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param mixed $limit
     *
     * @return Filter
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param mixed $offset
     *
     * @return Filter
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWheres()
    {
        return $this->wheres;
    }

    /**
     * @param mixed $wheres
     *
     * @return Filter
     */
    public function setWheres($wheres)
    {
        $this->wheres = $wheres;
        return $this;
    }
}
