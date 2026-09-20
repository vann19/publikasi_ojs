<?php

function fetchOjsArticles($oaiUrl) {
    // Inisialisasi cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $oaiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Bypass SSL jika di local
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Timeout 10 detik

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200 || !$response) {
        return [];
    }

    try {
        $xml = new SimpleXMLElement($response);
    } catch (Exception $e) {
        return [];
    }

    $articles = [];

    if (isset($xml->ListRecords->record)) {
        foreach ($xml->ListRecords->record as $record) {
            // Abaikan artikel yang dihapus/archived dari OJS
            if (isset($record->header['status']) && (string)$record->header['status'] === 'deleted') {
                continue;
            }

            // OAI Menggunakan Namespace Dublin Core (oai_dc & dc)
            $metadata = $record->metadata->children('http://www.openarchives.org/OAI/2.0/oai_dc/');
            if (!$metadata) continue;
            
            $dc = $metadata->children('http://purl.org/dc/elements/1.1/');

            // Extract multi-author
            $authors = [];
            if (isset($dc->creator)) {
                foreach ($dc->creator as $creator) {
                    $authors[] = (string)$creator;
                }
            }

            // Extract DOI & Article URL
            $url = null;
            $doi = null;
            if (isset($dc->identifier)) {
                foreach ($dc->identifier as $identifier) {
                    $strId = (string)$identifier;
                    if (filter_var($strId, FILTER_VALIDATE_URL)) {
                        $url = $strId;
                    } elseif (str_contains($strId, '10.')) {
                        $doi = $strId;
                    }
                }
            }

            $articles[] = [
                'oai_id'         => (string)$record->header->identifier,
                'title'          => (string)$dc->title,
                'authors'        => implode(', ', $authors),
                'abstract'       => (string)$dc->description,
                'published_date' => (string)$dc->date,
                'publisher'      => (string)$dc->publisher,
                'source'         => isset($dc->source[0]) ? (string)$dc->source[0] : null, // Berisi Info Vol & No
                'doi'            => $doi,
                'url'            => $url,
            ];
        }
    }

    return $articles;
}

function getCoverFromOjsUrl($url) {
    // Ambil isi HTML halaman artikel OJS dengan stream context dan timeout singkat
    $context = stream_context_create([
        'http' => [
            'header' => "User-Agent: Mozilla/5.0\r\n",
            'timeout' => 3 // Maksimal tunggu 3 detik supaya tidak hang
        ],
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
        ]
    ]);
    
    $html = @file_get_contents($url, false, $context);
    if (!$html) return null;

    // 1. Cari tag meta og:image menggunakan regex
    if (preg_match('/<meta\s+property="og:image"\s+content="([^"]+)"/i', $html, $matches)) {
        return $matches[1]; 
    }

    // 2. Fallback 1: Cari logo header jurnal (karena tema OJS ini tidak punya og:image)
    if (preg_match('/<img[^>]+src="([^"]+pageHeaderLogoImage[^"]+)"/i', $html, $matches)) {
        return $matches[1];
    }
    
    // 3. Fallback 2: Cari gambar cover issue
    if (preg_match('/<img[^>]+src="([^"]+cover_issue[^"]+)"/i', $html, $matches)) {
        return $matches[1];
    }

    return null;
}

function fetchOjsJournals($oaiUrl) {
    $cacheFile = __DIR__ . '/../storage/journals_cache.json';
    $cacheTime = 3600 * 24; // Cache 24 jam karena proses scraping og:image agak lambat

    // 1. Cek Cache
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTime) {
        $cachedData = @file_get_contents($cacheFile);
        if ($cachedData) {
            $journals = json_decode($cachedData, true);
            if (is_array($journals) && count($journals) > 0) {
                return $journals;
            }
        }
    }

    // 2. Fetch OAI jika cache tidak ada atau expire
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $oaiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200 || !$response) {
        return []; // Jika gagal fetch OAI
    }

    try {
        $xml = new SimpleXMLElement($response);
    } catch (Exception $e) {
        return [];
    }

    $journals = [];
    $colors = ['bg-emerald-700', 'bg-sky-800', 'bg-purple-700', 'bg-teal-700', 'bg-orange-600', 'bg-green-800', 'bg-rose-800', 'bg-slate-700'];
    $colorIndex = 0;

    if (isset($xml->ListSets->set)) {
        foreach ($xml->ListSets->set as $set) {
            $setSpec = (string)$set->setSpec;
            
            // Abaikan section
            if (str_contains($setSpec, ':')) {
                continue;
            }

            $journalUrl = "https://e-journal.nawaedukasi.org/index.php/" . $setSpec;
            
            // Scraping cover
            $coverUrl = getCoverFromOjsUrl($journalUrl);

            $journals[] = [
                'path'      => $setSpec,
                'judul'     => (string)$set->setName,
                'deskripsi' => 'Jurnal ilmiah ' . (string)$set->setName . ' dari Nawa Edukasi.',
                'issn'      => 'ISSN',
                'sinta'     => 'SINTA',
                'warna'     => $colors[$colorIndex % count($colors)],
                'url'       => $journalUrl,
                'cover'     => $coverUrl // Tambah properti cover
            ];
            
            $colorIndex++;
        }
    }

    // 3. Simpan ke Cache
    if (!empty($journals)) {
        if (!is_dir(__DIR__ . '/../storage')) {
            mkdir(__DIR__ . '/../storage', 0755, true);
        }
        file_put_contents($cacheFile, json_encode($journals, JSON_PRETTY_PRINT));
    }

    return $journals;
}

