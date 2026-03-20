<?php defined('BASEPATH') or exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 * Smarter Dog - Online Appointment Scheduler
 *
 * @package     SmarterDog
 * @author      A.Tselegidis <alextselegidis@gmail.com>
 * @copyright   Copyright (c) Alex Tselegidis
 * @license     https://opensource.org/licenses/GPL-3.0 - GPLv3
 * @link        https://easyappointments.org
 * @since       v1.0.0
 * ---------------------------------------------------------------------------- */

/**
 * Legacy password hashing using iterated SHA-256.
 *
 * Retained for verifying existing password hashes during migration to bcrypt.
 *
 * @param string $salt Salt value for current user.
 * @param string $password Given string password.
 *
 * @return string Returns the legacy hash string.
 */
function legacy_hash_password(string $salt, string $password): string
{
    $half = (int) (strlen($salt) / 2);

    $hash = hash('sha256', substr($salt, 0, $half) . $password . substr($salt, $half));

    for ($i = 0; $i < 100000; $i++) {
        $hash = hash('sha256', $hash);
    }

    return $hash;
}

/**
 * Generate a hash of password string using bcrypt.
 *
 * @param string $salt Unused, kept for backward compatibility with callers.
 * @param string $password Given string password.
 *
 * @return string Returns the bcrypt hash.
 *
 * @throws InvalidArgumentException
 */
function hash_password(string $salt, string $password): string
{
    if (strlen($password) > MAX_PASSWORD_LENGTH) {
        throw new InvalidArgumentException('The provided password is too long, please use a shorter value.');
    }

    return password_hash($password, PASSWORD_BCRYPT);
}

/**
 * Verify a password against a stored hash.
 *
 * Supports both bcrypt hashes (starting with $2y$) and legacy SHA-256 hashes.
 *
 * @param string $password Plain text password to verify.
 * @param string $stored_hash The stored hash from the database.
 * @param string $salt The user's salt (needed for legacy hash verification).
 *
 * @return bool Returns true if the password matches.
 */
function verify_password_hash(string $password, string $stored_hash, string $salt = ''): bool
{
    if (str_starts_with($stored_hash, '$2y$') || str_starts_with($stored_hash, '$2a$') || str_starts_with($stored_hash, '$2b$')) {
        return password_verify($password, $stored_hash);
    }

    if (empty($salt)) {
        return false;
    }

    return hash_equals($stored_hash, legacy_hash_password($salt, $password));
}

/**
 * Check if a stored hash needs to be upgraded to bcrypt.
 *
 * @param string $stored_hash The stored hash from the database.
 *
 * @return bool Returns true if the hash is a legacy format.
 */
function needs_password_rehash(string $stored_hash): bool
{
    return !str_starts_with($stored_hash, '$2y$') && !str_starts_with($stored_hash, '$2a$') && !str_starts_with($stored_hash, '$2b$');
}

/**
 * Generate a new password salt.
 *
 * This method will not check if the salt is unique in database. This must be done
 * from the calling procedure.
 *
 * @return string Returns a salt string.
 */
function generate_salt(): string
{
    $max_length = 100;

    $salt = hash('sha256', uniqid((string) rand(), true));

    return substr($salt, 0, $max_length);
}
