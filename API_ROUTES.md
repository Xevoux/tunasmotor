# API Routes Documentation - Tunas Motor

Dokumentasi lengkap untuk semua routes yang tersedia di aplikasi Tunas Motor.

## Authentication Routes

### GET `/`
- **Deskripsi**: Redirect ke halaman login
- **Method**: GET
- **Auth**: Tidak perlu
- **Response**: Redirect ke `/login`

### GET `/login`
- **Deskripsi**: Menampilkan halaman login
- **Method**: GET
- **Auth**: Tidak perlu (redirect ke home jika sudah login)
- **Response**: View `auth.login`

### POST `/login`
- **Deskripsi**: Proses login user
- **Method**: POST
- **Auth**: Tidak perlu
- **Body Parameters**:
  ```json
  {
    "email": "string (required, email)",
    "password": "string (required)",
    "remember": "boolean (optional)"
  }
  ```
- **Response Success**: Redirect ke `/home`
- **Response Error**: Redirect back dengan error message

### GET `/register`
- **Deskripsi**: Menampilkan halaman register
- **Method**: GET
- **Auth**: Tidak perlu (redirect ke home jika sudah login)
- **Response**: View `auth.register`

### POST `/register`
- **Deskripsi**: Proses registrasi user baru
- **Method**: POST
- **Auth**: Tidak perlu
- **Body Parameters**:
  ```json
  {
    "name": "string (required, max:255)",
    "email": "string (required, email, unique)",
    "password": "string (required, min:8, confirmed)",
    "password_confirmation": "string (required)"
  }
  ```
- **Response Success**: Auto login dan redirect ke `/home`
- **Response Error**: Redirect back dengan error messages

### POST `/logout`
- **Deskripsi**: Logout user
- **Method**: POST
- **Auth**: Required
- **Response**: Redirect ke `/login`

---

## Home Routes

### GET `/home`
- **Deskripsi**: Menampilkan halaman home dengan produk
- **Method**: GET
- **Auth**: Required (middleware: auth)
- **Response**: View `home` dengan data:
  - `newProducts` - Collection produk baru (is_new = true, limit 6)
  - `products` - Collection produk berdasarkan rating (limit 8)
  - `categories` - Collection semua kategori

**Data Structure Response:**
```php
[
    'newProducts' => [
        {
            'id': 1,
            'category_id': 1,
            'nama': 'Nama Produk',
            'deskripsi': 'Deskripsi produk',
            'harga': 100000,
            'harga_diskon': 80000,
            'stok': 50,
            'terjual': 10,
            'gambar': null,
            'rating': 4.5,
            'jumlah_rating': 20,
            'is_new': true,
            'diskon_persen': 20,
            'category': {
                'id': 1,
                'nama': 'Oli Motor'
            }
        }
    ],
    'products' => [...],
    'categories' => [...]
]
```

---

## Cart Routes

### GET `/cart`
- **Deskripsi**: Menampilkan halaman keranjang belanja
- **Method**: GET
- **Auth**: Required
- **Route Name**: `cart.index`
- **Response**: View `cart` dengan data:
  - `carts` - Collection item di keranjang user
  - `total` - Total harga semua item

### POST `/cart/add`
- **Deskripsi**: Menambahkan produk ke keranjang
- **Method**: POST
- **Auth**: Required
- **Route Name**: `cart.add`
- **Headers**: 
  ```
  Content-Type: application/json
  X-CSRF-TOKEN: {csrf_token}
  ```
- **Body Parameters**:
  ```json
  {
    "product_id": "integer (required, exists:products,id)",
    "jumlah": "integer (required, min:1)"
  }
  ```
- **Response Success**:
  ```json
  {
    "success": true,
    "message": "Produk berhasil ditambahkan ke keranjang",
    "cartCount": 5
  }
  ```
- **Response Error (Not Authenticated)**:
  ```json
  {
    "success": false,
    "message": "Silakan login terlebih dahulu"
  }
  ```

### PUT `/cart/{id}`
- **Deskripsi**: Update quantity produk di keranjang
- **Method**: PUT
- **Auth**: Required
- **Route Name**: `cart.update`
- **Headers**: 
  ```
  Content-Type: application/json
  X-CSRF-TOKEN: {csrf_token}
  ```
- **URL Parameters**:
  - `id` - Cart item ID
- **Body Parameters**:
  ```json
  {
    "jumlah": "integer (required, min:1)"
  }
  ```
- **Response Success**:
  ```json
  {
    "success": true
  }
  ```

### DELETE `/cart/{id}`
- **Deskripsi**: Hapus produk dari keranjang
- **Method**: DELETE
- **Auth**: Required
- **Route Name**: `cart.delete`
- **Headers**: 
  ```
  Content-Type: application/json
  X-CSRF-TOKEN: {csrf_token}
  ```
