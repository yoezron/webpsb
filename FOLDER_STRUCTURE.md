# 📁 Struktur Folder Project
## PSB Persis 31 Banjaran

---

## 🌳 Overview Struktur Direktori

```
psb-persis31-banjaran/
│
├── 📂 app/                          # Application Core
│   ├── 📂 Config/                   # Configuration files
│   │   ├── App.php
│   │   ├── Database.php
│   │   ├── Routes.php
│   │   ├── Filters.php
│   │   ├── Validation.php
│   │   └── ...
│   │
│   ├── 📂 Controllers/              # Controller layer
│   │   ├── BaseController.php
│   │   ├── Landing.php
│   │   ├── Pendaftaran.php
│   │   ├── Dashboard.php
│   │   ├── Auth.php
│   │   └── PdfController.php
│   │
│   ├── 📂 Models/                   # Data Models
│   │   ├── PendaftarModel.php
│   │   ├── AlamatModel.php
│   │   ├── AyahModel.php
│   │   ├── IbuModel.php
│   │   ├── WaliModel.php
│   │   ├── BansosModel.php
│   │   ├── SekolahModel.php
│   │   └── AdminModel.php
│   │
│   ├── 📂 Views/                    # View templates
│   │   ├── 📂 templates/
│   │   │   ├── header.php
│   │   │   ├── footer.php
│   │   │   ├── sidebar.php
│   │   │   └── navbar.php
│   │   │
│   │   ├── 📂 landing/
│   │   │   └── index.php
│   │   │
│   │   ├── 📂 form/
│   │   │   ├── tsanawiyyah.php
│   │   │   ├── muallimin.php
│   │   │   └── partials/
│   │   │       ├── data_diri.php
│   │   │       ├── data_alamat.php
│   │   │       ├── data_keluarga.php
│   │   │       ├── data_bansos.php
│   │   │       └── data_sekolah.php
│   │   │
│   │   ├── 📂 review/
│   │   │   └── index.php
│   │   │
│   │   ├── 📂 success/
│   │   │   └── index.php
│   │   │
│   │   ├── 📂 dashboard/
│   │   │   ├── index.php
│   │   │   ├── tsanawiyyah.php
│   │   │   ├── muallimin.php
│   │   │   └── detail.php
│   │   │
│   │   ├── 📂 auth/
│   │   │   ├── login.php
│   │   │   └── forgot_password.php
│   │   │
│   │   └── 📂 pdf/
│   │       ├── kartu_pendaftaran.php
│   │       └── detail_pendaftar.php
│   │
│   ├── 📂 Database/                 # Database operations
│   │   ├── 📂 Migrations/           # Database migrations
│   │   │   ├── 2026-01-01-000001_create_pendaftar_table.php
│   │   │   ├── 2026-01-01-000002_create_alamat_table.php
│   │   │   ├── 2026-01-01-000003_create_data_ayah_table.php
│   │   │   ├── 2026-01-01-000004_create_data_ibu_table.php
│   │   │   ├── 2026-01-01-000005_create_data_wali_table.php
│   │   │   ├── 2026-01-01-000006_create_bansos_table.php
│   │   │   ├── 2026-01-01-000007_create_asal_sekolah_table.php
│   │   │   └── 2026-01-01-000008_create_admin_panitia_table.php
│   │   │
│   │   └── 📂 Seeds/                # Database seeders
│   │       ├── AdminSeeder.php
│   │       ├── PendaftarSeeder.php
│   │       └── DatabaseSeeder.php
│   │
│   ├── 📂 Libraries/                # Custom libraries
│   │   ├── PdfGenerator.php
│   │   ├── ExcelExport.php
│   │   └── NomorPendaftaran.php
│   │
│   ├── 📂 Helpers/                  # Helper functions
│   │   ├── form_helper.php
│   │   ├── date_helper.php
│   │   └── pdf_helper.php
│   │
│   ├── 📂 Filters/                  # Middleware filters
│   │   ├── AuthFilter.php
│   │   └── RoleFilter.php
│   │
│   └── 📂 Validation/               # Custom validation rules
│       └── PendaftaranRules.php
│
├── 📂 public/                       # Publicly accessible files
│   ├── index.php                    # Front controller
│   ├── .htaccess
│   │
│   ├── 📂 assets/                   # Static assets
│   │   ├── 📂 hafsa/               # Hafsa template
│   │   │   ├── 📂 css/
│   │   │   │   ├── style.css
│   │   │   │   ├── bootstrap.min.css
│   │   │   │   ├── custom.css
│   │   │   │   └── responsive.css
│   │   │   │
│   │   │   ├── 📂 js/
│   │   │   │   ├── main.js
│   │   │   │   ├── bootstrap.bundle.min.js
│   │   │   │   ├── jquery.min.js
│   │   │   │   └── form-validation.js
│   │   │   │
│   │   │   ├── 📂 images/
│   │   │   │   ├── logo-persis.png
│   │   │   │   ├── hero-bg.jpg
│   │   │   │   └── icons/
│   │   │   │
│   │   │   └── 📂 fonts/
│   │   │       └── ...
│   │   │
│   │   └── 📂 uploads/             # User uploads
│   │       ├── 📂 pdf/             # Generated PDFs
│   │       ├── 📂 documents/       # Uploaded documents
│   │       └── 📂 photos/          # Photos
│   │
│   └── 📂 favicon/
│       └── favicon.ico
│
├── 📂 writable/                    # Writable directories
│   ├── 📂 cache/                   # Cache files
│   ├── 📂 logs/                    # Log files
│   │   ├── log-2026-01-15.log
│   │   └── ...
│   ├── 📂 session/                 # Session files
│   └── 📂 uploads/                 # Temp uploads
│
├── 📂 tests/                       # Test files
│   ├── 📂 _support/
│   ├── 📂 unit/
│   └── 📂 feature/
│
├── 📂 vendor/                      # Composer dependencies
│   └── ...
│
├── 📂 docs/                        # Documentation
│   ├── USER_MANUAL_PENDAFTAR.pdf
│   ├── USER_MANUAL_PANITIA.pdf
│   ├── DATABASE_SCHEMA.md
│   └── API_DOCS.md
│
├── .env                            # Environment variables
├── .gitignore                      # Git ignore rules
├── composer.json                   # Composer config
├── composer.lock                   # Composer lock
├── phpunit.xml                     # PHPUnit config
├── spark                           # CLI tool
├── install.sh                      # Installation script (Linux/Mac)
├── install.bat                     # Installation script (Windows)
├── database_schema.sql             # Database schema
├── REQUIREMENTS.md                 # System requirements
└── README.md                       # Project documentation
```

