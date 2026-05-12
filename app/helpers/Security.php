<?php

function csrf_token()
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        return '';
    }

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_input()
{
    $token = htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8');

    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

function csrf_verify()
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        return false;
    }

    $submittedToken = $_POST['csrf_token'] ?? '';
    $storedToken = $_SESSION['csrf_token'] ?? '';

    return is_string($submittedToken)
        && $submittedToken !== ''
        && is_string($storedToken)
        && $storedToken !== ''
        && hash_equals($storedToken, $submittedToken);
}

function validate_image_upload($file, $maxSize = 2097152)
{
    if (!isset($file['error']) || is_array($file['error'])) {
        return "Paramètres invalides.";
    }

    switch ($file['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            return "Aucun fichier envoyé.";
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            return "Le fichier est trop volumineux.";
        default:
            return "Erreur lors de l'envoi.";
    }

    if ($file['size'] > $maxSize) {
        return "Le fichier est trop volumineux (max 2Mo).";
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);
    $allowedMimes = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif'
    ];

    if (!array_key_exists($mimeType, $allowedMimes)) {
        return "Format de fichier invalide. Autorisés : JPG, PNG, WEBP, GIF.";
    }

    return true;
}

function validate_password($password)
{
    if (strlen($password) < 8) {
        return "Le mot de passe doit faire au moins 8 caractères.";
    }

    if (!preg_match('/[A-Z]/', $password)) {
        return "Le mot de passe doit contenir au moins une majuscule.";
    }

    if (!preg_match('/[a-z]/', $password)) {
        return "Le mot de passe doit contenir au moins une minuscule.";
    }

    if (!preg_match('/[0-9]/', $password)) {
        return "Le mot de passe doit contenir au moins un chiffre.";
    }

    return true;
}
