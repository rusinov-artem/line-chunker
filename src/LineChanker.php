<?php


namespace RusinovArtem\LineChunker;


class LineChanker
{
    protected $fd;
    protected $file;
    protected $bufSize = 10000;

    public function __construct($file, $bufSize=10000)
    {
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

            $lastEOLat = strrpos( $buffer, PHP_EOL);
            if(false === $lastEOLat){
                if($eof){
                    $func(trim($buffer));
                    return;
                }
                else{
                    continue;
                }
            }

            while(false !== ($eolPos = strpos( $buffer, PHP_EOL))){
                $line = substr($buffer,  0, $eolPos );
                $buffer = substr($buffer, $eolPos+1);
                if ($func(trim($line)) === false) return ;
            }
        }
        while(true);
    }

    public function __destruct()
    {
        fclose($this->fd);
    }
}