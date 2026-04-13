<?php

require_once __DIR__ . '/../inc/function.php';

$db = db_connect();

$slots = [];

$times = [];
$classes = [];
$consultants = [];
$carecons = [];
$lines = [];
$statuses = [];

$err_mgs = '';

try {
  $sql = 'SELECT reservation_slots.id,reservation_slots.date,times.time AS time_slot, classes.name AS class_name,consultants.name AS consultant_name, carecons.name AS carecon_name, carecon_lines.line AS line_number, reserve_status.name AS reserve_status_name FROM reservation_slots
INNER JOIN times ON reservation_slots.time_id = times.id
LEFT JOIN classes ON reservation_slots.class_id = classes.id
LEFT JOIN consultants ON reservation_slots.consultant_id = consultants.id
INNER JOIN carecons ON reservation_slots.carecon_id = carecons.id
INNER JOIN carecon_lines ON reservation_slots.lines_id = carecon_lines.id
INNER JOIN reserve_status ON reservation_slots.reserve_status_id = reserve_status.id ORDER BY reservation_slots.date ASC, reservation_slots.lines_id ASC';

  $stmt = $db->prepare($sql);
  $stmt->execute();
  $slots = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $sql_time = 'SELECT * FROM times ORDER BY id ASC';
  $stmt_time = $db->prepare($sql_time);
  $stmt_time->execute();
  $times = $stmt_time->fetchAll(PDO::FETCH_ASSOC);

  $sql_class = 'SELECT * FROM classes ORDER BY id ASC';
  $stmt_class = $db->prepare($sql_class);
  $stmt_class->execute();
  $classes = $stmt_class->fetchAll(PDO::FETCH_ASSOC);

  $sql_consul = 'SELECT * FROM consultants ORDER BY id ASC';
  $stmt_consul = $db->prepare($sql_consul);
  $stmt_consul->execute();
  $consultants = $stmt_consul->fetchAll(PDO::FETCH_ASSOC);

  $sql_carecon = 'SELECT* FROM carecons ORDER BY id ASC';
  $stmt_carecon = $db->prepare($sql_carecon);
  $stmt_carecon->execute();
  $carecons = $stmt_carecon->fetchAll(PDO::FETCH_ASSOC);

  $sql_line = 'SELECT * FROM carecon_lines ORDER BY id ASC';
  $stmt_line = $db->prepare($sql_line);
  $stmt_line->execute();
  $lines = $stmt_line->fetchAll(PDO::FETCH_ASSOC);

  $sql_status = 'SELECT * FROM reserve_status ORDER BY id ASC';
  $stmt_status = $db->prepare($sql_status);
  $stmt_status->execute();
  $statuses = $stmt_status->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $err_msg = 'データの取得に失敗しました:' . $e->getMessage();
};

require_once './../inc/header.php';
?>

<body>
  <div class="l-wrapper">
    <h1 class="c-title">キャリコン枠作成</h1>
    <button type="button" class="btn btn-info mb-3 add-btn" data-bs-toggle="modal" data-bs-target="#addSlotModal">
      新規キャリコン枠登録
    </button>

    <!-- モーダル -->
    <div class="modal fade" id="addSlotModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title">キャリコン枠作成</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>

          <form action="./schedule_add_do.php" method="post">
            <div class="modal-body px-4">
              <div class="mb-3">
                <label class="form-label fw-bold">日付</label>
                <input type="date" name="date" class="form-control" required>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold">時間</label>
                <select name="time_id" class="form-select" required>
                  <option value="" disabled>時間</option>
                  <?php foreach ($times as $time): ?>
                    <option value="<?php echo h($time['id']); ?>">
                      <?php echo h($time['time']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold">ライン</label>
                <select name="lines_id" class="form-select" required>
                  <option value="" disabled>ライン</option>
                  <?php foreach ($lines as $line): ?>
                    <option value="<?php echo h($line['id']); ?>">
                      <?php echo h($line['line']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold">教室</label>
                <select name="class_id" class="form-select">
                  <option value="">未定</option>
                  <?php foreach ($classes as $class): ?>
                    <option value="<?php echo h($class['id']); ?>">
                      <?php echo h($class['name']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold">担当</label>
                <select name="consultant_id" class="form-select">
                  <option value="">未定</option>
                  <?php foreach ($consultants as $consultant): ?>
                    <option value="<?php echo h($consultant['id']); ?>">
                      <?php echo h($consultant['name']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold">キャリコン種別</label>
                <select name="carecon_id" class="form-select" required>
                  <option value="" disabled>キャリコン種別</option>
                  <?php foreach ($carecons as $carecon): ?>
                    <option value="<?php echo h($carecon['id']); ?>">
                      <?php echo h($carecon['name']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold">予約状況</label>
                <select name="reserve_status_id" class="form-select" required>
                  <?php foreach ($statuses as $status): ?>
                    <option value="<?php echo h($status['id']); ?>" <?php echo $status['id'] == 1 ? 'selected' : '' ?>>
                      <?php echo h($status['name']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

            </div>

            <div class="modal-footer">
              <input type="submit" value="登録" class="btn btn-primary">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
            </div>

          </form>

        </div>
      </div>
    </div>
    <!-- ここまでモーダル -->

    <table class="table">
      <thead>
        <tr>
          <th scope="col">日付</th>
          <th scope="col">時間</th>
          <th scope="col">ライン</th>
          <th scope="col">教室</th>
          <th scope="col">担当</th>
          <th scope="col">キャリコン種別</th>
          <th scope="col">予約状況</th>
          <th scope="col">操作</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($slots as $slot): ?>
          <tr>
            <td><?php echo $slot['date'] ?></td>
            <td><?php echo $slot['time_slot'] ?></td>
            <td><?php echo $slot['line_number'] ?></td>
            <td><?php echo $slot['class_name'] ?? '未定' ?></td>
            <td><?php echo $slot['consultant_name'] ?? '未定' ?></td>
            <td><?php echo $slot['carecon_name'] ?></td>
            <td><?php echo $slot['reserve_status_name'] ?></td>
            <td>
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" da-bs-target="#addEditModal">編集</button>
              <button type="button" class="btn btn-danger" data-bs-toggle="modal" da-bs-target="#addDelModal">削除</button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <!-- 編集モーダル -->
    <!-- ここまで -->
    <!-- 削除モーダル -->
    <!-- ここまで -->
  </div>

</body>
<script>

</script>


<?php require_once './../inc/footer.php'; ?>