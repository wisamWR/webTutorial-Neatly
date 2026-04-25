<?php

use App\Libraries\JsonStorage;

if (!function_exists('generate_slug')) {
    function generate_slug(string $text): string
    {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9]+/i', '-', $text);
        $text = preg_replace('/-+/', '-', $text);
        $text = trim($text, '-');
        
        return $text;
    }
}

if (!function_exists('generate_uuid4')) {
    function generate_uuid4(): string
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}

if (!function_exists('generate_presentation_url')) {
    function generate_presentation_url(string $judul): string
    {
        return generate_slug($judul) . '-' . generate_uuid4();
    }
}

if (!function_exists('generate_finished_url')) {
    function generate_finished_url(string $judul): string
    {
        return generate_slug($judul) . '-' . generate_uuid4();
    }
}

if (!function_exists('generate_unique_urls')) {
    function generate_unique_urls(string $judul): array
    {
        return [
            'url_presentation' => generate_presentation_url($judul),
            'url_finished'     => generate_finished_url($judul),
        ];
    }
}

if (!function_exists('generate_image_filename')) {
    function generate_image_filename(int $detailId, string $originalName): string
    {
        $ext = pathinfo($originalName, PATHINFO_EXTENSION);
        return "detail_{$detailId}_" . time() . ".{$ext}";
    }
}

if (!function_exists('delete_detail_image')) {
    function delete_detail_image(?string $filename): bool
    {
        if (empty($filename)) {
            return true;
        }

        $path = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . $filename;
        if (file_exists($path)) {
            return unlink($path);
        }
        return true;
    }
}

if (!function_exists('get_tutorial_and_details')) {
    function get_tutorial_and_details(int $tutorialId): array
    {
        $tutorial = JsonStorage::find('tutorials', $tutorialId);
        if (!$tutorial) {
            return ['tutorial' => null, 'details' => []];
        }

        $details = JsonStorage::whereAll('details', ['tutorial_id' => $tutorialId]);
        
        usort($details, function($a, $b) {
            $orderA = $a['order'] ?? 0;
            $orderB = $b['order'] ?? 0;
            return $orderA <=> $orderB;
        });

        return [
            'tutorial' => $tutorial,
            'details' => $details
        ];
    }
}
