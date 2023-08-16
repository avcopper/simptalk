<?php

namespace Controllers;

use Models\Test;
use Models\User;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Crypt\RSA\PrivateKey;
use phpseclib3\Math\BigInteger;
use System\Crypt;
use System\OldRSA;

class Tests extends Controller
{
    protected const PUBLIC = "-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA43pm+vOnpYfG69F8Rs1/
9+ZysS7U0TvAPcfV5ATXMeyppBVi8Yy8cUZ/XIQpMv4ArJ1x1j+AdiKJIzOHc4Zt
LFVIeuKizLk+VZxsP8aaEmMvbXhDlqIS4Lj666+O2O309XRP5ExkmYezLJhD4eaP
kzMixv1xJTQmUvz8wz8KeyYKMH3edpbKlWU3OmObf9eC9srbVpyfWbvO6/JaMHKd
2+gxpQbQ0gzBESCdTnT5rKziaUYbGqKUWrroYYCSVWbq+ETZoXVZGJpDv/eN6YiH
kQyI8vUpOdi2aMQrIMwIncBa5D3I2H0FgpSNEloZ1L4Tjq4k0u9x0UWL20Tu+JSq
EQIDAQAB
-----END PUBLIC KEY-----";

    protected const PRIVATE = "-----BEGIN ENCRYPTED PRIVATE KEY-----
MIIFLTBXBgkqhkiG9w0BBQ0wSjApBgkqhkiG9w0BBQwwHAQI0YNACsWBY7ACAggA
MAwGCCqGSIb3DQIJBQAwHQYJYIZIAWUDBAEqBBAusItvRemzkKEmbrow/dCFBIIE
0NF9ofFHf7mBYRccphH51GhwI+bY0jvHwnA0/EPc53NeDfPSeso8wH2GNvZjwVvR
BNBoViBhNkU2dbLe362DNkpkWsYP6pA/pV1n4H8c/cq3i1ah5VfBDO8LJ3mauP4Q
TeQUHgCtB6YP271V/cYSS2+AlZSmGHW9MMgnmsX8Enly3Ki+qkhaeBbNz/4ezjl8
M+Phf5fjEulmGXam3qkgeFlqU3T+117Vy938kEM0w4k4FDsz3zpVxz/qdx5sjnfe
K1ZAySU+buTXvh+o2MiWCGUlECf+aUfX7yCKH69fd3wQs2soUeRquBP3w3X3cA5K
gwDKriwCggnIPtRp6pJ+04hO9hZ+u76urYfWhQHy8l8dCQy+jcrD0H/x+DxSNmuN
53EtjN8f3fgGEJOV0CAivYyi8HTw0pUw4nz54PkpFdfYeM7YRSIgf+P0+RfNMvri
cr87MRJ8fkxt/pvLbqLE/yWwgaIItJaqhBYDyPF9RX1nGA61/eveLyeeG2qt5yTn
5qkp2RtVrm7+h/FDuW1RpKDxR3e/4JbY6/hHpk+tsuNFY3sB7Ar/+L3RnSuHCeC6
RNfyIXeEAmL12JchXRkSCSz9Ij/z6qYyOp/hMvQm05hehQljprtmLYb/OkeC63Aa
E3pzp9upAAimgV7ZUj3n1O3D3DCTUXi0V37gJDA1Y9Hac+W4l/k/71q+F4XWENHr
SBbd9fkhEZHgXW3YF/atmPeT4OXj1ii3hmd8MDut5YRNwSO95sS9vyrfvUGvn9KP
kbQ5aoiSOGv6kqN5Ki8U+6+LpT5EH1YsnhHsstQebpf2b5lnzpYhph7jAiO+zGGd
7sapnnegW3kGomjrqC5wPHP3GyilI4BKc8bhGj0f+52BMi+tnITflJxd+K/aO0HV
CsYBmKwX5ctvKbhklyh6o8SqWR5pbjGYQnDUB+j/Ce4wnaqbH60BL1clyWndUV2x
MI9vzJsn0isR1/iJqgYXa9WrzSbWoKifeW6tH3uTU/hTx0sQpwXLXqhJ4CgFkPrJ
OvUpAftE4rTIOzN3TabZ9BDueYzHIqQt1kZDcz3o+JQEhi1r9vRJr4tTawiWMBno
bLBIDmasxoBlGh2mPP/BIxbwK88KhYLUVj88yUCi14ZoJ22tC76/loqS0wSraHkW
Q7I0SDylB6MHgwEbjVcRrtXkljgD+UCRKK5ekxmgLaBC3aWpergf1sDjUrCzyMdv
alJBSgxYCsALaE6p4/pC7yoXsH68qt6UBD8uVsX0UbBhHJsUPVHAdFkFx1AsoQHc
KL2RJ/4nwea97iwAyGd/LgZoGEFRPI0JUKXyJRzmNS54ts/MdozN4easbCx/lZNL
tqp/f+Tosuqepm+LB8GrSu/ufMVPKbFjGFLdA5EPzxrw3sPN9jVw2p4cXNWxx7+n
cU5kTjdElASEKjP2VhjLqURrnoFgrXXhXYUH1Ubub4R7l2Oq9MFIvw6Brbt+Mo+a
DBFpk4avMZSpKO+g8ML9+LG0DuBbCzdBDgJOzOjJq2r7csmY7MDqHfiFaRhyHUUp
5LIJ9rOnW3r6h9N8y2/rIDkyY5x2JqnJ+4P8FP9RHlbI67fGzGhAhb5nsSOlR2Lc
J5VcxRK3jcnLdOrvuJkgKcR3iqyaT9jHKf2VDLzihJ0k
-----END ENCRYPTED PRIVATE KEY-----";

