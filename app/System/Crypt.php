<?php
namespace System;

use phpseclib3\Crypt\RSA;
use Exceptions\CryptException;

class Crypt
{
    private $public;
    private $private;
    private $publicKey;
    private $privateKey;

    public function __construct($publicKey = null, $privateKey = null)
    {
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
    }

    public function generatePair(int $bits = 2048)
    {
        $this->private = RSA::createKey(2048);
        $this->public = $this->private->getPublicKey();

        $this->privateKey = $this->private->toString(
            'PKCS8'/*,
            [
                'encryptionAlgorithm' => 'id-PBES2',
                'encryptionScheme'    => 'aes256-CBC-PAD',
                'PRF'                 => 'id-hmacWithSHA512-256',
                'iterationCount'      => 4096
            ]*/
        );
        $this->publicKey = $this->public->toString('PKCS8');

        return $this;
    }

    public function load($id)
    {
        if (!is_file(__DIR__ . "/../../certificates/{$id}/private.pem") ||
            !is_file(__DIR__ . "/../../certificates/{$id}/public.pem")
        ) throw new CryptException('Не найдены файлы с ключами', 523);

        $this->privateKey = file_get_contents(__DIR__ . "/../../certificates/{$id}/private.pem");
        $this->publicKey = file_get_contents(__DIR__ . "/../../certificates/{$id}/public.pem");
        return $this;
    }

    public function save($id)
    {
        if (!is_dir(__DIR__ . "/../../certificates")) mkdir(__DIR__ . "/../../certificates");
        if (!is_dir(__DIR__ . "/../../certificates/{$id}")) mkdir(__DIR__ . "/../../certificates/{$id}");
        file_put_contents(__DIR__ . "/../../certificates/{$id}/public.pem", $this->publicKey);
        file_put_contents(__DIR__ . "/../../certificates/{$id}/private.pem", $this->privateKey);
        return $this;
    }

    public function encryptByPublicKey(string $plaintext, $encodeBase64 = true)
    {
        openssl_public_encrypt($plaintext, $encrypted, $this->publicKey);
        return $encodeBase64 ? base64_encode($encrypted) : $encrypted;
    }

    public function encryptByPrivateKey(string $plaintext, $encodeBase64 = true)
    {
        openssl_private_encrypt($plaintext, $encrypted, $this->privateKey);
        return $encodeBase64 ? base64_encode($encrypted) : $encrypted;
    }

    public function decryptByPublicKey(string $ciphertext, $encodedBase64 = true)
    {
        openssl_public_decrypt($encodedBase64 ? base64_decode($ciphertext) : $ciphertext, $out, $this->publicKey);
        return $out;
    }

    public function decryptByPrivateKey(string $ciphertext, $encodedBase64 = true)
    {
        openssl_private_decrypt($encodedBase64 ? base64_decode($ciphertext) : $ciphertext, $out, $this->privateKey);
        return $out;
    }

    public function getPublicKey()
    {
        return $this->publicKey;
    }

    public function getPrivateKey()
    {
        return $this->privateKey;
    }
}
