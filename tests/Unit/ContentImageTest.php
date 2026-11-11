<?php

namespace Tests\Unit;

use App\Support\ContentImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class ContentImageTest extends TestCase
{
    public function test_public_upload_uses_the_current_site_even_when_default_disk_is_private(): void
    {
        config(['filesystems.default' => 'local']);
        Storage::fake('public');
        URL::forceRootUrl('http://127.0.0.1:8000');
        Storage::disk('public')->put('konten/produk/simpanan.jpg', 'image');

        $this->assertSame('http://127.0.0.1:8000/storage/konten/produk/simpanan.jpg', ContentImage::url('konten/produk/simpanan.jpg'));
    }

    public function test_missing_images_return_null_for_the_existing_placeholder(): void
    {
        Storage::fake('public');

        $this->assertNull(ContentImage::url('konten/tips/missing.png'));
        $this->assertNull(ContentImage::url(null));
        $this->assertNull(ContentImage::url(''));
    }

    public function test_a_private_file_is_not_exposed_as_a_public_image(): void
    {
        Storage::fake('public');
        Storage::fake('local');
        Storage::disk('local')->put('konten/tips/private.png', 'private');

        $this->assertNull(ContentImage::url('konten/tips/private.png'));
    }
}