    protected const PRIVATE_PKCS8 = "-----BEGIN PRIVATE KEY-----
MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDjemb686elh8br
0XxGzX/35nKxLtTRO8A9x9XkBNcx7KmkFWLxjLxxRn9chCky/gCsnXHWP4B2Iokj
M4dzhm0sVUh64qLMuT5VnGw/xpoSYy9teEOWohLguPrrr47Y7fT1dE/kTGSZh7Ms
mEPh5o+TMyLG/XElNCZS/PzDPwp7Jgowfd52lsqVZTc6Y5t/14L2yttWnJ9Zu87r
8lowcp3b6DGlBtDSDMERIJ1OdPmsrOJpRhsaopRauuhhgJJVZur4RNmhdVkYmkO/
943piIeRDIjy9Sk52LZoxCsgzAidwFrkPcjYfQWClI0SWhnUvhOOriTS73HRRYvb
RO74lKoRAgMBAAECggEBAN6Z9RQ2QyPUznANu1N93XA1bp68eT2W/vR2DXTo17zf
HtKWqxA87Frc5d0e50IjFX3VRISn94mjGhf8YNxH+6WafybhiPZVgA4+2B9AtZyw
usShH2LUjyyhV8ozLdJSaZeYjmWV7AvhoxBMoID7Ihylua6IbKQ/eBFVx/M4SYVf
R+QvPdxbInot/xiOpk4iXWw2iG50yybdzGu/wQGVyNXf/ek/bCimCC1N+jCB3PeN
9GQMkfVEgVZY+WCTuZOD/iYPuZeeHeM2cpHtmdMaNTfO5u1TyNJgqzMd2alWETfN
UYAya+ZZ+7cIO6i7Eo5pIXru0E0pcmQG8N3/kVMmZZECgYEA8mvsS9ZU+CR2ESn9
LkHrQaLXw5nXCH/8aTJlkbsPY3MKwnm6cF6kDshpsv8CfQGx9ETA1KQUIWK9UXEc
zYppLOp60envVe9exXG0E8NYYLBGIeH+NaP3fktl7iNRfrOq5/nMgv4TuLjWjurL
XNh4S3CejQHwqKtlR1vFqT0YXmcCgYEA8Dg0ouHQXV2IRI0MTBM9f1VvSDGIu3r6
s6+R358VG4vw+pKZln/CPIeReXokl/9zRjWBd5IKufJoqnXm5czF8mK+Ens3j2T+
7FVSAhcJA+sGf9y5vbxi6dQ8GPjA5Tj7n/waND0ISrE7oHzkAVWkOjOt3JYueyzE
vJ1SaFqleMcCgYBLctVnQPzPAiIQ832N4QxEMFdw1Dd9uL+pSfscoRiH/i0SG+qv
wHTU+QEUqZNvrpBhEujlBXASd/WuHEM3oWVcilnRbTqFB2v6jYnbQMgHx7T8JKcG
sNJ9ZyABPtLFWUvOyQsilcsziauVbXP/oIvIBvQBtOHezQFHdUOOiapE7wKBgQDs
u9dTtgqsGGMUe5Rk3Ik8launj/laGeY7cojlwlcA4LbJmfP2l02KbWf2OWMa0EqA
JHbEqY4clkKDytGUImvpZVo/yGKG1FdN/P9mw+DElbfABnyd+avZGKlpSqx+udtw
XPhOBt/HDEbg8HOaxRWlrgxnWTHRfdscrkXqRXrRPQKBgC1CihYZDqsZBXEFDx71
sGYYe7XieVtilbMOjRoovgJ5Cig63cQShM+XIaB6icViyy3TI9nTYvO9zd+K7XAR
J6vdEc+kUmXHoMlckpgsJP/s48GYMPwvFsfPSoHaW0mJKgCQNQYLPeHPn7bCTRzV
7ymuMHwV9PBZSJD2rApbHBr/
-----END PRIVATE KEY-----";

    protected function before()
    {
    }

    protected function actionDefault()
    {
        $rsa = new Crypt();
        $rsa->load(1);
    }

    protected function actionNewPair()
    {
        $rsa = new Crypt();
        $rsa->generatePair()->save(1);
        $rsa->generatePair()->save(2);
    }

