<?php if (file_exists('./views/layouts/header.php')) include(ROOT . './views/layouts/header.php'); ?>
    <br> <br> <br>
    <section>
        <div class="container">
            <div class="row">
                <div class="col-sm-4 col-sm-offset-4 padding-right">
                    <?php if (isset($errors) && is_array($errors)): ?>
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li> - <?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    <div class="signup-form">
                        <h2>Вход в личный кабинет</h2>
                        <form action="#" method="post">
                            <input type="email" class="form-control" placeholder="Email address"
                                   required autofocus name="email" value="<?php echo $email; ?>"/>
                            <input type="password" name="password" class="form-control" placeholder="Password"
                                   required value="<?php echo $password; ?>"/>
                            <input type="submit" name="submit" class="btn btn-lg btn-primary btn-block" value="Вход"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php if (file_exists('./views/layouts/footer.php')) include(ROOT . '/views/layouts/footer.php'); ?>