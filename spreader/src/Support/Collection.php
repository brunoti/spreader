<?php

namespace Indb\Spreader\Support;

use ArrayIterator;

abstract class Collection
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * Get
     *
     * @param string $key Key
     *
     * @return mixed
     */
    public function get($key)
    {
        return isset($this->items[$key]) ? $this->items[$key] : null;
    }

    /**
     * Count
     *
     * @return integer
     */
    public function count()
    {
        return count($this->getIterator());
    }

    public function getIterator()
    {
        return $this->items;
    }


    /**
     * isEmpty
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return $this->count() === 0;
    }

    /**
     * Clear categories
     */
    public function clear()
    {
        $this->items = new ArrayIterator();
    }

    /**
     * Execute a callback over each item.
     *
     * @param  callable  $callback
     * @return $this
     */
    public function each(callable $callback)
    {
        foreach ($this->items as $key => $item) {
            if ($callback($item, $key) === false) {
                break;
            }
        }

        return $this;
    }

    protected function push($item)
    {
        array_push($this->items, $item);
        return $this;
    }

    public function toArray()
    {
        return $this->items;
    }

    /**
     * Chunk the underlying collection array.
     *
     * @param  int   $size
     * @return static
     */
    public function chunk($size)
    {
        $chunks = [];

        foreach (array_chunk($this->items, $size, true) as $chunk) {
            $chunks[] = $chunk;
        }

        return $chunks;
    }
}