<?php


namespace RusinovArtem\LineChunker;


class LineChunker
{
    protected $fd;
    protected $file;
    protected $bufSize = 10000;
    protected $delimiter = PHP_EOL;
    protected $delimiterLen = 1;

    public function __construct($file, $bufSize=10000, $delimiter = PHP_EOL)
    {
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
                    $func(trim($buffer));
                    break;
                }
                else{
                    continue;
                }
            }

            while(false !== ($eolPos = strpos( $buffer, $this->delimiter))){
                $line = substr($buffer,  0, $eolPos );
                $buffer = substr($buffer, $eolPos+$this->delimiterLen);
                if ($func(trim($line)) === false) break ;
            }
        } while(true);
    }

    public function __destruct()
    {
        fclose($this->fd);
    }
}