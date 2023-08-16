<div class="auth">
    <form action="/auth/login/" method="post" class="auth-main">
        <label for="">
            Логин: <sup>*</sup>
            <input type="text" name="login" required>
        </label>

        <label for="">
            Пароль: <sup>*</sup>
            <input type="password" name="password" required>
        </label>

        <div class="auth-checkbox">
            <input type="checkbox" name="remember" id="auth-remember">
            <label for="auth-remember">Запомнить</label>
        </div>

        <input type="submit" value="Войти">
    </form>
</div>
