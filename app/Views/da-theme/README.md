# Digital Agency Theme Layout System

## Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
## Date: 2025-08-29
## Github: github.com/mikhaelfelian
## Description: Layout system documentation for Digital Agency theme
## This file represents the documentation for the Digital Agency theme layout system.

## Overview

The Digital Agency theme now uses a modular layout system that separates different sections into individual files for better maintainability and reusability.

## File Structure

```
app/Views/da-theme/
├── layout/
│   ├── main.php              # Main layout template
│   ├── header.php            # Header section (top bar)
│   ├── navbar.php            # Navigation bar
│   ├── footer.php            # Footer section
│   └── pagers/
│       └── datheme_pagination.php  # Pagination template
├── home.php                  # Sample home page
└── README.md                 # This documentation
```

## How to Use

### 1. Extending the Main Layout

To create a new page using the Digital Agency theme layout:

```php
<?= $this->extend('da-theme/layout/main') ?>

<?= $this->section('content') ?>
<!-- Your page content here -->
<?= $this->endSection() ?>
```

### 2. Adding Custom CSS

You can add custom CSS to any page using the CSS section:

```php
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('path/to/custom.css') ?>">
<?= $this->endSection() ?>
```

### 3. Adding Custom JavaScript

You can add custom JavaScript to any page using the JS section:

```php
<?= $this->section('js') ?>
<script src="<?= base_url('path/to/custom.js') ?>"></script>
<?= $this->endSection() ?>
```

### 4. Using Pagination

To use the Digital Agency theme pagination:

```php
<?= $pager->links('group_name', 'da-theme/layout/pagers/datheme_pagination') ?>
```

## Layout Components

### Main Layout (`main.php`)
- Contains the complete HTML structure
- Includes all necessary CSS and JavaScript files
- Defines content sections for customization
- Handles the `$Pengaturan` object validation

### Header (`header.php`)
- Top bar with contact information
- Welcome message
- Contact details (email, address, city, phone)

### Navigation (`navbar.php`)
- Main navigation menu
- Logo and branding
- Responsive mobile menu
- Sign In button

### Footer (`footer.php`)
- Contact information
- Social media links
- About Us section
- Copyright notice

### Pagination (`datheme_pagination.php`)
- Bootstrap-styled pagination
- First/Previous/Next/Last navigation
- Active page highlighting

## Required Variables

The layout system requires the following variables to be passed from the controller:

- `$Pengaturan` - Settings object containing:
  - `deskripsi` - Welcome message
  - `judul_app` / `judul` - Company name
  - `url` - Email address
  - `alamat` - Physical address
  - `kota` - City
  - `telp` - Phone number

## Example Controller Usage

```php
public function index()
{
    $data = [
        'title' => 'Home - Digital Agency',
        'Pengaturan' => $this->pengaturan
    ];
    
    return $this->view('da-theme/home', $data);
}
```

## Customization

### Adding New Menu Items
Edit `navbar.php` to add new navigation items:

```php
<li class="<?= strpos(current_url(), 'new-page') !== false ? 'active' : '' ?>">
    <a href="<?= base_url('new-page') ?>">New Page</a>
</li>
```

### Modifying Styles
The theme uses Bootstrap 3.x with custom CSS files:
- `style.css` - Main theme styles
- `theme.css` - Additional theme customizations

### Adding New Sections
You can add new sections to the main layout by editing `main.php` and including new partial files.

## Browser Support

- IE9+
- Modern browsers (Chrome, Firefox, Safari, Edge)
- Mobile responsive design

## Dependencies

- Bootstrap 3.x
- jQuery 2.1.4
- Font Awesome 4.x
- Owl Carousel
- Magnific Popup
- Revolution Slider 5.0
- CounterUp with WayPoints

## Notes

- All assets are loaded from `/public/assets/theme/da-theme/`
- The layout system is designed to work with CodeIgniter 4
- Make sure the `$Pengaturan` object is always passed to avoid runtime errors
- The theme is fully responsive and mobile-friendly
