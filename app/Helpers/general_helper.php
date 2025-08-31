<?php

if (!function_exists('alnum')) {
    function alnum($string)
    {
        return preg_replace('/[^a-zA-Z0-9]/', '', $string);
    }
}


if (!function_exists('isMenuActive')) {
    /**
     * Check if current menu is active
     *
     * @param string|array $paths Path or array of paths to check
     * @param bool $exact Match exact path or use contains
     * @return bool
     */
    function isMenuActive($paths, bool $exact = false): bool
    {
        $uri = service('uri');
        $segments = $uri->getSegments(); // Get all segments
        $currentPath = implode('/', $segments); // Join segments with /

        // Convert single path to array
        $paths = (array) $paths;

        foreach ($paths as $path) {
            // Remove leading/trailing slashes
            $path = trim($path, '/');

            if ($exact) {
                // Exact path matching
                if ($currentPath === $path) {
                    return true;
                }
            } else {
                // Contains path matching
                if (strpos($currentPath, $path) !== false) {
                    return true;
                }
            }
        }

        return false;
    }
}

if (!function_exists('isStockable')) {
    /**
     * Check if item is stockable and return badge
     * 
     * @param mixed $value Value to check
     * @return string HTML badge element
     */
    function isStockable($value = '1'): string
    {
        if ($value) {
            return br() . '<span class="badge badge-success">Stockable</span>';
        }
        return ''; // Return empty string when not stockable
    }
}

if (!function_exists('jns_klm')) {
    /**
     * Get gender description based on the provided code
     * 
     * @param string $code Gender code
     * @return string Gender description
     */
    function jns_klm(string $code): string
    {
        $genders = [
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
            'B' => 'Banci',
            'G' => 'Gay'
        ];

        return $genders[$code] ?? 'Unknown';
    }
}

if (!function_exists('get_status_badge')) {
    /**
     * Get bootstrap badge class based on PO status
     * 
     * @param int $status Status code
     * @return string Bootstrap badge class
     */
    function get_status_badge($status)
    {
        $badges = [
            0 => 'secondary', // Draft
            1 => 'info',      // Menunggu Persetujuan
            2 => 'primary',   // Disetujui
            3 => 'danger',    // Ditolak
            4 => 'warning',   // Diterima
            5 => 'success'    // Selesai
        ];

        return $badges[$status] ?? 'secondary';
    }
}

if (!function_exists('statusPO')) {
    /**
     * Get PO status label and badge
     * 
     * @param int $status Status code
     * @return array Array containing status label and badge class
     */
    function statusPO($status)
    {
        switch ($status) {
            case 0:
                return [
                    'label' => 'Draft',
                    'badge' => 'secondary'
                ];
            case 1:
                return [
                    'label' => 'Proses',
                    'badge' => 'primary'
                ];
            case 3:
                return [
                    'label' => 'Ditolak',
                    'badge' => 'danger'
                ];
            case 4:
                return [
                    'label' => 'Disetujui',
                    'badge' => 'warning'
                ];
            case 5:
                return [
                    'label' => 'Selesai',
                    'badge' => 'success'
                ];
            default:
                return [
                    'label' => 'Unknown',
                    'badge' => 'secondary'
                ];
        }
    }
}

if (!function_exists('statusGd')) {
    /**
     * Get warehouse status label and badge
     * 
     * @param int $status Status code
     * @return array Array containing status label and badge class
     */
    function statusGd($status)
    {
        switch ($status) {
            case '1':
                return [
                    'label' => 'Utama',
                    'badge' => 'success'
                ];
            case '0':
                return [
                    'label' => '',
                    'badge' => ''
                ];
            default:
                return [
                    'label' => '',
                    'badge' => ''
                ];
        }
    }
}

if (!function_exists('statusHist')) {
    /**
     * Get status history label with badge
     * 
     * @param string $status Status code
     * @return array Label and badge class
     */
    function statusHist($status)
    {
        switch ($status) {
            case '1':
                return [
                    'label' => 'Stok Masuk Pembelian',
                    'badge' => 'success'
                ];
            case '2':
                return [
                    'label' => 'Stok Masuk',
                    'badge' => 'info'
                ];
            case '3':
                return [
                    'label' => 'Stok Masuk Retur Jual',
                    'badge' => 'primary'
                ];
            case '4':
                return [
                    'label' => 'Stok Keluar Penjualan',
                    'badge' => 'danger'
                ];
            case '5':
                return [
                    'label' => 'Stok Keluar Retur Beli',
                    'badge' => 'warning'
                ];
            case '6':
                return [
                    'label' => 'SO',
                    'badge' => 'dark'
                ];
            case '7':
                return [
                    'label' => 'Stok Keluar',
                    'badge' => 'danger'
                ];
            case '8':
                return [
                    'label' => 'Mutasi Antar Gudang',
                    'badge' => 'secondary'
                ];
            default:
                return [
                    'label' => '-',
                    'badge' => 'secondary'
                ];
        }
    }
}

