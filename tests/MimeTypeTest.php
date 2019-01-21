<?php
declare(strict_types=1);

namespace xobotyi;

use PHPUnit\Framework\TestCase;

final class MimeTypeTest extends TestCase
{
    public function testIsSupported() {
        $this->assertTrue(MimeType::isSupported("text/plain"));
        $this->assertFalse(MimeType::isSupported("text0/plain"));
        $this->assertFalse(MimeType::isSupported("text/plain0"));
        $this->assertFalse(MimeType::isSupported("textplain"));
    }

    public function testIsSupportedExtension() {
        $this->assertTrue(MimeType::isSupportedExtension("txt"));
        $this->assertTrue(MimeType::isSupportedExtension("webp"));
        $this->assertFalse(MimeType::isSupportedExtension("text/plain0"));
    }

    public function testGetInfo() {
        $this->assertNull(MimeType::getInfo("text0/plain"));
        $this->assertNull(MimeType::getInfo("text/plain0"));
        $this->assertNull(MimeType::getInfo("textplain"));

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
        $this->assertTrue(count($list) >= 2070);

        $this->assertIsArray(MimeType::getSupportedMimes('inexistentGroup'));
        $this->assertEquals(0, count(MimeType::getSupportedMimes('inexistentGroup')));

        $list = MimeType::getSupportedMimes("text");

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
