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
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.0-beta.2/themes/smoothness/jquery-ui.css">
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
                    <th scope="col">Цена за сутки</th>
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

                <label for="customRange1" class="form-label" id="count">Количество дней: <span
                            id="intervalD"></span></label>
                <div id="datepicker"></div>
                <input type="number" id="interval" name="days" class="form-control" min="1" max="30"
                       style="display: none">
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

            <h5>
                Итоговая стоимость:
                <span id="total-price">0</span> руб
                <span id="tooltip"

                      data-toggle="tooltip" data-html="true">
                    ?
                </span>
            </h5>


        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.14.0-beta.2/jquery-ui.min.js"></script>
<script>
    $(document).ready(function () {

        $("#form").change(function () {
            event.preventDefault();
            $.ajax({
                url: 'App/Presentation/calculate.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    $("#total-price").text(response.total);
                    let text = `Выбрано ${response.days} дней \n`;
                    text += `Тариф ${response.tarif}р/сутки \n`
                    if(response.services > 0){
                        text += `+ ${response.services}р/сутки за доп.услуги`
                    }

                    $("#tooltip").attr("title", text)
                },
                error: function () {
                    $("#total-price").text('Ошибка при расчете');
                }
            });

        });


        var selectedDates = [];

        $('#datepicker').datepicker({
            onSelect: function (dateText, inst) {
                var date = $(this).datepicker('getDate');
                selectedDates.push(date);

                if (selectedDates.length > 2) {
                    selectedDates = [];
                    $(this).datepicker('option', 'minDate', null);
                    $(this).datepicker('option', 'maxDate', null);
                } else if (selectedDates.length > 1) {
                    selectedDates.sort(function (a, b) {
                        return a.getTime() - b.getTime();
                    });

                    var startDate = selectedDates[0];
                    var endDate = selectedDates[selectedDates.length - 1];

                    if (endDate - startDate > 30 * 24 * 60 * 60 * 1000) {
                        $(this).datepicker('setDate', startDate);
                        alert('Промежуток между датами не должен превышать 30 дней');
                    } else {
                        var diffDays = Math.round((endDate - startDate) / (1000 * 60 * 60 * 24));
                        $('#interval').val(diffDays);
                        $('#interval').trigger('change');
                        $('#intervalD').text(diffDays)
                        $(this).datepicker('option', 'minDate', startDate);
                        $(this).datepicker('option', 'maxDate', endDate);
                    }
                }
            },
            beforeShowDay: function (date) {
                if (selectedDates.length > 1) {
                    var startDate = selectedDates[0];
                    var endDate = selectedDates[selectedDates.length - 1];

                    if (date < startDate || date > endDate) {
                        return [false, ''];
                    } else {
                        return [true, ''];
                    }
                } else {
                    return [true, ''];
                }
            }
        });


    })
    $(document).tooltip();

</script>
</body>
</html>