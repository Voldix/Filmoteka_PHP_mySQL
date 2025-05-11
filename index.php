<?php
// Подключение к БД
$link = mysqli_connect("localhost", "root", "", "filmoteka");

// Проверка на подключение, при ошибке останавливаем скрипт и показываем ошибку
if (mysqli_connect_error()) {
    die("Ошибка подключения к базе данных");
}

$result_success = "";
$result_error = "";
$errors = array();

// Saving form data to DB
if (array_key_exists("add-film", $_POST)) {

    if ($_POST["film"] == "") {
        $errors[] = "<p>Необходимо ввести название фильма</p> ";
    }

    if ($_POST["type"] == "") {
        $errors[] = "<p>Необходимо ввести жанр фильма</p>";
    }
    if ($_POST["year"] == "") {
        $errors[] = "<p>Необходимо ввести год фильма</p>";
    }

    if (empty($errors)) {
        // Запись данных в БД
        $query = "INSERT INTO `films` (`film`, `type`, `year`) VALUES (
        '" . mysqli_real_escape_string($link, $_POST["film"]) . "', 
        '" . mysqli_real_escape_string($link, $_POST["type"]) . "', 
        '" . mysqli_real_escape_string($link, $_POST["year"]) . "' 
        )";

        if (mysqli_query($link, $query)) {
            $result_success = "<p>Фильм был успешно добавлен!</p>";

            foreach ($_POST as $key => $value) {
                unset($_POST[$key]);
            }
        } else {
            $result_error = "<p>Фильм не был добавлен, произошла ошибка!</p>";
        }
    }
}

// Getting films from DB
// Формируем запрос, который выбирает все данные из таблицы films
$query = "SELECT * FROM `films`";
// Массив для помещения данных
$films = array();

// Проверка на выполнение запроса и тут же записываем возвращенный объект в переменную $result 
if ($result = mysqli_query($link, $query)) {
    while ($row = mysqli_fetch_array($result)) {
        // Возвращаем каждую строку таблицы и записываем в массив
        $films[] = $row;
    }
}

?>

<!-- Разные миксины по одному, которые понадобятся. Для логотипа, бейджа, и т.д.-->
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8" />
    <title>Фильмотека</title>
    <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge"/><![endif]-->
    <meta name="keywords" content="" />
    <meta name="description" content="" /><!-- build:cssVendor css/vendor.css -->
    <link rel="stylesheet" href="libs/normalize-css/normalize.css" />
    <link rel="stylesheet" href="libs/bootstrap-4-grid/grid.min.css" />
    <link rel="stylesheet" href="libs/jquery-custom-scrollbar/jquery.custom-scrollbar.css" /><!-- endbuild -->
    <!-- build:cssCustom css/main.css -->
    <link rel="stylesheet" href="./css/main.css" /><!-- endbuild -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800&amp;subset=cyrillic-ext" rel="stylesheet">
    <!--[if lt IE 9]><script src="http://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.2/html5shiv.min.js"></script><![endif]-->
</head>

<body class="index-page">
    <div class="container user-content section-page">

        <?php if ($result_success) { ?>
            <div class="notify notify--success mb-20"><?= $result_success ?></div>
        <?php } ?>

        <?php if ($result_error) { ?>
            <div class="notify notify--error mb-20"><?= $result_error ?></div>
        <?php } ?>

        <div class=" title-1">Фильмотека</div>

        <?php
        foreach ($films as $key => $value) {
        ?>
            <div class="card mb-20">
                <h4 class="title-4"><?= $films[$key]["film"] ?></h4>
                <div class="badge"><?= $films[$key]["type"] ?></div>
                <div class="badge"><?= $films[$key]["year"] ?></div>
            </div>
        <?php
        }
        ?>
        <div class="panel-holder mt-80 mb-40">
            <div class="title-3 mt-0">Добавить фильм</div>

            <form action="index.php" method="POST">
                <?php
                if (!empty($errors)) {
                    foreach ($errors as $key => $value) {
                        echo '<div class="notify notify--error mb-20">' . $value . '</div>';
                    }
                }
                ?>

                <div class="form-group">
                    <label class="label">Название фильма<input class="input" name="film" type="text" placeholder="Такси 2" 
                    value="<?php echo isset($_POST["add-film"]) && !empty(trim($_POST["film"])) ? trim($_POST["film"]) : ""  ?>" /></label>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label class="label">Жанр<input class="input" name="type" type="text" placeholder="комедия" 
                            value="<?php echo isset($_POST["add-film"]) && !empty(trim($_POST["type"])) ? trim($_POST["type"]) : ""  ?>" /></label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label class="label">Год<input class="input" name="year" type="text" placeholder="2000" 
                            value="<?php echo isset($_POST["add-film"]) && !empty(trim($_POST["year"])) ? trim($_POST["year"]) : ""  ?>"/></label>
                        </div>
                    </div>
                </div>
                <input class="button" type="submit" name="add-film" value="Добавить" />
            </form>
        </div>
    </div>
    <!-- build:jsLibs js/libs.js -->
    <script src="libs/jquery/jquery.min.js"></script><!-- endbuild -->
    <!-- build:jsVendor js/vendor.js -->
    <script src="libs/jquery-custom-scrollbar/jquery.custom-scrollbar.js"></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key="></script><!-- endbuild -->
    <!-- build:jsMain js/main.js -->
    <script src="js/main.js"></script><!-- endbuild -->
    <script defer="defer" src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
</body>

</html>