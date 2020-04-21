<?php

namespace RusinovArtem\LineChunker\test;

use PHPUnit\Framework\TestCase;
use RusinovArtem\LineChunker\LineChunker;

class LineChunkerTest extends TestCase
{
    public function testCanCreateObject()
    {
        $chunker = new LineChunker(__DIR__.'/example.log');
        static::assertInstanceOf(LineChunker::class, $chunker);
    }
    public function testCanGetAllLines()
    {
        $chunker = new LineChunker(__DIR__.'/example.log');
        $result = [];
        $chunker->chunk(function ($line)use(&$result){
            $result[] = $line;
        });
        static::assertEquals(3, count($result));
    }
    public function testWorkingCorrectOnLowBuffer()
    {
        $chunker = new LineChunker(__DIR__.'/example.log', 3);
        $result = [];
        $chunker->chunk(function ($line)use(&$result){
            $result[] = $line;
        });
        static::assertEquals(3, count($result));
    }
    public function testOtherDelimiter()
    {
        $chunker = new LineChunker(__DIR__.'/example.log', 3, '<br>');
        $result = [];
        $chunker->chunk(function ($line)use(&$result){
            $result[] = $line;
        });
        static::assertEquals(4, count($result));
    }
}