if (!function_exists('statusMutasi')) {
    /**
     * Mendapatkan label dan badge status mutasi transfer gudang.
     * 
     * @param string|int $status
     * @return array
     */
    function statusMutasi($status)
    {
        switch ($status) {
            case '1':
                return [
                    'label' => 'Pindah Gudang',
                    'badge' => 'primary'
                ];
            case '2':
                return [
                    'label' => 'Stok Masuk',
                    'badge' => 'success'
                ];
            case '3':
                return [
                    'label' => 'Stok Keluar',
                    'badge' => 'danger'
                ];
            case '4':
                return [
                    'label' => 'Pindah Outlet',
                    'badge' => 'info'
                ];
            default:
                return [
                    'label' => '-',
                    'badge' => 'secondary'
                ];
        }
    }
}

if (!function_exists('statusOpn')) {
    /**
     * Mendapatkan label dan badge status stok opname.
     * 
     * Status:
     * 0 = Draft
     * 1 = Selesai
     * 2 = Dibatalkan
     * 3 = Dikonfirmasi
     * 
     * @param string|int $status
     * @return array
     */
    function statusOpn($status)
    {
        switch ((string) $status) {
            case '0':
                return [
                    'label' => 'Draft',
                    'badge' => 'secondary'
                ];
            case '1':
                return [
                    'label' => 'Selesai',
                    'badge' => 'success'
                ];
            case '2':
                return [
                    'label' => 'Dibatalkan',
                    'badge' => 'danger'
                ];
            case '3':
                return [
                    'label' => 'Dikonfirmasi',
                    'badge' => 'info'
                ];
            default:
                return [
                    'label' => '-',
                    'badge' => 'secondary'
                ];
        }
    }
}

if (!function_exists('tipeOpn')) {
    /**
     * Mendapatkan label tipe stok opname.
     * 
     * 1 = Gudang
     * 2 = Outlet
     * 
     * @param string|int $tipe
     * @return array
     */
    function tipeOpn($tipe)
    {
        switch ((string) $tipe) {
            case '1':
                return [
                    'label' => 'Gudang',
                    'badge' => 'success'
                ];
            case '2':
                return [
                    'label' => 'Outlet',
                    'badge' => 'secondary'
                ];
            default:
                return [
                    'label' => '-',
                    'badge' => 'secondary'
                ];
        }
    }
}




if (!function_exists('isItemActive')) {
    function isItemActive($status)
    {
        switch ($status) {
            case '1':
                return [
                    'label' => 'Aktif',
                    'badge' => 'success'
                ];
            case '0':
                return [
                    'label' => 'Non Aktif',
                    'badge' => 'danger'
                ];
            default:
                return [
                    'label' => '-',
                    'badge' => 'secondary'
                ];
        }
    }
}

if (!function_exists('statusNota')) {
    /**
     * Mendapatkan label dan badge status nota transfer.
     * 
     * Status:
     * 0 = Draft
     * 1 = Pending
     * 2 = Diproses
     * 3 = Selesai
     * 4 = Dibatalkan
     * 
     * @param string|int $status
     * @return array
     */
    function statusNota($status)
    {
        switch ((string) $status) {
            case '0':
                return [
                    'label' => 'Draft',
                    'badge' => 'secondary'
                ];
            case '1':
                return [
                    'label' => 'Pending',
                    'badge' => 'warning'
                ];
            case '2':
                return [
                    'label' => 'Diproses',
                    'badge' => 'info'
                ];
            case '3':
                return [
                    'label' => 'Selesai',
                    'badge' => 'success'
                ];
            case '4':
                return [
                    'label' => 'Dibatalkan',
                    'badge' => 'danger'
                ];
            default:
                return [
                    'label' => '-',
                    'badge' => 'secondary'
                ];
        }
    }
}

