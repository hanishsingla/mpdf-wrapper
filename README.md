# mpdf-wrapper

This bundle provides a service for mpdf library with symfony 4.


## 1 Installation<a name="p_1"></a>

### 1.1 Download MpdfWrapperBundle using composer<a name="p_1_1"></a>

Run in terminal:
```bash
$ php composer.phar require symfgenus/mpdf-wrapper
```

### 1.2 Enable the bundle<a name="p_1_2"></a>

Symfony flex activates the bundles automatically. If it is not, then enable the bundle:
```php
// /config/bundles.php

    [
        // ...
        Symfgenus\MpdfWrapper\MpdfWrapperBundle::class => ['all' => true],
    ];
```

## 2 Usage<a name="p_2"></a>

MpdfService provides many ways to use MPDF.

###2.1 It can generate a direct pdf response which can be served through any route.

```php
// /config/bundles.php

    public function index(MpdfService $MpdfService)
    {
        return $MpdfService->generatePdfResponse($pdfHtml);
    }
```

###2.2 It can also generate pdf content which can be saved in a variable and used.

```php
// /config/bundles.php

    public function index(MpdfService $MpdfService)
    {
        return $pdf = $MpdfService->generatePdf($pdfHtml);
    }
```

###2.3 Sometimes there is need to create multiple PDFs, MpdfService can be used as following:

```php
// /config/bundles.php

    public function index(MpdfService $MpdfService)
    {
        $firstPdf = $MpdfService->getMpdf($argsFirst);
        $mpdf->WriteHTML($htmlFirst);
        $firstPdfFile = $mpdf->Output();

        $secondPdf = $MpdfService->getMpdf($argsSecond);
        $mpdf->WriteHTML($htmlSecond);
        $secondPdfFile = $mpdf->Output();

        return [
            $firstPdfFile,
            $secondPdfFile
        ];
    }
```

## 2 Usage for symfony 3<a name="p_2"></a>

For symfony 3, this service can be loaded as following:

```php
$this->get('symfgenus.mpdf.wrapper').
```