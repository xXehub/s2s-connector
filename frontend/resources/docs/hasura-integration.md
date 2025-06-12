# Integrasi Hasura GraphQL dengan Frontend

## Pengenalan

Hasura GraphQL Engine telah diintegrasikan dengan microservices menggunakan PostgreSQL untuk menyediakan API GraphQL yang terpadu. Dokumen ini menjelaskan cara menggunakan Hasura GraphQL dari frontend Laravel.

## Konfigurasi

Konfigurasi koneksi ke Hasura GraphQL telah ditambahkan ke environment frontend:

```env
HASURA_GRAPHQL_URL=http://hasura:8080/v1/graphql
```

## Contoh Penggunaan

### Menggunakan HTTP Client Laravel

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GraphQLController extends Controller
{
    public function query(Request $request)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-hasura-admin-secret' => 'hasura-admin-secret'
        ])->post(env('HASURA_GRAPHQL_URL'), [
            'query' => $request->input('query'),
            'variables' => $request->input('variables', [])
        ]);

        return $response->json();
    }
}
```

### Contoh Query GraphQL

#### Mendapatkan Daftar Produk

```graphql
query GetProducts {
  product_service {
    products {
      id
      name
      price
      stock
    }
  }
}
```

#### Mendapatkan Detail Order dengan Relasi

```graphql
query GetOrderWithDetails($orderId: Int!) {
  order_service {
    orders(where: {id: {_eq: $orderId}}) {
      id
      user_id
      product_id
      product_name
      product_price
      quantity
      total_price
      status
      created_at
    }
  }
}
```

#### Mendapatkan Data User

```graphql
query GetUsers {
  user_service {
    users {
      id
      name
      email
    }
  }
}
```

## Keamanan

Untuk keamanan produksi, sebaiknya:

1. Jangan gunakan admin secret di frontend
2. Buat JWT authentication untuk Hasura
3. Gunakan permission rules di Hasura untuk membatasi akses data

## Catatan Penting

Konfigurasi ini menggunakan PostgreSQL karena versi gratis Hasura hanya mendukung PostgreSQL sebagai sumber data. Semua database microservices (user, order, product) sekarang disimpan dalam satu instance PostgreSQL, bukan di MySQL seperti sebelumnya.

## Referensi

- [Dokumentasi Hasura](https://hasura.io/docs/latest/index/)
- [Hasura GraphQL API](https://hasura.io/docs/latest/api-reference/index/)
- [Hasura Authentication](https://hasura.io/docs/latest/auth/index/)