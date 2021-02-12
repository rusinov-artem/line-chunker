<?php


namespace RusinovArtem\LineChunker;

class LineChunker
{
    protected $fd;
    protected string $file;
    protected int $bufSize = 10000;
    protected string $delimiter = PHP_EOL;
    protected int $delimiterLen = 1;
    protected string $buffer = '';

    public function __construct($file, $bufSize=10000, $delimiter = PHP_EOL)
    {
        $this->file = $file;
        $this->bufSize = $bufSize;
        $this->delimiter = $delimiter;
        $this->delimiterLen = strlen($this->delimiter);
        $this->fd = fopen($file, 'r');
    }

    public function chunk(callable $func){

        $buffer = '';
        $eof = false;
        do
        {
            $r = fread($this->fd, $this->bufSize);
            if($r !== ''){
                $buffer .= $r;
            }else{
                $eof = true;
            }

            $lastEOLat = strrpos( $buffer, $this->delimiter);
            if(false === $lastEOLat){
                if($eof){
                    if(!empty($buffer)) $func($buffer);
                    return;
                }
                else{
                    continue;
                }
            }

            while(false !== ($eolPos = strpos( $buffer, $this->delimiter))){
                $line = substr($buffer,  0, $eolPos );
                $buffer = substr($buffer, $eolPos+$this->delimiterLen);
                if ($func(trim($line)) === false) return;
            }
        } while(true);
    }

    public function __destruct()
    {
        if(is_resource($this->fd)){
            fclose($this->fd);
        }
    }

}