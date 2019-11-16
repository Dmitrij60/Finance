<?php if (file_exists('./views/layouts/header.php')) include(ROOT . './views/layouts/header.php'); ?>
    <br> <br> <br>
    <section>
        <div class="container">
            <div class="row">
                <div class="col-sm-4 col-sm-offset-4 padding-right">
                    <?php if ($result): ?>
                        <p>Вы зарегистрированы!</p>
                    <?php else: ?>
                        <?php if (isset($errors) && is_array($errors)): ?>
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li> - <?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                        <div class="signup-form">
                            <h2>Регистрация на сайте</h2>
                            <form action="#" method="post">
                                <label for="inputPassword" class="sr-only">Имя</label>
                                <input type="text" class="form-control" name="name" placeholder="Имя"
                                       value="<?php echo $name; ?>"/>
                                <label for="inputPassword" class="sr-only">E-mail</label>
                                <input type="email" class="form-control" name="email" placeholder="E-mail"
                                       value="<?php echo $email; ?>"/>
                                <label for="inputPassword" class="sr-only">Пароль</label>
                                <input type="password" class="form-control" name="password" placeholder="Пароль"
                                       value="<?php echo $password; ?>"/>
                                <input type="submit" name="submit" class="btn btn-lg btn-primary btn-block"
                                       value="Регистрация"/>
                            </form>
                        </div>
                    <?php endif; ?>
                    <br/>
                    <br/>
                </div>
            </div>
        </div>
    </section>

<?php if (file_exists('./views/layouts/footer.php')) include(ROOT . '/views/layouts/footer.php'); ?>