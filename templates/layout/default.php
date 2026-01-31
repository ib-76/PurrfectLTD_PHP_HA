<!DOCTYPE html>
<html>
    <head>
        <?= $this->Html->charset() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>
            <?= $this->fetch('title') ?>
        </title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

        <?= $this->fetch('meta') ?>
        <?= $this->fetch('css') ?>
        <?= $this->fetch('script') ?>
    </head>

    <body>
        <div class="container">
            <nav class="nav mt-3 mb-3 bg-light text-light p-3 text-center rounded d-flex">
                <a class="nav-link" aria-current="page" href="<?= $this->Url->build("/") ?>">Home</a>
                <?php if (isset($loggedUser)) { ?>
                <a class="nav-link" aria-current="page" href="<?= $this->Url->build("/users") ?>">Users List</a>
                <a class="nav-link" aria-current="page" href="<?= $this->Url->build("/pets") ?>">Pets List</a>
<?php } ?>
                <?php if (!isset($loggedUser)) { ?>
                    <a class="nav-link ms-auto" aria-current="page" href="<?= $this->Url->build("/users/login") ?>">
                        Login
                    </a>
                <?php } else { ?>
                    <span class="nav-link ms-auto disabled text-dark">
                        <?= $loggedUser->is_admin == 1 ? 'Admin' : 'User' ?>
                    </span>
                    <a class="nav-link" aria-current="page" href="<?= $this->Url->build("/users/logout") ?>">
                        Logout
                    </a>
                <?php } ?>
            </nav>
            
            <main class="main">
                <?= $this->Flash->render() ?>
                <?= $this->fetch('content') ?>
            </main>

            <footer class="bg-dark text-light p-3 mt-3 text-center rounded">
                Cake PHP 5.x PuRRfect Application &copy; <?= date('Y') ?> - Home Assignement
            </footer>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    </body>
</html>
