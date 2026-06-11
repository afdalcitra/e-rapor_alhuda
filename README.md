#Rapor Digital Guru – Al-Huda Citra Utama

Aplikasi berbasis web untuk pengelolaan dan distribusi nilai rapor guru secara online. Sistem ini menggantikan proses rapor berbasis kertas sehingga guru dapat melihat hasil penilaian kapan saja dan di mana saja.
#✨ Fitur Utama
##🔐 Authentication & Authorization
Role-based access:

- Admin
- Yayasan
- Guru

##👨‍💼 Admin

- Mengelola data pengguna
- Menginput dan mengubah nilai guru
- Mengelola status verifikasi rapor

##🏛️ Yayasan

- Melihat grafik performa guru
- Melihat detail nilai per guru
- Memberikan ACC/verifikasi rapor

##👨‍🏫 Guru

- Melihat dashboard performa pribadi
- Melihat ringkasan nilai
- Download/print rapor setelah diverifikasi
- Tampilan responsive untuk perangkat mobile

🛠️ Teknologi yang Digunakan

- Framework: Laravel
- Database: MySQL
- Frontend: Blade + TailwindCSS

| 📦 Struktur Role | |

| Role | Akses |
| Admin | CRUD pengguna dan nilai guru |
| Yayasan | Melihat grafik dan memverifikasi nilai |
| Guru | Melihat dan mengunduh rapor pribadi |
