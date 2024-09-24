<?php
require_once 'App/Domain/Users/UserEntity.php';

use App\Domain\Users\UserEntity;

$user = new UserEntity();
if (!$user->isAdmin) die('Доступ закрыт');
?>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          crossorigin="anonymous">
    <link href="/assets/css/style.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
    <h1>Админка</h1>

    <div class="col-md-6 mx-auto">
        <h2>
            Добавление нового продукта
        </h2>
        <form id="add_product" action="">
            <div class="mb-3">
                <label for="name" class="form-label">Наименование</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Цена</label>
                <input type="number" min="1" class="form-control" id="price" name="price" required>
            </div>
            <h5>
                Тарифы
                <button type="button" id="add-tariff" class="btn btn-success">Добавить</button>
            </h5>
            <ol id="tariff" style="max-height: 500px; overflow: hidden auto; padding: 15px">
            </ol>
            <button type="submit" class="btn btn-primary">Добавить</button>
        </form>
    </div>

</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        let tarifId = 1;
        $('#add-tariff').click(() => {
            const tarifBlock = `
    <li class="mb-3" id="tarif${tarifId}">



      <div class="row align-items-end">
        <div class="col">
          <label for="tarif${tarifId}Name" class="form-label">От N дней</label>
          <input type="number" min="0" class="form-control" id="tarif${tarifId}Name" name="tarif${tarifId}Name" required>
        </div>
        <div class="col">
          <label for="tarif${tarifId}Price" class="form-label">Цена</label>
          <input type="number" min="0" class="form-control" id="tarif${tarifId}Price" name="tarif${tarifId}Price" required>
        </div>
        <div class="col">
            <button type="button"  class="btn btn-danger" onclick="deleteTarif(${tarifId})"> Удалить</button>
        </div>

      </div>
    </li>
  `;

            $('#tariff').append(tarifBlock);
            tarifId++;
        })

        function deleteTarif(id) {
            $(`#tarif${id}`).remove()

        }


        $("#add_product").submit(function (event) {
            event.preventDefault();
            console.log($(this))
            $.ajax({
                url: '/App/Presentation/admin/addProduct.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    alert('Успешно добавлен!')
                    window.location.reload();

                },
                error: function () {
                    alert('Ошибка добавления!')
                }
            });

        });

    })

</script>

</body>
</html>