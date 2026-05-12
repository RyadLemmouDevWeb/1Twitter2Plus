<?php

function handle_file_upload($file, $subDir)
{
    if ($file && isset($file['error']) && $file['error'] === UPLOAD_ERR_OK) {
        $validation = validate_image_upload($file);
        if ($validation !== true) {
            return null;
        }

        $uploadDir = __DIR__ . '/../../public/uploads/' . $subDir . '/';

        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
                return null;
            }
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);
        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif'
        ];
        $extension = $extensions[$mimeType] ?? 'jpg';

        $newFileName = uniqid('up_') . '.' . $extension;
        $uploadFilePath = $uploadDir . $newFileName;

        if (move_uploaded_file($file['tmp_name'], $uploadFilePath)) {
            return '/uploads/' . $subDir . '/' . $newFileName;
        }
    }
    
    return null;
}