if (!function_exists('tipeMutasi')) {
    /**
     * Mendapatkan label dan badge tipe mutasi transfer.
     * 
     * Tipe:
     * 0 = Draft
     * 1 = Pindah Gudang
     * 2 = Stok Masuk
     * 3 = Stok Keluar
     * 
     * @param string|int $tipe
     * @return array
     */
    function tipeMutasi($tipe)
    {
        switch ((string) $tipe) {
            case '0':
                return [
                    'label' => 'Draft',
                    'badge' => 'secondary'
                ];
            case '1':
                return [
                    'label' => 'Antar Gudang',
                    'badge' => 'info'
                ];
            case '2':
                return [
                    'label' => 'Stok Masuk',
                    'badge' => 'success'
                ];
            case '3':
                return [
                    'label' => 'Stok Keluar',
                    'badge' => 'warning'
                ];
            case '4':
                return [
                    'label' => 'Antar Outlet',
                    'badge' => 'info'
                ];
            default:
                return [
                    'label' => '-',
                    'badge' => 'secondary'
                ];
        }
    }
}

if (!function_exists('getEventStatus')) {
    /**
     * Get event status label and badge
     * 
     * @param int $status Event status (0 = inactive, 1 = active)
     * @return array Array containing status label and badge class
     */
    function getEventStatus($status)
    {
        switch ((int) $status) {
            case 0:
                return [
                    'label' => 'Tidak Aktif',
                    'badge' => 'danger'
                ];
            case 1:
                return [
                    'label' => 'Aktif',
                    'badge' => 'success'
                ];
            default:
                return [
                    'label' => 'Unknown',
                    'badge' => 'secondary'
                ];
        }
    }
}

if (!function_exists('getEventDaysLeft')) {
    /**
     * Calculate days left until event starts
     * 
     * @param string $eventDate Event start date (Y-m-d format)
     * @return int Days left (0 = today, negative = past)
     */
    function getEventDaysLeft($eventDate)
    {
        $eventDateTime = new DateTime($eventDate);
        $today = new DateTime();
        $diff = $today->diff($eventDateTime);

        return $diff->invert ? -$diff->days : $diff->days;
    }
}

if (!function_exists('getEventStatusBadge')) {
    /**
     * Get event status badge based on days left
     * 
     * @param string $eventDate Event start date (Y-m-d format)
     * @return array Array containing status text and badge class
     */
    function getEventStatusBadge($eventDate)
    {
        $daysLeft = getEventDaysLeft($eventDate);

        if ($daysLeft < 0) {
            return [
                'text' => 'Event Selesai',
                'badge' => 'secondary'
            ];
        } elseif ($daysLeft == 0) {
            return [
                'text' => 'Hari Ini!',
                'badge' => 'success'
            ];
        } elseif ($daysLeft <= 7) {
            return [
                'text' => 'Dalam ' . $daysLeft . ' hari',
                'badge' => 'warning'
            ];
        } else {
            return [
                'text' => 'Akan Datang',
                'badge' => 'info'
            ];
        }
    }
}

if (!function_exists('formatEventDate')) {
    /**
     * Format event date range for display
     * 
     * @param string $startDate Event start date
     * @param string $endDate Event end date
     * @param string $format Date format (default: 'd M Y')
     * @return string Formatted date string
     */
    function formatEventDate($startDate, $endDate = null, $format = 'd M Y')
    {
        $start = date($format, strtotime($startDate));

        if ($endDate && $endDate != $startDate) {
            $end = date($format, strtotime($endDate));
            return $start . ' - ' . $end;
        }

        return $start;
    }
}

if (!function_exists('getEventCapacityInfo')) {
    /**
     * Get event capacity information
     * 
     * @param int $totalCapacity Total event capacity
     * @param int $registeredCount Number of registered participants
     * @return array Array containing capacity information
     */
    function getEventCapacityInfo($totalCapacity, $registeredCount = 0)
    {
        if ($totalCapacity == 0) {
            return [
                'text' => 'Tak Terbatas',
                'percentage' => 0,
                'is_full' => false,
                'available' => 'Unlimited'
            ];
        }

        $percentage = round(($registeredCount / $totalCapacity) * 100);
        $available = $totalCapacity - $registeredCount;
        $is_full = $registeredCount >= $totalCapacity;

        return [
            'text' => $registeredCount . ' / ' . $totalCapacity,
            'percentage' => $percentage,
            'is_full' => $is_full,
            'available' => $available
        ];
    }
}

