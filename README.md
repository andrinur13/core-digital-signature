# Sistem Informasi Persuratan (Core-Digital Signature)
Free Engine Content Management System - Biro Sistem Informasi Universitas Ahmad Dahlan
Contact : d3v@bsi.uad.ac.id

## Kebutuhan Server
- PHP >= 5.6
- BCMath PHP Extension
- Ctype PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- Curl PHP Extension
- Mysql PHP Extension
- Exif PHP Extension
- Fileinfo PHP Extension

### Catatan (harap dibaca)

#### Dokumentasi Penggunaan
Dalam Dokumentasi Penggunaan Struktur Controoler Terdapat Beberapa Jenis Base Controller yang bisa digunakan contoh yang bisa di gunakan seperti contoh di bawah ini 

_Struktur Module_
```>
- controller
- library
- helper
- model
- view
- module
  - ModuleName
    - controller
    - view
    - model
```  
## Panduan Stylce Coding
- Modules, Classes, and Methods


#### Panduan Untuk Menampilkan Menu Style
* Menampilkan Menu Untuk Admin Silahkan Setting MenuStyle menjadi **3** pada table ``sys_menu`` 
* Menampilkan Menu Untuk **Mahasiswa** bisa menggunakan Default MenuStyle menjadi **2** yang di ubah pada ``sys_menu`` pada database *dokma*

#### Modules, Classes, and Methods


***Admin Controller (Digunakan untuk Admin / Prodi / Dosen)***  


Setiap controller module harus _extends_ ke class controller _`Admin_Controller`_.  
Untuk authorization antara **Menu** dengan **Group** harus menambahakan kode program helper `restrict()` di dalam _`function __construct()`_.
```php
<?php
class ModuleClass extends Admin_Controller 
{
    function __construct()
    {
        parent::__construct();
        restrict();
    }
}
```  
***Unit Kerja dan Kantor Controller (Digunakan untuk Unit Kerja dan Kantor Universitas)***  


Setiap controller module harus _extends_ ke class controller _`Unit_Kantor_Controller`_.  
Untuk authorization antara **Menu** dengan **Group** harus menambahakan kode program helper `restrict()` di dalam _`function __construct()`_.
```php
<?php
class ModuleClass extends Unit_Kantor_Controller 
{
    function __construct()
    {
        parent::__construct();
        restrict();
    }
}
```  

function helper `restrict()` juga bisa dipanggil di setiap method controller dengan menyertakan path method, jadi hanya method tertentu yang akan di lakukan pengecekan hak akses dengan group.


## Login backend Aplikasi
* Masuk ke alamat http://nama.web.anda/login
* Masukkan data login sebagai berikut :
	* Username : admin
	* Password : admin@123

## API 
http://nama.web.anda/api/v1

Deskripsi Api :
* Comming Soon

# Terima Kasih Kepada
1. Tuhan Yang Maha Esa
2. Seluruh Programmer Biro Sistem Informasi
3. TheAdmin – Bootstrap Admin Dashboard Template sebagai pembuat template backend v.1.0.1
7. Jquery, Bootstrap dan semua plugins jquery yang dipakai pada Sistem Informasi Persuratan (E-Office) Core