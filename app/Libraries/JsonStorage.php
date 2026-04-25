<?php

namespace App\Libraries;

class JsonStorage
{
    public const DATA_PATH = WRITEPATH . 'data' . DIRECTORY_SEPARATOR;

    /**
     * Helper to ensure the directory exists
     */
    private static function now(): string
    {
        return (new \DateTime('now', new \DateTimeZone('Asia/Jakarta')))->format('Y-m-d H:i:s');
    }

    private static function checkPath(): void
    {
        if (!is_dir(self::DATA_PATH)) {
            mkdir(self::DATA_PATH, 0755, true);
        }
    }

    /**
     * Read the JSON file and return as array.
     *
     * @param string $filename
     * @return array
     */
    public static function read(string $filename): array
    {
        self::checkPath();
        $file = self::DATA_PATH . $filename . '.json';
        if (!file_exists($file)) {
            return [];
        }

        $content = file_get_contents($file);
        if (trim($content) === '') {
            return [];
        }

        $data = json_decode($content, true);
        return is_array($data) ? $data : [];
    }

    /**
     * Write data array to JSON file.
     *
     * @param string $filename
     * @param array $data
     * @return bool
     */
    public static function write(string $filename, array $data): bool
    {
        self::checkPath();
        $file = self::DATA_PATH . $filename . '.json';
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            return false;
        }

        return file_put_contents($file, $json) !== false;
    }

    /**
     * Insert a new row with auto-increment ID and timestamps.
     *
     * @param string $filename
     * @param array $row
     * @return array
     */
    public static function insert(string $filename, array $row): array
    {
        $data = self::read($filename); //baca seluruh file JSON
        
        //cari ID tertinggi untuk auto increment
        $maxId = 0;
        foreach ($data as $item) {
            if (isset($item['id']) && $item['id'] > $maxId) {
                $maxId = $item['id'];
            }
        }
        
        //tambahkan meta data
        $row['id'] = $maxId + 1;
        $row['created_at'] = self::now();
        $row['updated_at'] = self::now();
        
        $data[] = $row;         //append ke array data
        self::write($filename, $data);  //tulis kembali ke file JSON
        
        return $row;
    }

    /**
     * Update an existing row. ID and created_at are protected.
     *
     * @param string $filename
     * @param int $id
     * @param array $row
     * @return bool
     */
    public static function update(string $filename, int $id, array $row): bool
    {
        $data = self::read($filename);
        $found = false;
        
        foreach ($data as $index => $item) {
            if (isset($item['id']) && $item['id'] == $id) {
                $existingCreatedAt = $item['created_at'] ?? self::now();
                
                $updatedRow = array_merge($item, $row);
                $updatedRow['id'] = $id;
                $updatedRow['created_at'] = $existingCreatedAt;
                $updatedRow['updated_at'] = self::now();
                
                $data[$index] = $updatedRow;
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            return false;
        }
        
        return self::write($filename, $data);
    }

    /**
     * Delete a row by ID.
     *
     * @param string $filename
     * @param int $id
     * @return bool
     */
    public static function delete(string $filename, int $id): bool
    {
        $data = self::read($filename);
        $initialCount = count($data);
        
        $data = array_values(array_filter($data, function ($item) use ($id) {
            return isset($item['id']) && $item['id'] != $id;
        }));
        
        if (count($data) === $initialCount) {
            return false;
        }
        
        return self::write($filename, $data);
    }

    /**
     * Find a single row by ID.
     *
     * @param string $filename
     * @param int $id
     * @return array|null
     */
    public static function find(string $filename, int $id): ?array
    {
        $data = self::read($filename);
        foreach ($data as $item) {
            if (isset($item['id']) && $item['id'] == $id) {
                return $item;
            }
        }
        return null;
    }

    /**
     * Filter rows by key matching a value.
     *
     * @param string $filename
     * @param string $key
     * @param mixed $value
     * @return array
     */
    public static function where(string $filename, string $key, $value): array
    {
        $data = self::read($filename);
        return array_values(array_filter($data, function ($item) use ($key, $value) {
            return isset($item[$key]) && $item[$key] == $value;
        }));
    }

    /**
     * Check if a row with specific key and value exists (useful for uniqueness check).
     *
     * @param string $filename
     * @param string $key
     * @param mixed $value
     * @param int|null $excludeId
     * @return bool
     */
    public static function exists(string $filename, string $key, $value, ?int $excludeId = null): bool
    {
        $data = self::read($filename);
        foreach ($data as $item) {
            if (isset($item[$key]) && $item[$key] == $value) {
                if ($excludeId !== null && isset($item['id']) && $item['id'] == $excludeId) {
                    continue;
                }
                return true;
            }
        }
        return false;
    }

    /**
     * Filter rows by multiple conditions (AND logic).
     *
     * @param string $filename
     * @param array $conditions
     * @return array
     */
    public static function whereAll(string $filename, array $conditions): array
    {
        $data = self::read($filename);
        return array_values(array_filter($data, function ($item) use ($conditions) {
            foreach ($conditions as $key => $value) {
                if (!isset($item[$key]) || $item[$key] != $value) {
                    return false;
                }
            }
            return true;
        }));
    }

    /**
     * Find the maximum order value, optionally filtered by conditions.
     *
     * @param string $filename
     * @param array $conditions
     * @return int
     */
    public static function maxOrder(string $filename, array $conditions = []): int
    {
        $data = self::read($filename);
        $max = 0;
        foreach ($data as $item) {
            $match = true;
            foreach ($conditions as $k => $v) {
                if (!isset($item[$k]) || $item[$k] != $v) {
                    $match = false;
                    break;
                }
            }
            
            if ($match && isset($item['order'])) {
                if ($item['order'] > $max) {
                    $max = $item['order'];
                }
            }
        }
        return (int)$max;
    }
}