if (!function_exists('getEventCategoryBadge')) {
    /**
     * Get event category badge
     * 
     * @param string $categoryName Category name
     * @param string $badgeClass Badge class (default: 'primary')
     * @return string HTML badge element
     */
    function getEventCategoryBadge($categoryName, $badgeClass = 'primary')
    {
        if (empty($categoryName)) {
            return '';
        }

        return '<span class="badge badge-' . $badgeClass . '">' . esc($categoryName) . '</span>';
    }
}

if (!function_exists('formatEventTime')) {
    /**
     * Format event time range for display
     * 
     * @param string $startTime Event start time
     * @param string $endTime Event end time
     * @return string Formatted time string
     */
    function formatEventTime($startTime, $endTime = null)
    {
        if (empty($startTime)) {
            return '';
        }

        if ($endTime && $endTime != $startTime) {
            return $startTime . ' - ' . $endTime;
        }

        return $startTime;
    }
}

if (!function_exists('getEventLocationInfo')) {
    /**
     * Get event location information with icon
     * 
     * @param string $location Event location
     * @param bool $showIcon Whether to show location icon
     * @return string Formatted location string
     */
    function getEventLocationInfo($location, $showIcon = true)
    {
        if (empty($location)) {
            return '';
        }

        $icon = $showIcon ? '<i class="fa fa-map-marker"></i> ' : '';
        return $icon . esc($location);
    }
}

if (!function_exists('formatCurrency')) {
    /**
     * Format currency for display
     * 
     * @param float $amount Amount to format
     * @param string $currency Currency code (default: 'IDR')
     * @param bool $showSymbol Whether to show currency symbol
     * @return string Formatted currency string
     */
    function formatCurrency($amount, $currency = 'IDR', $showSymbol = true)
    {
        if ($amount == 0) {
            return 'Gratis';
        }

        $formatted = number_format($amount, 0, ',', '.');

        if ($showSymbol) {
            switch ($currency) {
                case 'IDR':
                    return 'Rp ' . $formatted;
                case 'USD':
                    return '$' . $formatted;
                case 'EUR':
                    return '€' . $formatted;
                default:
                    return $formatted;
            }
        }

        return $formatted;
    }
}

if (!function_exists('getTimeAgo')) {
    /**
     * Get human readable time ago
     * 
     * @param string $datetime Datetime string
     * @param string $format Date format (default: 'Y-m-d H:i:s')
     * @return string Human readable time ago
     */
    function getTimeAgo($datetime, $format = 'Y-m-d H:i:s')
    {
        $time = strtotime($datetime);
        $now = time();
        $diff = $now - $time;

        if ($diff < 60) {
            return 'Baru saja';
        } elseif ($diff < 3600) {
            $minutes = floor($diff / 60);
            return $minutes . ' menit yang lalu';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours . ' jam yang lalu';
        } elseif ($diff < 2592000) {
            $days = floor($diff / 86400);
            return $days . ' hari yang lalu';
        } elseif ($diff < 31536000) {
            $months = floor($diff / 2592000);
            return $months . ' bulan yang lalu';
        } else {
            $years = floor($diff / 31536000);
            return $years . ' tahun yang lalu';
        }
    }
}

