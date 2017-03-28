<?php
namespace Symfgenus\MpdfWrapper\Service;

use Symfony\Component\HttpFoundation\Response;

class MpdfService
{

    private $addDefaultConstructorArgs = true;
    public $pdf;

    public function __construct($constructorArgs = array())
    {
        $this->getMpdf($constructorArgs);
        return $this;
    }

    public function init($constructorArgs = array())
    {

        $this->getMpdf($constructorArgs);
        return $this;
    }

    /**
     * Get an instance of mPDF class
     * @param array $constructorArgs arguments for mPDF constror
     * @return \mPDF
     */
    public function getMpdf($constructorArgs = array())
    {
        $allConstructorArgs = $constructorArgs;
        if ($this->getAddDefaultConstructorArgs()) {
            $allConstructorArgs = array_merge(array('utf-8', 'A4'), $allConstructorArgs);
        }

        if (!$this->pdf || !empty($constructorArgs)) {
            $reflection = new \ReflectionClass('\mPDF');
            $this->pdf = $reflection->newInstanceArgs($allConstructorArgs);
        }

        return $this->pdf;
    }

    /**
     * Returns a string which content is a PDF document
     */
    public function generatePdf($html, array $argOptions = array())
    {
        //Calculate arguments
        $defaultOptions = array(
            'constructorArgs'     => array(),
            'writeHtmlMode'       => null,
            'writeHtmlInitialise' => null,
            'writeHtmlClose'      => null,
            'outputFilename'      => '',
            'outputDest'          => 'S',
            'mpdf'                => null
        );
        $options = array_merge($defaultOptions, $argOptions);
        extract($options);

        if (null == $mpdf)
            $mpdf = $this->getMpdf($constructorArgs);

        //Add argguments to AddHtml function
        $writeHtmlArgs = array($writeHtmlMode, $writeHtmlInitialise, $writeHtmlClose);
        $writeHtmlArgs = array_filter($writeHtmlArgs, function($x) {
            return !is_null($x);
        });
        $writeHtmlArgs['HTMLHeader'] = $html;

        @call_user_func_array(array($mpdf, 'WriteHTML'), $writeHtmlArgs);

        //Add arguments to Output function
        $content = $mpdf->Output($outputFilename, $outputDest);
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
