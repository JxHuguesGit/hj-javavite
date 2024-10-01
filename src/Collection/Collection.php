<?php
namespace src\Collection;

use src\Constant\ConstantConstant;
use src\Exception\KeyHasUseException;

class Collection implements \Iterator
{
    protected $items = [];
    protected $index = 0;

    public function __toString(): string
    {
        $str = '';
        $this->rewind();
        while ($this->valid()) {
            $objEvent = $this->current();
            $str .= $objEvent->__toString().ConstantConstant::CST_EOL;
            $this->next();
        }
        return $str;
    }

    public function addItem($obj, $key=null): self
    {
        if ($key==null) {
            array_push($this->items, $obj);
        } elseif (isset($this->items[$key])) {
            throw new KeyHasUseException($key);
        } else {
            $this->items[$key] = $obj;
        }
        return $this;
    }

    public function deleteItem($key): void
    {
        if (isset($this->items[$key])) {
            unset($this->items[$key]);
        } else {
            throw new KeyInvalidException($key);
        }
    }

    public function deleteLast(): void
    {
        $this->deleteItem($this->length()-1);
    }

    public function getItem($key)
    {
        if (isset($this->items[$key])) {
            return $this->items[$key];
        } else {
            throw new KeyInvalidException($key);
        }
    }

    public function keys(): array
    {
        return array_keys($this->items);
    }

    public function length(): int
    {
        return count($this->items);
    }

    public function keyExists($key): bool
    {
        return isset($this->items[$key]);
    }

    public function slice(int $offset, int $length): void
    {
        $this->items = array_slice($this->items, $offset, $length);
    }

    public function rewind(): self
    {
        $this->index = 0;
        return $this;
    }

    public function valid(): bool
    {
        return isset($this->items[$this->index]);
    }

    public function current(): mixed
    {
        return $this->items[$this->index];
    }

    public function next(): void
    {
        ++$this->index;
    }

    public function key(): int
    {
        return $this->index;
    }

    public function empty(): void
    {
        $this->items = [];
        $this->index = 0;
    }

}
