<?php


namespace RusinovArtem\LineChunker\test;


use PHPUnit\Framework\TestCase;
use RusinovArtem\LineChunker\LineChanker;

class LineChunkerTest extends TestCase
{
    public function testCanCreateObject()
    {
        $chunker = new LineChanker(__DIR__.'/example.log');
        static::assertInstanceOf(LineChanker::class, $chunker);
    }
}