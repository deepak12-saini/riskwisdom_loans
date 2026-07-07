<?php

namespace Tests\Unit;

use App\Support\PdfSignaturePlacement;
use Tests\TestCase;

class PdfSignaturePlacementTest extends TestCase
{
    public function test_coordinate_field_places_signature_within_short_page(): void
    {
        $field = PdfSignaturePlacement::coordinateField([
            'width' => 595,
            'height' => 500,
            'pages' => 2,
        ]);

        $this->assertSame('signature', $field['type']);
        $this->assertTrue($field['required']);
        $this->assertSame(2, $field['page']);
        $this->assertGreaterThanOrEqual(10, $field['y_coordinate']);
        $this->assertLessThanOrEqual(500, $field['y_coordinate'] + $field['height']);
        $this->assertLessThanOrEqual(595, $field['x_coordinate'] + $field['width']);
    }

    public function test_old_default_coordinates_would_have_been_off_page(): void
    {
        $field = PdfSignaturePlacement::coordinateField([
            'width' => 595,
            'height' => 500,
            'pages' => 1,
        ]);

        $this->assertLessThan(650, $field['y_coordinate']);
    }

    public function test_dimensions_reads_mediabox_and_page_count_from_pdf_bytes(): void
    {
        $pdf = "%PDF-1.4\n".
            "1 0 obj<</Type/Pages/Kids[2 0 R 3 0 R]/Count 2>>endobj\n".
            "2 0 obj<</Type/Page/MediaBox[0 0 595 500]/Parent 1 0 R>>endobj\n".
            "3 0 obj<</Type/Page/MediaBox[0 0 595 500]/Parent 1 0 R>>endobj\n";

        $dimensions = PdfSignaturePlacement::dimensions($pdf);

        $this->assertSame(595.0, $dimensions['width']);
        $this->assertSame(500.0, $dimensions['height']);
        $this->assertSame(2, $dimensions['pages']);
    }
}
