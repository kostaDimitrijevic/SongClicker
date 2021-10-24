<!--
    Mladen Mirčić 2018/0413
-->

<form method="post" action="<?= base_url("Register/checkRegisterCredentials") ?>">
    <table class="table registerForm">
        <tr>
            <td colspan="2" style="color: red; border-top: none">
                    <?php
                        if (!empty($errors['username']))
                            echo $errors['username'];
                        else if (!empty($errors['password'])) {
                            echo $errors['password'];
                        }
                        else if (!empty($errors['confirmPass'])) {
                            echo $errors['confirmPass'];
                        }
                    ?>
            </td>
        </tr>
        <tr>
            <td>
                Username:
            </td>
            <td>
                <input id="username" type="text" name="username">
            </td>
        </tr>
        <tr>
            <td>
                Password:
            </td>
            <td>
                <input id="password" type="password" name="password">
            </td>
        </tr>
        <tr>
            <td>
                Confirm password:
            </td>
            <td>
                <input id="confirmPass" type="password" name="confirmPass">
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center">
                <input class="btn btn-dark btnRegister btnTransition" type="submit" name="submit" value="Register">
            </td>
        </tr>
    </table>
</form>