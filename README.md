# FoodBank Native PHP + TailwindCSS

## Menjalankan
1. Copy env:
   ```bash
   cp .env.example .env
   ```
2. Pastikan path database SQLite di `.env` terisi:
   - `DB_CONNECTION=sqlite`
   - `DB_DATABASE=database/foodbank.sqlite`
   File database akan dibuat otomatis jika belum ada.
3. Jalankan server:
   ```bash
   php -S localhost:8000 -t public
   ```
4. Buka:
   - User dashboard: `http://localhost:8000/`
   - Admin dashboard: `http://localhost:8000/admin`

## Login
- User: login nomor HP + OTP WhatsApp.
- Admin default dari `.env`:
  - username: `admin`
  - password: `admin123`

## Fitur
- Identifikasi profile user via nomor handphone
- Auth user via OTP WhatsApp
- Registrasi user baru (HP + NIK + OTP WhatsApp)
- Limit pengambilan makanan: max 1x / minggu
- Katalog inventori publik real-time
- Admin CRUD inventori + monitor riwayat pengambilan
- Menu regulasi (PDF)
- API ready untuk scale ke mobile app

## Catatan OTP WhatsApp
- Isi `FONNTE_TOKEN` di `.env` untuk kirim OTP melalui Fonnte.
- Jika belum diisi, aplikasi jalan di mode dev: OTP ditulis ke `storage/wa_otp.log`.
