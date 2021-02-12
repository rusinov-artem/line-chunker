<?php

use PHPUnit\Framework\TestCase;
use RusinovArtem\LineChunker\LineIterator;

class LineIteratorTest extends TestCase
{
    public function testCanGetAllLines()
    {
        $chunker = new LineIterator(__DIR__.'/example.log');
        $result = [];
        foreach ($chunker as $line){
            $result[] = $line;
        }
        static::assertCount(4, $result);
    }

    public function testWorkingCorrectOnLowBuffer()
    {
        $chunker = new LineIterator(__DIR__.'/example.log', 3);
        $result = [];
        foreach ($chunker as $line){
            $result[] = $line;
        }
        static::assertCount(4, $result);
    }

    public function testOtherDelimiter()
    {
        $chunker = new LineIterator(__DIR__.'/example.log', 3, '<br>');
        $result = [];
        foreach ($chunker as $line){
            $result[] = $line;
        }
        static::assertCount(3, $result);
    }

    public function testGenerator(){
        $chunker = new RusinovArtem\LineChunker\LineChunker(__DIR__.'/example.log', 3, '<br>');
        $result = [];
        foreach ($chunker as $line){
            $result[] = $line;
        }
        static::assertCount(3, $result);
    }

}