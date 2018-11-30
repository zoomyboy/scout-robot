<?php

namespace App\Pdf\Generator;

use Storage;
use App\Traits\ParsesHtmlContent;

define('FPDF_FONTPATH', resource_path('fonts'));
define('EURO', chr(128));

abstract class GlobalPdf
{
    use ParsesHtmlContent;

    public $pdf;

    public function __construct()
    {
        $this->pdf = new \FPDF;
    }

    public function generate($filename) {
        return $this->save(str_slug($filename).'.pdf');
    }

    private function save($filename)
    {
        $this->pdf->output('F', Storage::disk('temp')->path($filename));

        return Storage::disk('temp')->url($filename);
    }

    public function formatHtml($string)
    {
        $parts = $this->parseHtmlContent($string);

        return array_map(function ($part) {
            $part->text = $this->formatStringWithEuro($part->text);

            return $part;
        }, $parts);
    }

    public function formatStringWithEuro($string)
    {
        $content = explode('€', $string);
        $content = array_map('utf8_decode', $content);

        return implode(EURO, $content);
    }
}
