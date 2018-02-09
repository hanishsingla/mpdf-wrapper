<?php

namespace Symfgenus\MpdfWrapper\Service;

use Symfony\Component\HttpFoundation\Response;

class MpdfService
{
    private $addDefaultConstructorArgs = true;
    private $tmp_dir;
    public $pdf;

    public function __construct($cache_dir)
    {
        $this->tmp_dir = $cache_dir . '/mpdf/';

        $this->init();

        if (!is_dir($this->tmp_dir)) {
            mkdir($this->tmp_dir, 0777);
        }

        if (!is_writable($this->tmp_dir)) {
            throw new Exception("Temp directory not writeable: " . $this->tmp_dir);
        }

        return $this;
    }

    public function init($constructorArgs = [])
    {
        $this->pdf = $this->getMpdf($constructorArgs);

        if (!$this->pdf || !empty($constructorArgs)) {
            $this->pdf = $this->init($constructorArgs);
        }

        return $this;
    }

    /**
     * Get an instance of mPDF class
     * @param array $constructorArgs arguments for mPDF constructor
     * @return \mPDF
     */
    public function getMpdf($constructorArgs = [])
    {
        if ($this->getAddDefaultConstructorArgs()) {
            $defaultArgs = [
                'mode'            => 'utf-8',
                'format'          => 'A4',
                'tempDir'         => $this->tmp_dir,
                'showImageErrors' => true,
                'debug'           => true
            ];

            $constructorArgs = array_merge($defaultArgs, $constructorArgs);
        }

        return new \Mpdf\Mpdf($constructorArgs);
    }

    /**
     * Returns a string which content is a PDF document
     */
    public function generatePdf($html, array $argOptions = [])
    {
        //Calculate arguments
        $defaultOptions = [
            'constructorArgs'     => [],
            'writeHtmlMode'       => null,
            'writeHtmlInitialise' => null,
            'writeHtmlClose'      => null,
            'outputFilename'      => '',
            'outputDest'          => 'S',
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
            echo $e->getMessage();
            exit;
        } catch (\Exception $e) {
            // Note: safer fully qualified exception name used for catch
            // Process the exception, log, print etc.
            echo $e->getMessage();
            exit;
        }

        return $content;
    }

    /**
     * Generates an instance of Response class with PDF document
     * @param unknown $argContent
     * @param array $argOptions
     */
    public function generatePdfResponse($html = '', array $argOptions = array())
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/pdf');

        $content = $this->generatePdf($html, $argOptions);
        $response->setContent($content);

        return $response;
    }

    //Getters and setters

    public function setAddDefaultConstructorArgs($val)
    {
        $this->addDefaultConstructorArgs = $val;
        return $this;
    }

    public function getAddDefaultConstructorArgs()
    {
        return $this->addDefaultConstructorArgs;
    }

}
