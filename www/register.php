<?php
require_once 'init.php';

if (Input::exist())
{
    if (Token::check(Input::get('token')))
    {
        $validate = new Validate();

        $validate->check($_POST, [
            'username' => [
                'required' => true,
                'min' => 4,
                'max' => 255
            ],
            'email' => [
                'required' => true,
                'email' => true,
                'max' => 255,
                'unique' => 'users',
            ],
            'password' => [
                'required' => true,
                'min' => 8,
                'max' => 255
            ],
            'password_confirm' => [
                'required' => true,
                'confirm' => 'password',
            ],
        ]);

        if ($validate->passed())
        {
            $user = new User;

            $user->create([
                'email' => Input::get('email'),
                'password' => password_hash(Input::get('password'), PASSWORD_DEFAULT),
                'username' => Input::get('username')
            ]);

            Session::flash('register_success', 'register success');
            //Redirect::to('result.php');
        } else {
            foreach ($validate->getErrors() as $error)
            {
                echo '- ' . $error . '<br>';
            }
        }
    }
}
?>

<form action="" method="post">
    <?= Session::flash('register_success') ?>
    <div class="field">
        <label for="username">Username</label>
        <input name="username" type="text" value="<?= Input::get('username') ?>">
    </div>

    <div class="field">
        <label for="email">Email</label>
        <input name="email" type="text" value="<?= Input::get('email') ?>">
    </div>

    <div class="field">
        <label for="">Password</label>
        <input name="password" type="password">
    </div>

    <div class="field">
        <label for="">Password confirmation</label>
        <input name="password_confirm" type="password">
    </div>

    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <div class="field">
        <button type="submit">Отправить</button>
    </div>
</form>
