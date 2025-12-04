<?php

if (! function_exists('num_to_words_vn')) {
    /**
     * Convert integer number to Vietnamese words (basic, handles up to billions)
     * Returns string without currency suffix.
     */
    function num_to_words_vn($num)
    {
        $num = (int) $num;
        if ($num === 0) return 'không';

        $units = ['', 'một', 'hai', 'ba', 'bốn', 'năm', 'sáu', 'bảy', 'tám', 'chín'];
        $scales = ['', ' nghìn', ' triệu', ' tỷ'];

        // Split number into groups of 3 digits
        $groups = [];
        while ($num > 0) {
            $groups[] = $num % 1000;
            $num = (int) ($num / 1000);
        }

        $words = [];

        for ($i = count($groups) - 1; $i >= 0; $i--) {
            $n = $groups[$i];
            if ($n === 0) {
                continue;
            }

            $hundreds = (int) ($n / 100);
            $tens = (int) (($n % 100) / 10);
            $ones = $n % 10;

            $groupWords = [];

            if ($hundreds > 0) {
                // handled by num_to_words_vn_three_digits
            }

            if ($hundreds > 0) {
                $groupStr = num_to_words_vn_three_digits($n);
            } else {
                $groupStr = num_to_words_vn_three_digits($n);
            }

            $words[] = trim($groupStr) . $scales[$i];
        }

        return trim(implode(' ', $words));
    }
}

if (! function_exists('num_to_words_vn_three_digits')) {
    function num_to_words_vn_three_digits($n)
    {
        $units = ['', 'một', 'hai', 'ba', 'bốn', 'năm', 'sáu', 'bảy', 'tám', 'chín'];
        $hundreds = (int) ($n / 100);
        $tens = (int) (($n % 100) / 10);
        $ones = $n % 10;
        $parts = [];
        if ($hundreds > 0) {
            $parts[] = $units[$hundreds] . ' trăm';
        }
        if ($tens > 1) {
            $parts[] = $units[$tens] . ' mươi';
            if ($ones == 1) $parts[] = 'mốt';
            elseif ($ones == 5) $parts[] = 'lăm';
            elseif ($ones > 0) $parts[] = $units[$ones];
        } elseif ($tens == 1) {
            $parts[] = 'mười' . ($ones == 0 ? '' : ' ' . ($ones == 5 ? 'lăm' : $units[$ones]));
        } else { // tens == 0
            if ($ones > 0) {
                if ($hundreds > 0) $parts[] = 'lẽ ' . ($ones == 5 ? 'năm' : $units[$ones]);
                else $parts[] = $units[$ones];
            }
        }
        return trim(implode(' ', $parts));
    }
}

if (! function_exists('format_money_in_words')) {
    function format_money_in_words($amount)
    {
        // Accept floats. Split integer đồng and fractional (xu) if present.
        $amount = (float) $amount;
        $whole = (int) floor($amount);
        $fraction = (int) round(($amount - $whole) * 100); // xu (2 decimals)

        $words = num_to_words_vn($whole);
        $words = mb_strtoupper(mb_substr($words, 0, 1)) . mb_substr($words, 1);
        $result = $words . ' đồng';
        if ($fraction > 0) {
            $fractionWords = num_to_words_vn($fraction);
            $result .= ' và ' . $fractionWords . ' xu';
        }
        return $result;
    }
}

if (! function_exists('qr_code_url')) {
    /**
     * Return a URL to generate a QR code (Google Chart API). Lightweight, no composer required.
     */
    function qr_code_url($text, $size = 150)
    {
        $encoded = urlencode($text);
        return "https://chart.googleapis.com/chart?cht=qr&chs={$size}x{$size}&chl={$encoded}&chld=L|0";
    }
}

if (! function_exists('qr_code_data_uri')) {
    /**
     * Generate a data URI for a QR code using bacon/bacon-qr-code (SVG) when available.
     * Falls back to external chart API URL if Bacon is not available.
     */
    function qr_code_data_uri($text, $size = 150)
    {
        // Use Bacon QR to generate SVG (no GD required)
        if (class_exists('BaconQrCode\\Writer')) {
            try {
                $rendererClass = 'BaconQrCode\\Renderer\\Image\\SvgImageBackEnd';
                $styleClass = 'BaconQrCode\\Renderer\\RendererStyle\\RendererStyle';
                $imageRendererClass = 'BaconQrCode\\Renderer\\ImageRenderer';

                if (class_exists($rendererClass) && class_exists($styleClass) && class_exists($imageRendererClass)) {
                    $renderer = new $imageRendererClass(new $styleClass($size), new $rendererClass());
                    $writer = new \BaconQrCode\Writer($renderer);
                    $svg = $writer->writeString($text);
                    // Clean svg xml prolog if any and return base64 data-uri
                    return 'data:image/svg+xml;base64,' . base64_encode($svg);
                }
            } catch (\Throwable $e) {
                // fallthrough to http API
            }
        }

        // Fallback: return URL to Google Chart API
        return qr_code_url($text, $size);
    }
}