- **URL Parameters**:
  - `id` - Cart item ID
- **Response Success**:
  ```json
  {
    "success": true
  }
  ```

### GET `/cart/count`
- **Deskripsi**: Get jumlah item di keranjang
- **Method**: GET
- **Auth**: Required
- **Route Name**: `cart.count`
- **Response**:
  ```json
  {
    "count": 5
  }
  ```
- **Response (Not Authenticated)**:
  ```json
  {
    "count": 0
  }
  ```

---

## Contoh Penggunaan dengan JavaScript

### 1. Menambah Produk ke Keranjang

```javascript
function addToCart(productId) {
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            product_id: productId,
            jumlah: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            updateCartCount();
        } else {
            alert(data.message || 'Gagal menambahkan produk');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan');
    });
}
```

### 2. Update Quantity di Keranjang

```javascript
function updateQuantity(cartId, quantity) {
    fetch(`/cart/${cartId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            jumlah: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload(); // Refresh untuk update total
        }
    })
    .catch(error => console.error('Error:', error));
}
```

### 3. Hapus Item dari Keranjang

```javascript
function removeItem(cartId) {
    if (!confirm('Yakin ingin menghapus produk ini?')) return;
    
    fetch(`/cart/${cartId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}
```

### 4. Get Cart Count

```javascript
function updateCartCount() {
    fetch('/cart/count')
        .then(response => response.json())
        .then(data => {
            document.getElementById('cartCount').textContent = data.count;
        })
        .catch(error => console.error('Error:', error));
}

// Call on page load
updateCartCount();
```

---

## Middleware

### Authentication Middleware
Routes yang menggunakan middleware `auth`:
- `/home` - Halaman home
- `/cart/*` - Semua routes cart

Jika user belum login dan mengakses routes ini, akan diarahkan ke `/login`

---

## Error Responses

### Validation Error
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": [
            "Email wajib diisi."
        ],
        "password": [
            "Password minimal 8 karakter."
        ]
    }
}
```

### Unauthenticated Error
```json
{
    "message": "Unauthenticated."
}
```

### Not Found Error (404)
```json
{
    "message": "Not Found."
}
```

---

## Testing Routes

### Menggunakan Postman atau Thunder Client

1. **Login**
   ```
   POST http://localhost:8000/login
   Body (form-data):
   - email: admin@tunasmotor.com
   - password: password
   ```

2. **Add to Cart** (setelah login)
   ```
   POST http://localhost:8000/cart/add
   Headers:
   - Content-Type: application/json
   - X-CSRF-TOKEN: {token_from_cookie}
   Body (JSON):
   {
       "product_id": 1,
       "jumlah": 2
   }
   ```

3. **Get Cart Count**
   ```
   GET http://localhost:8000/cart/count
   ```

---

## Database Schema Reference

### Users Table
- id (bigint, PK)
- name (varchar)
- email (varchar, unique)
- password (varchar)
- created_at, updated_at

### Categories Table
- id (bigint, PK)
- nama (varchar)
- deskripsi (text, nullable)
- created_at, updated_at

### Products Table
- id (bigint, PK)
- category_id (bigint, FK)
- nama (varchar)
- deskripsi (text, nullable)
- harga (decimal)
- harga_diskon (decimal, nullable)
- stok (integer)
- terjual (integer, default: 0)
- gambar (varchar, nullable)
- rating (decimal)
- jumlah_rating (integer)
- is_new (boolean)
- diskon_persen (integer, nullable)
- created_at, updated_at

### Carts Table
- id (bigint, PK)
- user_id (bigint, FK)
- product_id (bigint, FK)
- jumlah (integer)
- created_at, updated_at

### Orders Table
- id (bigint, PK)
- user_id (bigint, FK)
- nomor_pesanan (varchar, unique)
- total_harga (decimal)
- diskon (decimal)
- total_bayar (decimal)
- status (varchar)
- metode_pembayaran (varchar, nullable)
- alamat_pengiriman (text, nullable)
- created_at, updated_at

### Order Items Table
- id (bigint, PK)
- order_id (bigint, FK)
- product_id (bigint, FK)
- jumlah (integer)
- harga (decimal)
- subtotal (decimal)
- created_at, updated_at

---

## Notes

- Semua routes POST, PUT, DELETE memerlukan CSRF token
- Token CSRF bisa didapat dari meta tag: `<meta name="csrf-token" content="{{ csrf_token() }}">`
- Untuk API yang return JSON, pastikan menyertakan header `Accept: application/json`
- Semua response sukses untuk API akan return JSON dengan struktur:
  ```json
  {
      "success": true,
      "message": "...",
      "data": {...}
  }
  ```

