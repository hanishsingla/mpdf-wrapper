# mpdf-wrapper

This bundle allows to use mpdf library with symfony3.


## 1 Installation<a name="p_1"></a>

### 1.1 Download MpdfWrapperBundle using composer<a name="p_1_1"></a>

Run in terminal:
```bash
$ php composer.phar require symfgenus/mpdf-wrapper
```

### 1.2 Enable the bundle<a name="p_1_2"></a>

Enable the bundle:
```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Symfgenus\MpdfWrapper\MpdfWrapperBundle(),
    );
}
```

## 2 Usage<a name="p_2"></a>

MPDF instance can be loaded with help of 

```php
$this->get('symfgenus.mpdf.wrapper').
```