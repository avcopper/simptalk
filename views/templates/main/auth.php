<style>
    .auth {
        display: flex;
        flex: 1 0 auto;
        align-items: center;
        justify-content: center;
        background-color: #edeef0;
    }
    .auth-main {
        width: 300px;
    }
    .auth-main label,
    .auth-main input {
        display: block;
    }
    .auth-main input {
        width: 100%;
        height: 37px;
        margin: 5px 0 10px;
        padding: 0 10px;
        color: #383838;
        font: 13px/37px 'OpenSansRegular';
        border: 1px solid #eeeeee;
        box-shadow: none;
        outline: 0;
    }
    .auth-main input[type=checkbox] {
        display: inline-block;
        width: 20px;
        height: 20px;
        margin-right: 8px;
        line-height: 0;
    }
    .auth-main input[type=submit] {
        margin: 0 auto 0;
        color: #888888;
        border: 1px solid #a6a6a6;
        cursor: pointer;
    }
    .auth-checkbox {
        display: flex;
        flex-direction: row;
    }
    .auth-checkbox {
        line-height: 29px;
    }
</style>

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
