<?php if (file_exists('./views/layouts/header.php')) include(ROOT . './views/layouts/header.php'); ?>
<main role="main" class="container general">
    <br> <br> <br>
    <div>Привет <?php echo $user['name'] ?>!</div>
    <br>
    <div class="starter-template">
        <div class="content-start-page">
            <h3>Ваша карта №</h3>
            <div><?php echo $card['card_number']; ?></div>
            <br>
            <h3>Баланс Вашей карты:</h3>
            <div><?php echo $card['balance'] . ' $'; ?></div>
            <br>
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
                        <h2>Вывести средства</h2>
                        <form action="#" method="post">
                            <input type="text" class="form-control" placeholder="0.00" required autofocus
                                   name="withdraw" value=""/>
                            <input type="submit" name="submit" class="btn btn-lg btn-primary btn-block"
                                   value="Вывести"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php if (file_exists('./views/layouts/footer.php')) include(ROOT . '/views/layouts/footer.php'); ?>