if (!function_exists('getFileSize')) {
    /**
     * Get human readable file size
     * 
     * @param int $bytes File size in bytes
     * @param int $precision Number of decimal places
     * @return string Human readable file size
     */
    function getFileSize($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}

if (!function_exists('getStatusColor')) {
    /**
     * Get Bootstrap color class based on status
     * 
     * @param string $status Status string
     * @return string Bootstrap color class
     */
    function getStatusColor($status)
    {
        $status = strtolower($status);

        $colors = [
            'active' => 'success',
            'aktif' => 'success',
            'pending' => 'warning',
            'menunggu' => 'warning',
            'processing' => 'info',
            'diproses' => 'info',
            'completed' => 'success',
            'selesai' => 'success',
            'cancelled' => 'danger',
            'dibatalkan' => 'danger',
            'rejected' => 'danger',
            'ditolak' => 'danger',
            'draft' => 'secondary',
            'draft' => 'secondary',
            'inactive' => 'secondary',
            'nonaktif' => 'secondary'
        ];

        return $colors[$status] ?? 'secondary';
    }
}

if (!function_exists('truncateText')) {
    /**
     * Truncate text to specified length
     * 
     * @param string $text Text to truncate
     * @param int $length Maximum length
     * @param string $suffix Suffix to add if truncated
     * @return string Truncated text
     */
    function truncateText($text, $length = 100, $suffix = '...')
    {
        if (strlen($text) <= $length) {
            return $text;
        }

        return substr($text, 0, $length) . $suffix;
    }
}

if (!function_exists('getRandomColor')) {
    /**
     * Get random Bootstrap color class
     * 
     * @return string Random Bootstrap color class
     */
    function getRandomColor()
    {
        $colors = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'];
        return $colors[array_rand($colors)];
    }
}

if (!function_exists('formatPhoneNumber')) {
    /**
     * Format phone number for display
     * 
     * @param string $phone Phone number
     * @param string $country Country code (default: 'ID')
     * @return string Formatted phone number
     */
    function formatPhoneNumber($phone, $country = 'ID')
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if ($country == 'ID') {
            // Indonesian phone number format
            if (strlen($phone) == 11 && substr($phone, 0, 1) == '0') {
                return '+62' . substr($phone, 1);
            } elseif (strlen($phone) == 12 && substr($phone, 0, 2) == '62') {
                return '+' . $phone;
            } elseif (strlen($phone) == 13 && substr($phone, 0, 3) == '+62') {
                return $phone;
            }
        }

        return $phone;
    }
}

if (!function_exists('getInitials')) {
    /**
     * Get initials from name
     * 
     * @param string $name Full name
     * @param int $maxLength Maximum number of initials
     * @return string Initials
     */
    function getInitials($name, $maxLength = 2)
    {
        $words = explode(' ', trim($name));
        $initials = '';

        foreach ($words as $word) {
            if (strlen($initials) >= $maxLength) {
                break;
            }
            if (!empty($word)) {
                $initials .= strtoupper(substr($word, 0, 1));
            }
        }

        return $initials;
    }
}

if (!function_exists('isValidEmail')) {
    /**
     * Check if email is valid
     * 
     * @param string $email Email to validate
     * @return bool True if valid, false otherwise
     */
    function isValidEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}

if (!function_exists('getAge')) {
    /**
     * Calculate age from birth date
     * 
     * @param string $birthDate Birth date (Y-m-d format)
     * @return int Age in years
     */
    function getAge($birthDate)
    {
        $birth = new DateTime($birthDate);
        $today = new DateTime();
        $age = $today->diff($birth);
        return $age->y;
    }
}

if (!function_exists('getMonthName')) {
    /**
     * Get month name in Indonesian
     * 
     * @param int $month Month number (1-12)
     * @param bool $short Whether to return short name
     * @return string Month name
     */
    function getMonthName($month, $short = false)
    {
        $months = [
            1 => ['Januari', 'Jan'],
            2 => ['Februari', 'Feb'],
            3 => ['Maret', 'Mar'],
            4 => ['April', 'Apr'],
            5 => ['Mei', 'Mei'],
            6 => ['Juni', 'Jun'],
            7 => ['Juli', 'Jul'],
            8 => ['Agustus', 'Ags'],
            9 => ['September', 'Sep'],
            10 => ['Oktober', 'Okt'],
            11 => ['November', 'Nov'],
            12 => ['Desember', 'Des']
        ];

        $index = $short ? 1 : 0;
        return $months[$month][$index] ?? 'Unknown';
    }
}

if (!function_exists('generateSlug')) {
    /**
     * Generate URL-friendly slug from text
     * 
     * @param string $text Text to convert to slug
     * @param string $separator Separator character (default: '-')
     * @return string URL-friendly slug
     */
    function generateSlug($text, $separator = '-')
    {
        // Convert to lowercase
        $text = strtolower($text);

        // Replace non-alphanumeric characters with separator
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);

        // Replace multiple spaces/hyphens with single separator
        $text = preg_replace('/[\s-]+/', $separator, $text);

        // Trim separators from start and end
        return trim($text, $separator);
    }
}

