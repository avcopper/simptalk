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
            <?php if ($user->isLocked): ?>
                <span class="search-link">
            <?php else: ?>
                <a href="" class="search-link">
            <?php endif; ?>

                <img src="/images/user.jpg" alt="">

                <div class="search-info">
                    <div class="search-login"><?= $user->login ?></div>
                    <div class="search-name"><?= "{$name} {$secondName} {$lastName}" ?></div>
                </div>
            <?php if (!$user->isLocked): ?>
                </a>
            <?php else: ?>
                </span>
            <?php endif; ?>


            <?php if ($user->isNeedRequest): ?>
                <a href="" class="user-icon user-request" title="Request"></a>
            <?php else: ?>
                <a href="" class="user-icon user-message" title="Message"></a>
            <?php endif; ?>

            <?php if ($user->isLocked): ?>
                <span class="user-icon user-lock" title="Locked "></span>
            <?php else: ?>
                <span class="user-icon user-unlock" title="Unlocked"></span>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>
<?php else: ?>
    <div class="search-empty">Пользователи не найдены</div>
<?php endif;
