<?php


namespace RusinovArtem\LineChunker;


class LineIterator implements \Iterator
{

    protected $fd;
    protected string $file;
    protected int $bufSize = 10000;
    protected string $delimiter = PHP_EOL;
    protected int $delimiterLen = 1;

    protected string $buffer = '';
    protected string $current = '';
    protected int $lineNumber = 0;
    protected bool $valid = true;

    public function __construct($file, $bufSize=10000, $delimiter = PHP_EOL)
    {
        $this->file = $file;
        $this->bufSize = $bufSize;
        $this->delimiter = $delimiter;
        $this->delimiterLen = strlen($this->delimiter);
        $this->fd = fopen($file, 'r');
    }


    public function current()
    {
        return $this->current;
    }

    protected function fillBuffer(): int
    {
        while(!feof($this->fd)){
            $this->buffer .= fread($this->fd, $this->bufSize);
            $eolPos = strpos( $this->buffer, $this->delimiter);
            if(false !== $eolPos) return $eolPos;
        }
        return strlen($this->buffer);
    }

    public function next()
    {
        if(empty($this->buffer)){
            $this->buffer .= fread($this->fd, $this->bufSize);
        }

        $eolPos = strpos( $this->buffer, $this->delimiter);

        if(false === $eolPos ){
            $eolPos = $this->fillBuffer();
        }

        if(empty($this->buffer)) $this->valid = false;
        $this->current = substr($this->buffer,  0, $eolPos );
        $this->buffer = substr($this->buffer, $eolPos+$this->delimiterLen);
    }

    public function key()
    {
        return $this->lineNumber;
    }

    public function valid()
    {
        return $this->valid;
    }

    public function rewind()
    {
        if(is_resource($this->fd)){
            fclose($this->fd);
        }

        $this->fd = fopen($this->file, 'r');

        if (empty($this->buffer)) {
            $this->next();
        }
    }

    public function __destruct()
    {
        if(is_resource($this->fd)){
            fclose($this->fd);
        }
    }
}