if (!function_exists('maskEmail')) {
    /**
     * Mask email address for privacy
     * 
     * @param string $email Email address to mask
     * @param string $mask Character to use for masking (default: '*')
     * @return string Masked email address
     */
    function maskEmail($email, $mask = '*')
    {
        if (!isValidEmail($email)) {
            return $email;
        }

        $parts = explode('@', $email);
        $username = $parts[0];
        $domain = $parts[1];

        if (strlen($username) <= 2) {
            $maskedUsername = $username;
        } else {
            $maskedUsername = substr($username, 0, 1) . str_repeat($mask, strlen($username) - 2) . substr($username, -1);
        }

        $domainParts = explode('.', $domain);
        $maskedDomain = substr($domainParts[0], 0, 1) . str_repeat($mask, strlen($domainParts[0]) - 1);

        return $maskedUsername . '@' . $maskedDomain . '.' . $domainParts[1];
    }
}

if (!function_exists('formatNumber')) {
    /**
     * Format number with Indonesian locale
     * 
     * @param float $number Number to format
     * @param int $decimals Number of decimal places (default: 0)
     * @return string Formatted number
     */
    function formatNumber($number, $decimals = 0)
    {
        return number_format($number, $decimals, ',', '.');
    }
}

if (!function_exists('getDayName')) {
    /**
     * Get day name in Indonesian
     * 
     * @param int $day Day number (0-6, where 0 is Sunday)
     * @param bool $short Whether to return short name
     * @return string Day name
     */
    function getDayName($day, $short = false)
    {
        $days = [
            0 => ['Minggu', 'Min'],
            1 => ['Senin', 'Sen'],
            2 => ['Selasa', 'Sel'],
            3 => ['Rabu', 'Rab'],
            4 => ['Kamis', 'Kam'],
            5 => ['Jumat', 'Jum'],
            6 => ['Sabtu', 'Sab']
        ];

        $index = $short ? 1 : 0;
        return $days[$day][$index] ?? 'Unknown';
    }
}

if (!function_exists('calculatePercentage')) {
    /**
     * Calculate percentage with proper formatting
     * 
     * @param float $part Part value
     * @param float $total Total value
     * @param int $decimals Number of decimal places (default: 1)
     * @return string Formatted percentage
     */
    function calculatePercentage($part, $total, $decimals = 1)
    {
        if ($total == 0) {
            return '0%';
        }

        $percentage = ($part / $total) * 100;
        return number_format($percentage, $decimals) . '%';
    }
}

if (!function_exists('getFileExtension')) {
    /**
     * Get file extension from filename
     * 
     * @param string $filename Filename
     * @return string File extension (without dot)
     */
    function getFileExtension($filename)
    {
        return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    }
}

if (!function_exists('isImageFile')) {
    /**
     * Check if file is an image based on extension
     * 
     * @param string $filename Filename to check
     * @return bool True if image file
     */
    function isImageFile($filename)
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
        $extension = getFileExtension($filename);
        return in_array($extension, $imageExtensions);
    }
}

if (!function_exists('getRandomString')) {
    /**
     * Generate random string
     * 
     * @param int $length Length of string (default: 8)
     * @param bool $includeSpecial Include special characters (default: false)
     * @return string Random string
     */
    function getRandomString($length = 8, $includeSpecial = false)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        if ($includeSpecial) {
            $chars .= '!@#$%^&*()_+-=[]{}|;:,.<>?';
        }

        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= $chars[rand(0, strlen($chars) - 1)];
        }

        return $string;
    }
}

if (!function_exists('formatDuration')) {
    /**
     * Format duration in seconds to human readable format
     * 
     * @param int $seconds Duration in seconds
     * @return string Formatted duration
     */
    function formatDuration($seconds)
    {
        if ($seconds < 60) {
            return $seconds . ' detik';
        } elseif ($seconds < 3600) {
            $minutes = floor($seconds / 60);
            return $minutes . ' menit';
        } elseif ($seconds < 86400) {
            $hours = floor($seconds / 3600);
            return $hours . ' jam';
        } else {
            $days = floor($seconds / 86400);
            return $days . ' hari';
        }
    }
}

