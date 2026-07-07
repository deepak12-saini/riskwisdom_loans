<?php

namespace App\Support;

/**
 * Derives Annature signature field coordinates from PDF page dimensions.
 *
 * Annature silently skips fields outside page bounds (bottom-left origin, 72 DPI).
 */
class PdfSignaturePlacement
{
    /**
     * @return array{width: float, height: float, pages: int}
     */
    public static function dimensions(string $pdfContents): array
    {
        $width = 595.0;
        $height = 842.0;
        $pages = 1;

        if (preg_match_all(
            '/\/MediaBox\s*\[\s*([\d.\-]+)\s+([\d.\-]+)\s+([\d.\-]+)\s+([\d.\-]+)\s*\]/',
            $pdfContents,
            $matches,
            PREG_SET_ORDER
        )) {
            $box = end($matches);
            $width = max(1.0, (float) $box[3] - (float) $box[1]);
            $height = max(1.0, (float) $box[4] - (float) $box[2]);
        }

        $pageCount = preg_match_all('/\/Type\s*\/Page(?!\s*s)/', $pdfContents);

        if (is_int($pageCount) && $pageCount > 0) {
            $pages = $pageCount;
        }

        return [
            'width' => $width,
            'height' => $height,
            'pages' => $pages,
        ];
    }

    public static function containsAnchor(string $pdfContents, string $anchor): bool
    {
        return $anchor !== '' && str_contains($pdfContents, $anchor);
    }

    /**
     * @param  array{width: float, height: float, pages: int}  $dimensions
     * @param  array<string, mixed>  $defaults
     * @return array<string, mixed>
     */
    public static function coordinateField(array $dimensions, array $defaults = []): array
    {
        $marginX = (float) ($defaults['margin_x'] ?? 72);
        $marginY = (float) ($defaults['margin_y'] ?? 72);
        $fieldWidth = (float) ($defaults['width'] ?? 200);
        $fieldHeight = (float) ($defaults['height'] ?? 50);

        $pageWidth = $dimensions['width'];
        $pageHeight = $dimensions['height'];
        $page = (int) ($defaults['page'] ?? $dimensions['pages']);

        $fieldWidth = min($fieldWidth, max(120.0, $pageWidth - ($marginX * 2)));
        $fieldHeight = min($fieldHeight, max(30.0, $pageHeight - ($marginY * 2)));

        $x = $marginX;
        $y = $marginY;

        if ($x + $fieldWidth > $pageWidth) {
            $x = max(10.0, $pageWidth - $fieldWidth - 10.0);
        }

        if ($y + $fieldHeight > $pageHeight) {
            $y = max(10.0, $pageHeight - $fieldHeight - 10.0);
        }

        return [
            'type' => 'signature',
            'page' => max(1, $page),
            'x_coordinate' => (int) round($x),
            'y_coordinate' => (int) round($y),
            'width' => (int) round($fieldWidth),
            'height' => (int) round($fieldHeight),
            'required' => true,
        ];
    }
}