    protected function actionRsa()
    {
        $privateKey = file_get_contents('C:\OPENSERVER540\domains\simptalk\certificates\1\private.pem');
        $publicKey = file_get_contents('C:\OPENSERVER540\domains\simptalk\certificates\1\public.pem');

        var_dump($privateKey);
        var_dump($publicKey);

        echo '===================================================================';

        openssl_private_encrypt("jopa", $encrypted1, $privateKey);
        var_dump(base64_encode($encrypted1));

        openssl_public_decrypt($encrypted1, $out1, $publicKey);
        var_dump($out1);

        echo '===================================================================';

        openssl_public_encrypt("jopa", $encrypted2, $publicKey);
        var_dump(base64_encode($encrypted2));

        openssl_private_decrypt($encrypted2, $out2, $privateKey);
        var_dump($out2);

        echo '===================================================================<br>';
        echo '===================================================================';

        $pubStr = 'XepmieZFDKrQKFdoTEbHl4/V/pBUzIm66tTgip25K6z6xU9IcEKRU9oqpt6Od9UgTxG9ZZp2ouoqGgK9QnDMtP3kfI0VYXiv0hEt6HF8Uo8UwRhQ3+K7emt0Ai9lD3E107RuEsDDh9CYiAeUahijYqQ75NH0J3KcDiIA6zFuBzg4Ze8Fj1z+t3qD7RnIQ2Lb1cDoG7Pj7iFtaqBjqogXLkhxZ03kidBIFSJVhiRTYxYNqWdWmsPgbS27U5C7a5Kc6ZESM/e/wL/woQX2d5ZJymOPNnYw0Xs5bdF2vv+C+wNdG5PEZoRIK4vtLvPa845Z9nkQmESeFkn/7+PMs80T3A==';
        $privStr = 'nDxcP8jnoHpnswmbfntPa+5ajgw/2AdWuBbXSp0cCH7KrB0kI43uqZIy2+pbNpvYzSVkYZKLlYgheJJEOtBbp8IwDQyD4ze13sM/Y8JL6ww1VuoYnkQuKEdmnGY/pP0s8iQsNcpAjLFbz0u9JVaw0kZcXuELM8fB5QRC5E4lDKZ5Etcorl7o+iT/abbXwguG34nfYbAZMnocg8AscF5ZHxyfqIYxoppoSbPOFopdmdEUXhBVHMRNiHl2522uWE341+oNqN2jXHEAYOxvB2NtWARCLbK9pxvNcZkqEdTLWuWS+QLHpt/OJlaomywmRtjOZ8CYt0oYM22HH0Et39AW/A==';

        // расшифровка текста, зашифрованного публичным ключом с помощью приватного ключа
        openssl_private_decrypt(base64_decode($pubStr), $out3, $privateKey); // public -> private
        var_dump($out3);

        echo '===================================================================';

        // расшифровка текста, зашифрованного приватным ключом с помощью публичного ключа
        openssl_public_decrypt(base64_decode($privStr), $out5, $publicKey); // private -> public
        var_dump($out5);
    }

    /**
     * генерация ключей с помощью внешних команд
     */
    protected function actionRsaGenExec()
    {
        $id = 1;
        // создание приватного ключа в формате pkcs#1
        if (!is_dir(__DIR__ . '/certificates')) mkdir(__DIR__ . '/certificates');
        if (!is_dir(__DIR__ . "/certificates/{$id}")) mkdir(__DIR__ . "/certificates/{$id}");


        exec("openssl genpkey -algorithm OldRSA -out " . __DIR__ . "/certificates/{$id}/private.pem -pkeyopt rsa_keygen_bits:2048 -aes-256-cbc -pass pass:hello");
        $pkcs1 = file_get_contents(__DIR__ . "/certificates/{$id}/private.pem");
        var_dump($pkcs1);

        // создание приватного ключа в формате pkcs#8
        exec("openssl pkcs8 -topk8 -nocrypt -in " . __DIR__ . "/certificates/{$id}/private.pem -passin pass:hello -out " . __DIR__ . "/certificates/{$id}/private_pkcs8.pem -passout pass:hello");
        $pkcs8 = file_get_contents(__DIR__ . "/certificates/{$id}/private_pcks8.pem");
        var_dump($pkcs8);

        // создание публичного ключа
        exec("openssl rsa -in " . __DIR__ . "/certificates/{$id}/private.pem -passin pass:hello -pubout -out " . __DIR__ . "/certificates/{$id}/public.pem");
        $public = file_get_contents(__DIR__ . "/certificates/{$id}/public.pem");
        var_dump($public);
    }

    /**
     * генерация ключей с помощью библиотеки
     */
    protected function actionRsaGenLib()
    {
        $privKey = \phpseclib3\Crypt\RSA::createKey(2048);
        $pubKey = $privKey->getPublicKey();

        $privateKey = $privKey->toString('PKCS8');
        $publicKey = $pubKey->toString('PKCS8');

        var_dump($publicKey);
        var_dump($privateKey);
        file_put_contents('C:\OPENSERVER540\domains\simptalk\certificates\1\public.pem', $publicKey);
        file_put_contents('C:\OPENSERVER540\domains\simptalk\certificates\1\private.pem', $privateKey);
    }
}
