<?php
declare(strict_types = 1);

class HttpService {
    public function jsonRequest(string $url): array {
        $raw = file_get_contents($url);

        return json_decode($raw, true);
    }
}
