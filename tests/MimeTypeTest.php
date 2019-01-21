<?php
declare(strict_types=1);

namespace xobotyi\MimeType;

use PHPUnit\Framework\TestCase;

final class MimeTypeTest extends TestCase
{
    public function testIsSupported() {
        $this->assertTrue(MimeType::isSupported("text/plain"));
        $this->assertFalse(MimeType::isSupported("text/plain0"));
    }

    public function testIsSupportedExtension() {
        $this->assertTrue(MimeType::isSupportedExtension("txt"));
        $this->assertTrue(MimeType::isSupportedExtension("webp"));
        $this->assertFalse(MimeType::isSupportedExtension("text/plain0"));
    }

    public function testGetInfo() {
        $this->assertEquals(
            MimeType::getInfo("text/plain"),
            [
                'compressible' => true,
                'source'       => 'iana',
                'extensions'   => ['txt', 'text', 'conf', 'def', 'list', 'log', 'in', 'ini'],
            ]
        );
        $this->assertEquals(
            MimeType::getInfo("application/json"),
            [
                'compressible' => true,
                'source'       => 'iana',
                'charset'      => 'UTF-8',
                'extensions'   => ['json', 'map'],
            ]
        );
    }

    public function testExtensionMimes() {
        $this->assertEquals(
            MimeType::getExtensionMimes("wav"),
            ['audio/wav', 'audio/wave', 'audio/x-wav']
        );
        $this->assertEquals(
            MimeType::getExtensionMimes("wadl"),
            ['application/vnd.sun.wadl+xml']
        );
    }

    public function testGetSupportedExtensions() {
        $list = MimeType::getSupportedExtensions();

        $notString = false;
        foreach ($list as $item) {
            if (!\is_string($item) && !\is_numeric($item)) {
                $notString = true;
                break;
            }
        }

        $this->assertFalse($notString);
    }

    public function testGetSupportedMimes() {
        $list = MimeType::getSupportedMimes();

        $notString = false;
        foreach ($list as $item) {
            if (!\is_string($item)) {
                $notString = true;
                break;
            }
        }

        $this->assertFalse($notString);
    }
}
