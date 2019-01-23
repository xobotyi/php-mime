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
        $this->assertNull(MimeType::getExtensions("text0/plain"));
        $this->assertNull(MimeType::getExtensions("text/plain0"));
        $this->assertNull(MimeType::getExtensions("textplain"));

        $this->assertEquals(
            MimeType::getExtensions("text/plain"),
            ['asc', 'conf', 'def', 'in', 'list', 'log', 'text', 'txt']
        );
        $this->assertEquals(
            MimeType::getExtensions("application/json"),
            ['json', 'map']
        );
    }

    public function testExtensionMimes() {
        $this->assertNull(MimeType::getExtensionMimes("wave"));
        $this->assertNull(MimeType::getExtensions("txt0"));

        $this->assertEquals(
            MimeType::getExtensionMimes("wav"),
            ['audio/vnd.wave', 'audio/wav', 'audio/wave', 'audio/x-wav']
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
