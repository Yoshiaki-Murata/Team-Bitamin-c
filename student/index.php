<?php
require_once __DIR__ . "/../inc/function.php"


?>

<?php include __DIR__ . "/../inc/header.php" ?>
<main>
    <div class="mb-5">
        <h1>トップページ</h1>
        <p>ようこそ●●さん</p>
    </div>

    <div class="mb-5">
        <h2 class="mb-3">キャリコン予約状況</h2>
        <div>
            <table class="table ms-4">
                <thead>
                    <tr class="row">
                        <th class="col-3">日付</th>
                        <th class="col-3">開始時間</th>
                        <th class="col-3">教室</th>
                        <th class="col-3">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="row">
                        <td class="col-3">5/17</td>
                        <td class="col-3">10:00～</td>
                        <td class="col-3">6C</td>
                        <td class="col-3">
                            <form action="./reserve_del.php" method="post">
                                <input type="hidden" name="reserve-id" id="reserve-id">
                                <input type="submit" value="変更申請" class="btn btn-sm btn-danger">
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mb-4">
        <h2 class="mb-3">キャリコンプラス予約状況</h2>
        <div class="mb-3">
            <table class="table ms-4">
                <thead>
                    <tr class="row">
                        <th class="col-3">日付</th>
                        <th class="col-3">開始時間</th>
                        <th class="col-3">教室</th>
                        <th class="col-3">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="row">
                        <td class="col-3">5/17</td>
                        <td class="col-3">10:00～</td>
                        <td class="col-3">6C</td>
                        <td class="col-3">
                            <form action="./reserve_del.php" method="post">
                                <input type="hidden" name="reserve-id" id="reserve-id">
                                <input type="submit" value="キャンセル申請" class="btn btn-sm btn-danger">
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="text-center">
            <a href="./reserve_add.php" class="btn btn-warning">予約する</a>
        </div>
    </div>
</main>