<?php
namespace ArtworkSystem;

class ArtworkFileManager {
    private $pdo;
    private $baseUploadPath;
    private $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    public function __construct(\PDO $pdo, string $baseUploadPath) {
        $this->pdo = $pdo;
        $this->baseUploadPath = rtrim($baseUploadPath, '/');
    }

    public function processArtworkUpload(array $files, array $metadata): int {
        try {
            $this->pdo->beginTransaction();

            // Get author information
            $stmt = $this->pdo->prepare("SELECT id, fname, lname FROM people WHERE id = ?");
            $stmt->execute([$metadata['author_id']]);
            $author = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$author) {
                throw new \Exception("Author not found");
            }

            // Generate directory path using author's standardized name
            $authorPath = $this->formatAuthorPath($author['fname'], $author['lname']);
            $pieceNumber = str_pad($metadata['piece_number'], 4, '0', STR_PAD_LEFT);

            // Create paths for web and full versions
            $webPath = "{$this->baseUploadPath}/{$authorPath}/{$pieceNumber}/WEB";
            $fullPath = "{$this->baseUploadPath}/{$authorPath}/{$pieceNumber}/FULL";

            $this->createDirectories([$webPath, $fullPath]);

            // Process both versions of the image
            $webFilename = $this->moveFile($files['web_version'], $webPath);
            $fullFilename = $this->moveFile($files['full_version'], $fullPath);

            // Insert artwork record
            $artworkId = $this->insertArtwork([
                'author_id' => $author['id'],
                'piece_name' => $metadata['piece_name'],
                'piece_number' => $metadata['piece_number'],
                'web_filename' => "{$authorPath}/{$pieceNumber}/WEB/{$webFilename}",
                'full_filename' => "{$authorPath}/{$pieceNumber}/FULL/{$fullFilename}",
                'year' => $metadata['year'] ?? null,
                'description' => $metadata['description'] ?? null,
                'medium_type' => $metadata['medium_type'] ?? null
            ]);

            if (!empty($metadata['keywords'])) {
                $this->processKeywords($artworkId, explode(',', $metadata['keywords']));
            }

            $this->pdo->commit();
            return $artworkId;

        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    private function formatAuthorPath(string $fname, string $lname): string {
        $path = strtolower("{$fname}_{$lname}");
        return preg_replace('/[^a-z0-9_]/', '', $path);
    }

    private function createDirectories(array $paths): void {
        foreach ($paths as $path) {
            if (!file_exists($path)) {
                if (!mkdir($path, 0755, true)) {
                    throw new \Exception("Failed to create directory: {$path}");
                }
            }
        }
    }

    private function moveFile(array $file, string $destination): string {
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $this->allowedExtensions)) {
            throw new \Exception("Invalid file type: {$extension}");
        }

        $filename = $this->generateUniqueFilename($destination, $extension);
        $fullPath = "{$destination}/{$filename}";

        if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
            throw new \Exception("Failed to move uploaded file");
        }

        return $filename;
    }

    private function generateUniqueFilename(string $path, string $extension): string {
        do {
            $filename = uniqid() . '.' . $extension;
        } while (file_exists("{$path}/{$filename}"));

        return $filename;
    }

    private function insertArtwork(array $data): int {
        $sql = "INSERT INTO artworks (
                    author_id, piece_name, piece_number,
                    web_filename, full_filename, piece_year,
                    piece_description, medium_type
                ) VALUES (
                    :author_id, :piece_name, :piece_number,
                    :web_filename, :full_filename, :year,
                    :description, :medium_type
                )";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':author_id' => $data['author_id'],
            ':piece_name' => $data['piece_name'],
            ':piece_number' => $data['piece_number'],
            ':web_filename' => $data['web_filename'],
            ':full_filename' => $data['full_filename'],
            ':year' => $data['year'],
            ':description' => $data['description'],
            ':medium_type' => $data['medium_type']
        ]);

        return $this->pdo->lastInsertId();
    }

    private function processKeywords(int $artworkId, array $keywords): void {
        // First insert any new keywords
        foreach ($keywords as $keyword) {
            $keyword = trim($keyword);
            if (empty($keyword)) continue;

            $stmt = $this->pdo->prepare("INSERT IGNORE INTO keywords (keyword) VALUES (?)");
            $stmt->execute([$keyword]);
        }

        // Then link keywords to artwork
        $placeholders = str_repeat('?,', count($keywords) - 1) . '?';
        $sql = "INSERT INTO artworks_x_keywords (artwork_id, keyword_id)
                SELECT ?, id FROM keywords WHERE keyword IN ($placeholders)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_merge([$artworkId], $keywords));
    }
}