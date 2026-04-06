<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- リセットCSS -->
    <link rel="stylesheet" href="https://unpkg.com/destyle.css@3.0.2/destyle.min.css">
    <link rel="stylesheet" href="./../asset/style.css">
    <!-- bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <title>訓練生ログイン画面</title>
</head>

<!-- <?php include __DIR__ . "./template/header.php" ?> -->

<body>
    <main>
        <h1 class="mb-5 text-center">ログイン</h1>
        <form action="./login.php" method="post">
            <div class="row justify-content-center">
                <div class="mb-3 col-6">
                    <label for="user_name" class="form-laber">ユーザー名</label>
                    <input type="text" name="user_name" id="user_name" class="form-control" autocomplete="user_name" placeholder="半角英数字●●字以上">
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="mb-4 col-6">
                    <label for="user_name" class="form-laber">パスワード</label>
                    <input type="text" name="password" id="password" class="form-control" autocomplete="password" placeholder="半角英数字●●字以上">
                </div>
            </div>
            <div class="text-center">
                <input type="submit" value="ログイン" class="btn btn-primary">
            </div>

        </form>
    </main>
</body>

</html>