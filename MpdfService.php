<?php

namespace Symfgenus\MpdfWrapper;

use Mpdf\Mpdf;
use Symfony\Component\HttpFoundation\Response;

class MpdfService
{
    private $tmp_dir;
    public $pdf;

    public function __construct(string $cache_dir)
    {
        $this->tmp_dir = $cache_dir . '/mpdf/';

        if (!is_dir($this->tmp_dir)) {
            mkdir($this->tmp_dir, 0777);
        }

        if (!is_writable($this->tmp_dir)) {
            throw new \Exception("Temp directory not writeable: " . $this->tmp_dir);
        }

        return $this;
    }

    public function init(array $constructorArgs = [])
    {
        $this->getMpdf($constructorArgs);
        return $this;
    }

    /**
     * Get an instance of mPDF class
     * @param array $constructorArgs arguments for mPDF constructor
     * @return \mPDF
     */
    public function getMpdf(array $constructorArgs = []): Mpdf
    {
        list(
            $mode,
            $format,
            $default_font_size,
            $default_font,
            $mgl,
            $mgr,
            $mgt,
            $mgb,
            $mgh,
            $mgf,
            $orientation
            ) = $constructorArgs;

        $defaultArgs = [
            'tempDir' => $this->tmp_dir,
            'showImageErrors' => true,
            'debug' => true,
            'mode' => $mode ?? 'utf-8',
            'format' => $format ?? 'A4',
            'default_font_size' => $default_font_size,
            'default_font' => $default_font,
            'margin_left' => $mgl,
            'margin_right' => $mgr,
            'margin_top' => $mgt,
            'margin_bottom' => $mgb,
            'margin_header' => $mgh,
            'margin_footer' => $mgf,
            'orientation' => $orientation ?? 'P',
        ];

        $args = array_merge($defaultArgs, $constructorArgs);

        if (!$this->pdf || !empty($args)) {
            $this->pdf = new Mpdf($args);
        }

        return $this->pdf;
    }

    /**
     * Returns a string which content is a PDF document
     */
    public function generatePdf(string $html, array $argOptions = [])
    {
        //Calculate arguments
        $defaultOptions = [
            'constructorArgs' => ['', 'A4', 5, '', 15, 15, 10, 30, 10, 10, 'P'],
            'outputFilename' => '',
            'outputDest' => 'S',
        ];
        $options = array_merge($defaultOptions, $argOptions);

        //Add arguments to Output function
        try {
            if ($html) {
                $mpdf = $this->getMpdf($options['constructorArgs']);
                $mpdf->WriteHTML($html);
                $content = $mpdf->Output($options['outputFilename'], $options['outputDest']);
            } elseif ($this->pdf) {
                $content = $this->pdf->Output($options['outputFilename'], $options['outputDest']);
            }
        } catch (\Mpdf\MpdfException $e) {
            //// Note: safer fully qualified exception name used for catch
            // Process the exception, log, print etc.
            return $e->getMessage();
        } catch (\Exception $e) {
            // Note: safer fully qualified exception name used for catch
            // Process the exception, log, print etc.
            return $e->getMessage();
        }

        return $content;
    }

    /**
     * Generates an instance of Response class with PDF document
     *
     * @param unknown $html
     * @param array $argOptions
     */
    public function generatePdfResponse($html = '', array $argOptions = []): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/pdf');

        $content = $this->generatePdf($html, $argOptions);
        $response->setContent($content);

        return $response;
    }

}
