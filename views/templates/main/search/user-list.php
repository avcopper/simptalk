<?php
/**
 * @var \Entity\User $user
 */

$crypt = new \System\Crypt();
?>

<?php if (!empty($users) && is_array($users)): ?>
<ul>
    <?php foreach ($users as $user): ?>
        <?php
        $crypt->setPublicKey($user->publicKey);
        $name = !empty($user->name) ? $crypt->decryptByPublicKey($user->name) : '';
        $secondName = !empty($user->secondName) ? $crypt->decryptByPublicKey($user->secondName) : '';
        $lastName = !empty($user->lastName) ? $crypt->decryptByPublicKey($user->lastName) : '';
        ?>
        <li>
            <a href="">
                <img src="/images/user.jpg" alt="">
                <div class="search-info">
                    <div class="search-login">
                        <?= $user->login ?>
                    </div>
                    <div class="search-name">
                        <?= "{$name} {$secondName} {$lastName}" ?>
                    </div>
                </div>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
<?php else: ?>
    <div class="search-empty">Пользователи не найдены</div>
<?php endif;
