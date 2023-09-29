<style>
    .user-list {
        height: 100%;
        padding: 10px;
        background-color: #ffffff;
    }
    .user-link {
        margin: 10px;
    }
    .user-link a {
        display: flex;
    }
    .user-image {
        height: 40px;
    }
    .user-image img {
        height: 100%;
    }
    .user-name {
        height: 40px;
        padding: 10px;
    }
    .user-empty {
        margin: 5px;
        padding: 10px;
    }
</style>

<div class="user-list">
    <?php if (!empty($messages) && is_array($messages)): ?>
        <?php foreach ($messages as $message): ?>
            <div class="user-link">
                <a href="<?= $message->friendLogin ?>/">
                    <div class="user-image"><img src="/images/user.jpg" alt=""></div>
                    <div class="user-name"><?= $message->friendLogin ?></div>
                </a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="user-empty">Диалоги не обнаружены</div>
    <?php endif; ?>
</div>

<script>
    $(function () {

    });
</script>