if (!function_exists('truncateEventDescription')) {
    /**
     * Truncate event description with proper formatting
     * 
     * @param string $description Event description text
     * @param int $maxLength Maximum length (default: 160)
     * @param string $fallback Fallback text if description is empty
     * @return string Truncated description
     */
    function shortDescription($description, $maxLength = 160, $fallback = 'Deskripsi event belum tersedia.')
    {
        if (empty($description)) {
            return $fallback;
        }

        // Remove all HTML tags and entities
        $cleanText = strip_tags($description);
        $cleanText = html_entity_decode($cleanText, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Check if truncation is needed
        if (mb_strlen($cleanText) <= $maxLength) {
            return $cleanText;
        }

        // Truncate and add ellipsis
        $truncated = mb_strimwidth($cleanText, 0, $maxLength, '...');
        return $truncated;
    }
}

if (!function_exists('generatePagination')) {
    /**
     * Generate pagination HTML with Bootstrap styling
     * 
     * @param int $currentPage Current page number
     * @param int $totalPages Total number of pages
     * @param string $baseUrl Base URL for pagination links
     * @param array $options Additional options for pagination
     * @return string HTML pagination
     */
    function generatePagination($currentPage, $totalPages, $baseUrl = '', $options = [])
    {
        // Default options
        $defaults = [
            'maxVisible' => 5,        // Maximum visible page numbers
            'showFirstLast' => true,  // Show first/last buttons
            'showPrevNext' => true,   // Show previous/next buttons
            'ulClass' => 'pagination',
            'liClass' => '',
            'activeClass' => 'active',
            'disabledClass' => 'disabled',
            'prevText' => '&laquo;',
            'nextText' => '&raquo;',
            'firstText' => '&laquo;&laquo;',
            'lastText' => '&raquo;&raquo;'
        ];

        $options = array_merge($defaults, $options);

        if ($totalPages <= 1) {
            return '';
        }

        $html = '<ul class="' . $options['ulClass'] . '">';

        // First page button
        if ($options['showFirstLast'] && $currentPage > 1) {
            $html .= '<li class="' . $options['liClass'] . '">';
            $html .= '<a href="' . $baseUrl . '?page=1" aria-label="First">';
            $html .= '<span aria-hidden="true">' . $options['firstText'] . '</span>';
            $html .= '</a></li>';
        }

        // Previous button
        if ($options['showPrevNext'] && $currentPage > 1) {
            $html .= '<li class="' . $options['liClass'] . '">';
            $html .= '<a href="' . $baseUrl . '?page=' . ($currentPage - 1) . '" aria-label="Previous">';
            $html .= '<span aria-hidden="true">' . $options['prevText'] . '</span>';
            $html .= '</a></li>';
        }

        // Calculate start and end page numbers
        $startPage = max(1, $currentPage - floor($options['maxVisible'] / 2));
        $endPage = min($totalPages, $startPage + $options['maxVisible'] - 1);

        // Adjust start page if we're near the end
        if ($endPage - $startPage + 1 < $options['maxVisible']) {
            $startPage = max(1, $endPage - $options['maxVisible'] + 1);
        }

        // Page numbers
        for ($i = $startPage; $i <= $endPage; $i++) {
            $activeClass = ($i == $currentPage) ? ' ' . $options['activeClass'] : '';
            $html .= '<li class="' . $options['liClass'] . $activeClass . '">';
            $html .= '<a href="' . $baseUrl . '?page=' . $i . '">' . $i . '</a>';
            $html .= '</li>';
        }

        // Next button
        if ($options['showPrevNext'] && $currentPage < $totalPages) {
            $html .= '<li class="' . $options['liClass'] . '">';
            $html .= '<a href="' . $baseUrl . '?page=' . ($currentPage + 1) . '" aria-label="Next">';
            $html .= '<span aria-hidden="true">' . $options['nextText'] . '</span>';
            $html .= '</a></li>';
        }

        // Last page button
        if ($options['showFirstLast'] && $currentPage < $totalPages) {
            $html .= '<li class="' . $options['liClass'] . '">';
            $html .= '<a href="' . $baseUrl . '?page=' . $totalPages . '" aria-label="Last">';
            $html .= '<span aria-hidden="true">' . $options['lastText'] . '</span>';
            $html .= '</a></li>';
        }

        $html .= '</ul>';

        return $html;
    }

    if (!function_exists('cleanText')) {
        /**
         * Clean text by removing HTML tags, trimming whitespace, and optionally removing extra spaces.
         *
         * @param string $text The text to clean.
         * @param bool $removeExtraSpaces Whether to remove multiple spaces (default: true).
         * @return string Cleaned text.
         */
        function cleanText($text, $removeExtraSpaces = true)
        {
            // Remove HTML tags
            $text = strip_tags($text);

            // Decode HTML entities
            $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

            // Trim whitespace from beginning and end
            $text = trim($text);

            // Optionally remove extra spaces
            if ($removeExtraSpaces) {
                $text = preg_replace('/\s+/', ' ', $text);
            }

            return $text;
        }
    }
}
