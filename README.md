<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Courier Management

Aplikasi sederhana untuk mengelola data kurir menggunakan Laravel.

## Fitur

- Menampilkan daftar kurir
- Pencarian kurir berdasarkan nama
- Sorting data kurir
- Pagination
- Menambahkan data kurir
- Menampilkan detail kurir
- Memperbarui data kurir
- Menghapus data kurir
- Validasi input
- Hash password sebelum disimpan ke database

## Teknologi

- PHP
- Laravel
- MySQL
- Eloquent ORM
- Query Builder

## Controller

Controller yang digunakan:

    App\Http\Controllers\CourierController

Controller ini menggunakan Resource Controller Laravel dengan beberapa method utama:

| Method | Fungsi |
|---|---|
| `index()` | Menampilkan daftar kurir |
| `create()` | Menampilkan form tambah kurir |
| `store()` | Menyimpan data kurir baru |
| `show()` | Menampilkan detail kurir |
| `edit()` | Menampilkan form edit kurir |
| `update()` | Memperbarui data kurir |
| `destroy()` | Menghapus data kurir |

Method `create()` dan `edit()` pada implementasi saat ini belum memiliki isi.

## Menampilkan Daftar Kurir

Method:

    public function index($request)

Method `index()` digunakan untuk mengambil data kurir dari tabel `couriers` dan menampilkannya pada halaman daftar kurir.

Data diambil menggunakan Query Builder:

    $query = DB::table('couriers');

Hasilnya kemudian ditampilkan pada view:

    courier.index

Data dikirim menggunakan variable:

    [
        'couriers' => $courierData
    ]

## Pencarian Kurir

Pencarian dilakukan berdasarkan nama kurir menggunakan parameter `search`.

Contoh:

    /courier?search=budi

Implementasi:

    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(
            'name',
            'like',
            '%' . $search . '%'
        );
    }

Pencarian menggunakan operator `LIKE`, sehingga akan mencari nama yang mengandung kata yang diberikan.

Contoh data:

    Budi
    Budi Santoso
    Andi
    Siti

Dengan parameter:

    search=budi

Maka hasilnya:

    Budi
    Budi Santoso

## Sorting

Data kurir dapat diurutkan berdasarkan nama atau tanggal dibuat.

### Sorting Berdasarkan Nama

Secara default, data diurutkan berdasarkan nama secara ascending:

    $query->orderBy('name', 'asc');

Contoh:

    /courier

Hasil:

    Andi
    Budi
    Citra
    Doni

### Sorting Berdasarkan Tanggal

Jika parameter `sort_by` bernilai `created_at`, data akan diurutkan berdasarkan tanggal terbaru:

    $query->orderBy('created_at', 'desc');

Contoh:

    /courier?sort_by=created_at

Data yang dibuat paling baru akan ditampilkan terlebih dahulu.

## Pagination

Data kurir menggunakan pagination sebanyak 10 data per halaman.

Implementasi:

    $courierData = $query->paginate(10);

Contoh:

    /courier?page=2

Pagination juga dapat digunakan bersama pencarian dan sorting.

Contoh:

    /courier?search=budi&sort_by=created_at&page=2

Artinya:

1. Mencari kurir dengan nama yang mengandung `budi`
2. Mengurutkan berdasarkan tanggal terbaru
3. Menampilkan halaman ke-2

## Menambahkan Kurir

Method:

    public function store(Request $request)

Method `store()` digunakan untuk menyimpan data kurir baru.

### Validasi

Input yang digunakan:

    $validateInput = $request->validate([
        'nama' => 'required',
        'no_telp' => 'required|min:13',
        'password' => 'required|min:8'
    ]);

Aturan validasi:

| Field | Rule | Keterangan |
|---|---|---|
| `nama` | `required` | Wajib diisi |
| `no_telp` | `required|min:13` | Wajib diisi dan minimal 13 karakter |
| `password` | `required|min:8` | Wajib diisi dan minimal 8 karakter |

### Password Hashing

Password tidak disimpan dalam bentuk plaintext.

Password di-hash menggunakan:

    $validateInput['password'] = Hash::make(
        $request['password']
    );

Contoh:

Input:

    password123

Password tidak disimpan langsung sebagai:

    password123

Melainkan disimpan dalam bentuk hash.

### Menyimpan Data

Data disimpan menggunakan Eloquent:

    Courier::create($validateInput);

Setelah berhasil, pengguna diarahkan kembali ke halaman daftar kurir:

    return redirect()
        ->route('courier.index')
        ->with(
            'success',
            'Data kurir telah ditambahkan!'
        );

## Menampilkan Detail Kurir

Method:

    public function show($id)

Digunakan untuk menampilkan detail satu kurir.

Data dicari menggunakan:

    $courier = Courier::findOrFail($id);

Jika data ditemukan, data dikirim ke:

    courier.show

dengan variable:

    [
        'courier' => $courier
    ]

Jika data tidak ditemukan, Laravel akan menghasilkan response `404 Not Found`.

## Memperbarui Data Kurir

Method:

    public function update(Request $request, string $id)

Digunakan untuk memperbarui data kurir.

Validasi:

    $validate = $request->validate([
        'nama' => 'required',
        'no_telp' => 'required|min:13',
    ]);

Data kurir kemudian dicari:

    $userCourier = Courier::findOrFail($id);

Setelah ditemukan, data diperbarui menggunakan:

    $userCourier->update($validate);

Setelah berhasil, pengguna diarahkan kembali ke halaman daftar kurir.

## Menghapus Kurir

Method:

    public function destroy(Courier $courier)

Digunakan untuk menghapus data kurir.

Data dihapus menggunakan:

    $courier->delete();

Setelah berhasil, pengguna diarahkan kembali ke halaman daftar kurir.

Method ini menggunakan fitur **Route Model Binding** Laravel.

Parameter:

    Courier $courier

akan otomatis direpresentasikan sebagai instance dari model `Courier`.

## Query Parameter

Halaman daftar kurir mendukung beberapa parameter:

| Parameter | Fungsi | Contoh |
|---|---|---|
| `search` | Mencari berdasarkan nama | `?search=budi` |
| `sort_by` | Menentukan sorting | `?sort_by=created_at` |
| `page` | Menentukan halaman pagination | `?page=2` |

Parameter dapat digunakan secara bersamaan.

Contoh:

    /courier?search=budi&sort_by=created_at&page=2

## Database

Controller menggunakan tabel:

    couriers

Field yang digunakan:

| Field | Keterangan |
|---|---|
| `id` | ID kurir |
| `nama` | Nama kurir |
| `no_telp` | Nomor telepon kurir |
| `password` | Password kurir dalam bentuk hash |
| `created_at` | Waktu data dibuat |

Pastikan nama field pada database sesuai dengan field yang digunakan pada Model dan Controller.

## Model Courier

Model yang digunakan:

    App\Models\Courier

Model digunakan untuk operasi Eloquent seperti:

    Courier::create()

    Courier::findOrFail()

    $courier->update()

    $courier->delete()

Pastikan field yang digunakan oleh `Courier::create()` terdapat di dalam `$fillable` pada model.

Contoh:

    protected $fillable = [
        'nama',
        'no_telp',
        'password',
    ];


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