---

## 📄 Deskripsi File Penting

### Root Level

| File | Deskripsi |
|------|-----------|
| `.env` | Environment configuration (database, app settings) |
| `composer.json` | PHP dependencies management |
| `spark` | CodeIgniter CLI tool |
| `install.sh` | Auto-installation script for Linux/Mac |
| `install.bat` | Auto-installation script for Windows |
| `README.md` | Project documentation |

### app/Config/

| File | Deskripsi |
|------|-----------|
| `App.php` | Application-wide settings |
| `Database.php` | Database connection configuration |
| `Routes.php` | URL routing definitions |
| `Filters.php` | Middleware configuration |
| `Validation.php` | Validation rules |

### app/Controllers/

| File | Deskripsi |
|------|-----------|
| `Landing.php` | Landing page controller |
| `Pendaftaran.php` | Registration form controller |
| `Dashboard.php` | Admin dashboard controller |
| `Auth.php` | Authentication controller |
| `PdfController.php` | PDF generation controller |

### app/Models/

| File | Deskripsi |
|------|-----------|
| `PendaftarModel.php` | Main registration model |
| `AlamatModel.php` | Address data model |
| `AyahModel.php` | Father's data model |
| `IbuModel.php` | Mother's data model |
| `WaliModel.php` | Guardian's data model |
| `BansosModel.php` | Social aid data model |
| `SekolahModel.php` | School data model |
| `AdminModel.php` | Admin user model |

### app/Views/

Organized by feature:
- `templates/` - Reusable header, footer, navbar
- `landing/` - Homepage views
- `form/` - Registration forms
- `review/` - Data review page
- `success/` - Success confirmation
- `dashboard/` - Admin dashboard
- `auth/` - Login/logout pages
- `pdf/` - PDF templates

### public/assets/hafsa/

Hafsa template files:
- `css/` - Stylesheets (Bootstrap, custom CSS)
- `js/` - JavaScript files (jQuery, form validation)
- `images/` - Images, logos, icons
- `fonts/` - Web fonts

### writable/

Auto-generated files:
- `cache/` - Application cache
- `logs/` - Error and debug logs
- `session/` - Session data
- `uploads/` - Temporary uploads

---

## 🎯 File Naming Conventions

### Controllers
```
PascalCase + 'Controller' suffix (optional)
Examples: Landing.php, Pendaftaran.php, DashboardController.php
```

### Models
```
PascalCase + 'Model' suffix
Examples: PendaftarModel.php, AdminModel.php
```

### Views
```
snake_case.php
Examples: index.php, tsanawiyyah.php, detail_pendaftar.php
```

### CSS/JS
```
kebab-case
Examples: custom.css, form-validation.js
```

---

## 🔒 Permission Requirements

### Linux/Mac

```bash
# Application directories (read + execute)
chmod -R 755 app/
chmod -R 755 public/

# Writable directories (read + write + execute)
chmod -R 777 writable/
chmod -R 777 public/assets/uploads/

# Configuration files (read only for group/others)
chmod 644 .env
chmod 644 composer.json
```

### Windows

No special permissions needed. Ensure:
- IIS/Apache has read access to all directories
- Write access to `writable/` and `public/assets/uploads/`

---

## 📊 Directory Size Estimates

| Directory | Estimated Size |
|-----------|----------------|
| `app/` | 5-10 MB |
| `public/assets/hafsa/` | 15-20 MB |
| `vendor/` | 30-50 MB |
| `writable/logs/` | 10-100 MB (grows over time) |
| `public/assets/uploads/` | 100+ MB (grows over time) |

---

## 🗑️ Files to NEVER Commit to Git

Configured in `.gitignore`:
- `.env` (sensitive credentials)
- `writable/cache/*`
- `writable/logs/*`
- `writable/session/*`
- `vendor/` (installed via composer)
- `public/assets/uploads/*` (user uploads)
- `*.sql` (database backups)

---

## 📦 Backup Strategy

### Daily Backups
- Database (`psb_persis31`)
- User uploads (`public/assets/uploads/`)
- Log files (`writable/logs/`)

### Weekly Backups
- Full application code
- Configuration files

### Monthly Backups
- Complete system snapshot
- Off-site storage

---

## 🔍 Important Directories to Monitor

### For Performance
- `writable/logs/` - Check error logs daily
- `writable/cache/` - Clear periodically
- `public/assets/uploads/` - Monitor disk space

### For Security
- `.env` - Never expose publicly
- `writable/session/` - Clear old sessions
- `vendor/` - Keep dependencies updated

---

## 📝 Development vs Production

### Development Structure
```
- Keep debug files
- Verbose logging
- Source maps for CSS/JS
```

### Production Structure
```
- Remove test files
- Minify CSS/JS
- Optimize images
- Enable caching
```

---

**Document Version**: 1.0  
**Last Updated**: December 2025  
**Next Review**: Sprint 1
