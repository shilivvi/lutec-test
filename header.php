<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="header">
    <div class="container container__header">
        <div class="header__logo">Header</div>
        <nav class="header__menu nav">
            <ul class="nav__list list-reset">
                <li class="nav__item">
                    <a href="#" class="nav__link">Главная</a>
                </li>
                <li class="nav__item">
                    <a href="#" class="nav__link">Статьи</a>
                </li>
                <li class="nav__item">
                    <a href="#" class="nav__link">Новости</a>
                </li>
            </ul>
        </nav>
    </div>
</header>