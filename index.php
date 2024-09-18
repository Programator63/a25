<?php
require_once 'App/Application/CalculateService.php';

use App\Application\Calculate;

$Calculate = new Calculate();
?>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          crossorigin="anonymous">
    <link href="assets/css/style.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
    <div class="row row-header">
        <div class="col-12" id="count">
            <img src="assets/img/logo.png" alt="logo" style="max-height:50px"/>
            <h1>Прокат Y</h1>
        </div>
    </div>

    <div class="row row-form">
        <div class="col-12">

            <h3>
                Прайс лист продуктов
            </h3>
            <table class="table mb-5">
                <thead>
                <tr>
                    <th scope="col">Наименование</th>
                    <th scope="col">Цена за день</th>
                </tr>
                </thead>
                <tbody>
                <?php $products = $Calculate->get_products();
                if (is_array($products)) { ?>
                    <?php foreach ($products as $product) {
                        $name  = $product['NAME'];
                        $price = $product['PRICE'];
                        $tarif = $product['TARIFF'];
                        ?>
                        <tr>
                            <td><?= $name; ?></td>
                            <td><?= $price; ?></td>
                        </tr>
                    <?php } ?>
                <?php } ?>

                </tbody>
            </table>
            <form action="App/calculate.php" method="POST" id="form">

                <?php $products = $Calculate->get_products();
                if (is_array($products)) { ?>
                    <label class="form-label" for="product">Выберите продукт:</label>
                    <select class="form-select" name="product" id="product">
                        <?php foreach ($products as $product) {
                            $name  = $product['NAME'];
                            $price = $product['PRICE'];
                            $tarif = $product['TARIFF'];
                            ?>
                            <option value="<?= $product['ID']; ?>"><?= $name; ?></option>
                        <?php } ?>
                    </select>
                <?php } ?>

                <label for="customRange1" class="form-label" id="count">Количество дней:</label>
                <input type="number" name="days" class="form-control" id="customRange1" min="1" max="30">

                <?php $services = unserialize($Calculate->get_services());
                if (is_array($services)) {
                    ?>
                    <label for="customRange1" class="form-label">Дополнительно:</label>
                    <?php
                    $index = 0;
                    foreach ($services as $k => $s) {
                        ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="services[]" value="<?= $s; ?>"
                                   id="flexCheck<?= $index; ?>">
                            <label class="form-check-label" for="flexCheck<?= $index; ?>">
                                <?= $k ?>: <?= $s ?>
                            </label>
                        </div>
                        <?php $index++;
                    } ?>
                <?php } ?>
            </form>

            <h5>Итоговая стоимость: <span id="total-price"></span></h5>


        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {

        $("#form").change(function () {
            event.preventDefault();
            $.ajax({
                url: 'App/Presentation/calculate.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    $("#total-price").text(response);
                },
                error: function () {
                    $("#total-price").text('Ошибка при расчете');
                }
            });

        });

        $("#form").submit(function (event) {
            event.preventDefault();

            $.ajax({
                url: 'App/Presentation/calculate.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    $("#total-price").text(response);
                },
                error: function () {
                    $("#total-price").text('Ошибка при расчете');
                }
            });
        });
    });
</script>
</body>
</html>