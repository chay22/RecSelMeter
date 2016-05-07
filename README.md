# RecSelMeter
Ukur tingkat kepercayaan sebuah lapak KASKUS. Hal ini sedikit berguna untuk mencegah terjadinya maraknya penipuan online di KASKUS dengan menganalisa lapak melalui URL. Hal yang sama juga berguna agar para seller KASKUS mau (belajar) mengubah lapaknya agar lebih baik atau setidaknya sesuai standar yang KASKUS berikan.


[![Build Passing](https://img.shields.io/travis/chay22/RecSelMeter/master.svg?style=flat)](https://travis-ci.org/chay22/RecSelMeter)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/chay22/recselmeter.svg)](https://scrutinizer-ci.com/g/chay22/RecSelMeter/?branch=master)
[![Scrutinizer Coverage](https://img.shields.io/scrutinizer/coverage/g/chay22/recselmeter.svg)](https://scrutinizer-ci.com/g/chay22/RecSelMeter/?branch=master)
[![Packagist](https://img.shields.io/packagist/v/chay22/recselmeter.svg)](https://packagist.org/packages/chay22/recselmeter)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg?style=flat)](https://raw.githubusercontent.com/chay22/RecSelMeter/master/LICENSE)


## Contents
* [Example](#example)
* [Parameter Penunjang](#parameter-penunjang)
* [Installation](#installation)
* [Configuration](#configuration)
  * [`new`](#new)
  * [`add`](#add)
  * [`set`](#set)
* [Author](#author)
* [License](#license)

## Example
Semudah memakai pakaian sehari-hari. RecSelMeter hanya memerlukan valid lapak KASKUS URL yang ingin dianalisa dan sebuah method.
```
$url = 'http://fjb.kaskus.co.id/product/idlapak1234567890';
$recselmeter = new RecSelMeter($url);
$score = $recselmeter->calculate();

echo $score;
```

---
## Parameter Penunjang
Terdapat beberapa parameter sebagai penunjang dalam menilai sebuah lapak,
* Jumlah terjualnya produk
* Jumlah feedback seller
* Durasi keaktivan lapak
* Ketersediaan seller untuk COD
* Ranking seller
* Durasi seller bergabung dengan KASKUS
* Jumlah gambar produk

---
## Installation
Instalasi RecSelMeter menggunakan [composer](https://getcomposer.org/)
```
composer require chay22/recselmeter
```
Setelah terinstal, cukup dengan requiring autoload file
```
require_once __DIR__ . '/vendor/autoload.php';

use Chay22\RecSelMeter\RecSelMeter;
```

---
## Configuration
Untuk melihat parameter atau data-data konfigurasi bawaan (default) RecSelMeter, cukup dengan
```
$data = $recselmeter->config()->data();
print_r($data);
```
Sudah tau data-datanya? RecSelMeter menyediakan 3 method untuk mengubah nilai-nilai pada property penunjang bawaan, yakni `new`, `add`, `set`.


>**NOTE:** Ketiga method ini hanya bisa mengubah property `rank`, `storeActive` dan `sold`.


### **`new`**
Berfungsi untuk menimpa nilai config bawaan dengan nilai baru pada property yang dipilih, contoh:
```
$recselmeter->config()->newRank([
                       'KASKUS Plus' => 20,
                       'Moderator' => 500,
                       'Administrator' => 1000,
                  ]);
$recselmeter->config()->newSold([
                        1 => 30,
                        2 => 50,
                        3 => 100,
                  ]);
```
### **`add`**
Berfungsi untuk menambah nilai baru pada property yang dituju, contoh:
```
$recselmeter->config()->addRank(['Aktivis Kaskus' => 30]);
$recselmeter->config()->addSold([7 => 60]);
```

### **`set`**
Berfungsi untuk mengubah nilai bawaan yang tersedia pada property yang dituju, contoh:
```
$recselmeter->config()->setStoreActive([7 => 3]);
```

---
## Author
[Cahyadi Nugraha](https://enchay.ru)

---
## License
[The MIT License](https://github.com/chay22/RecSelMeter/blob/master/LICENSE)
