<!--
    Kosta Dimitrijević 0467/2018
-->

<form method="post"  action = "<?= base_url("Login/checkUserCredentials") ?>">
    <table class="table loginForm">
        <tr>
            <td colspan="2" style="color: red; border-top:none">
                <?php
                    if(!empty($errors['username'])){
                        echo $errors['username'];
                    }
                    else if(!empty($errors['password'])){
                        echo $errors['password'];
                    }
                ?>
            </td>
        </tr>
        <tr>
            <td>
                Username:
            </td>
            <td>
                <input type="text" name="username" value="<?php
                    if (!isset($errors['username']))
                        echo set_value("username");
                    else
                        echo "";
                ?>">
            </td>
        </tr>
        <tr>
            <td>
                Password:
            </td>
            <td>
                <input type="password" name="password">
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center">
                <input type="submit" class="btn btn-dark btnTransition btnRegister" name="submit" value="Login">
            </td>
        </tr>
    </table>
</form>
