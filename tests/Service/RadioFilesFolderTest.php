<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\Radio;
use App\Service\RadioFilesFolder;
use PHPUnit\Framework\TestCase;
use stdClass;

class RadioFilesFolderTest extends TestCase
{
    private string $baseFolder;
    private RadioFilesFolder $radioFilesFolder;

    protected function setUp(): void
    {
        $this->baseFolder = sys_get_temp_dir() . '/radio_test';
        mkdir($this->baseFolder, 0777, true);

        $this->radioFilesFolder = new RadioFilesFolder($this->baseFolder);
    }

    protected function tearDown(): void
    {
        array_map('unlink', glob($this->baseFolder . '/*'));
        rmdir($this->baseFolder);
    }

    public function testGetJsonFiles(): void
    {
        $radio = $this->createMock(Radio::class);
        $radio->method('getFolderName')->willReturn('radio1');

        $radioFolder = $this->baseFolder . '/radio1';
        mkdir($radioFolder, 0777, true);
        file_put_contents($radioFolder . '/file1.json', '{}');
        file_put_contents($radioFolder . '/file2.txt', 'text content');

        $files = iterator_to_array($this->radioFilesFolder->getJsonFiles($radio));

        $this->assertCount(1, $files);
        $this->assertEquals('file1.json', $files[0]);
    }

    public function testGetJson(): void
    {
        $radio = $this->createMock(Radio::class);
        $radio->method('getFolderName')->willReturn('radio1');

        $radioFolder = $this->baseFolder . '/radio1';
        mkdir($radioFolder, 0777, true);
        $filePath = $radioFolder . '/file1.json';
        file_put_contents($filePath, '{"key": "value"}');

        $json = $this->radioFilesFolder->getJson($radio, 'file1.json');

        $this->assertInstanceOf(stdClass::class, $json);
        $this->assertEquals('value', $json->key);
    }

    public function testGetJsonReturnsNullForNonExistentFile(): void
    {
        $radio = $this->createMock(Radio::class);
        $radio->method('getFolderName')->willReturn('radio1');

        $json = $this->radioFilesFolder->getJson($radio, 'nonexistent.json');

        $this->assertNull($json);
    }

    public function testGetWavFilePath(): void
    {
        $radio = $this->createMock(Radio::class);
        $radio->method('getFolderName')->willReturn('radio1');

        $filePath = $this->radioFilesFolder->getWavFilePath($radio, 'file1.wav');

        $this->assertEquals($this->baseFolder . '/radio1/file1.wav', $filePath);
    }
}