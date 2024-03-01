<?php
require_once 'init.php';

if (Input::exist())
{
    if (Token::check(Input::get('token')))
    {
        $validate = new Validate();

        $validate->check($_POST, [
            'email' => ['required' => true, 'email' => true],
            'password' => ['required' => true]
        ]);

        if ($validate->passed())
        {
            $user = new User;
            $login = $user->login(Input::get('email'), Input::get('password'));

            if ($login)
            {
                Redirect::to('index.php');
            } else {
                echo 'login failed';
            }
        } else {
            foreach ($validate->getErrors() as $error)
            {
                echo $error . '<br>';
            }
        }
    }
}
?>

<form action="" method="post">
    <?= Session::flash('login') ?>
    <div class="field">
        <label>Введите email</label>
        <input name="email" type="text" value="<?= Input::get('email') ?>">
    </div>
    <div class="field">
        <label>Введите пароль</label>
        <input name="password" type="password">
    </div>
    <div class="field">
        <input type="hidden" name="token" value="<?= Token::generate() ?>">
    </div>
    <div>
        <input type="submit">
    </div>
</form>
