<?php

require_once __DIR__ . '/../inc/function.php';

$db = db_connect();

$students = [];
$classes = [];
$err_msg = '';

$class_id = $_GET['class_id'] ?? '';
$keyword = $_GET['keyword'] ?? '';

try {
  $sql = 'SELECT 
  students.id,
  students.login_id, 
  students.name,
  students.number,
  students.password,
  students.admission_date,
  students.graduation_date,
  classes.name AS class_name,
  student_status.name AS status_name,
  students.status_id,
  courses.name AS course_name,

  CASE 
    WHEN students.status_id = 1 THEN 1
    ELSE 0
  END AS is_active

FROM students 
INNER JOIN classes ON students.class_id = classes.id 
INNER JOIN student_status ON students.status_id = student_status.id 
INNER JOIN courses ON students.course_id = courses.id';

  $where = [];
  $params = [];

  if (!empty($class_id)) {
    $where[] = 'students.class_id = :class_id';
    $params[':class_id'] = $class_id;
  }

  if (!empty($keyword)) {
    $where[] = 'students.name LIKE :keyword';
    $params[':keyword'] = '%' . $keyword . '%';
  }

  if ($where) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
  }

  $sql .= ' ORDER BY classes.id ASC, students.number ASC';

  $stmt = $db->prepare($sql);

  foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val);
  }

  $stmt->execute();
  $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // クラス
  $stmt_classes = $db->query('SELECT id, name FROM classes ORDER BY id ASC');
  $classes = $stmt_classes->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $err_msg = $e->getMessage();
}


// ===== 予約 =====
$reserve_sql = "SELECT 
students.id,students.name, 
reservation_slots.date, 
times.time, 
carecons.name, 
methods.name AS method_name 
FROM reservation_infos 
JOIN students ON reservation_infos.student_id=students.id 
JOIN reservation_slots ON reservation_infos.slot_id=reservation_slots.id 
JOIN methods ON reservation_infos.method_id=methods.id 
JOIN times ON reservation_slots.time_id=times.id 
JOIN carecons ON reservation_slots.carecon_id=carecons.id
ORDER BY reservation_slots.date ASC";

$reserves = $db->query($reserve_sql)->fetchAll(PDO::FETCH_ASSOC);

$reserve_by_student = [];
foreach ($reserves as $r) {
  $reserve_by_student[$r['id']][] = $r;
}

require_once './../inc/header_admin.php';
?>

<div class="l-wrapper">

  <h1 class="c-title">訓練生一覧</h1>
  <?php if (!empty($_SESSION["msg"])): ?>
    <p class="alert alert-success" role="alert">
      <?php echo $_SESSION["msg"];
      unset($_SESSION["msg"]);
      ?>
    </p>
  <?php endif; ?>
  <?php if (!empty($_SESSION["err_msg"])): ?>
    <p class="alert alert-danger" role="alert">
      <?php echo $_SESSION["msg"];
      unset($_SESSION["msg"]);
      ?>
    </p>
  <?php endif; ?>

  <a href="student_add.php" class="btn btn-info mb-3">＋ 新規登録</a>

  <!-- 検索 -->
  <form method="GET" class="row g-2 mb-3">

    <div class="col-md-4">
      <input type="text" name="keyword" class="form-control" placeholder="名前検索" value="<?php echo h($keyword) ?>">
    </div>

    <div class="col-md-3">
      <select name="class_id" class="form-select">
        <option value="">全クラス</option>
        <?php foreach ($classes as $c): ?>
          <option value="<?php echo $c['id'] ?>" <?php echo $c['id'] == $class_id ? 'selected' : '' ?>>
            <?php echo h($c['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="col-md-2 d-flex gap-1">
      <button class="btn btn-primary w-100">検索</button>
      <a href="./students.php" class="btn btn-secondary w-100">リセット</a>
    </div>

  </form>

  <!-- テーブル -->
  <div class="table-responsive">
    <table class="table table-hover ">
      <thead class="table-light">
        <tr>
          <th>番号</th>
          <th>名前</th>
          <th>コース</th>
          <th>状態</th>
          <th>予約</th>
          <th>詳細</th>
          <th>操作</th>
        </tr>
      </thead>

      <tbody>

        <?php if (empty($students)): ?>
          <tr>
            <td colspan="6" class="text-center text-muted">データがありません</td>
          </tr>
        <?php endif; ?>

        <?php foreach ($students as $s):
          $hasReserve = isset($reserve_by_student[$s['id']]);
        ?>
          <tr style="cursor:pointer;">
            <td><?php echo h($s['class_name'] . $s['number']) ?></td>
            <td><?php echo h($s['name']) ?></td>
            <td><?php echo h($s['course_name']) ?></td>

            <td>
              <?php if ($s['is_active']): ?>
                <span class="badge bg-success"><?php echo h($s['status_name']) ?></span>
              <?php else: ?>
                <span class="badge bg-danger"><?php echo h($s['status_name']) ?></span>
              <?php endif; ?>
            </td>

            <td>
              <?php if ($hasReserve): ?>
                <span class="badge bg-info">あり</span>
              <?php else: ?>
                <span class="badge bg-light text-dark">なし</span>
              <?php endif; ?>
            </td>

            <td>
              <button class="btn btn-sm btn-warning"
                data-bs-toggle="modal"
                data-bs-target="#studentModal"
                data-id="<?php echo $s['id'] ?>"
                data-name="<?php echo h($s['name']) ?>"
                data-number="<?php echo h($s['class_name'] . $s['number']) ?>"
                data-course="<?php echo h($s['course_name']) ?>"
                data-admission="<?php echo $s['admission_date'] ?>"
                data-graduation="<?php echo $s['graduation_date'] ?>"
                data-pass="<?php echo h($s['password']) ?>"
                data-status="<?php echo h($s['status_name']) ?>"
                data-login="<?php echo h($s['login_id']) ?>">
                詳細
              </button>
            </td>
            <td>
              <button type="button" id="modal-edit-btn" class="btn btn-primary btn-sm">
                編集
              </button>
              <button type="button" id="modal-delete-btn" class="btn btn-danger btn-sm">
                削除
              </button>
            </td>
          </tr>
        <?php endforeach; ?>

      </tbody>
    </table>
  </div>


  <!-- 詳細モーダル -->
  <div class="modal fade" id="studentModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">訓練生詳細</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <h6 class="text-muted">基本情報</h6>
          <p>番号：<span id="modal-number"></span></p>
          <p>名前：<span id="modal-name"></span></p>
          <p>コース：<span id="modal-course"></span></p>

          <h6 class="text-muted mt-3">期間</h6>
          <p>入校：<span id="modal-admission"></span></p>
          <p>修了：<span id="modal-graduation"></span></p>

          <h6 class="text-muted mt-3">アカウント</h6>
          <p>ID：<span id="modal-login"></span></p>
          <p>PASS：<span id="modal-pass"></span></p>

          <h6 class="text-muted mt-3">予約</h6>
          <div id="modal-reserve"></div>
        </div>
      </div>
    </div>
  </div>

</div>


<script>
  const reserveData = <?php echo json_encode($reserve_by_student) ?>;
  // モーダル
  const modal = document.getElementById('studentModal');

  modal.addEventListener('show.bs.modal', function(e) {
    const btn = e.relatedTarget;

    document.getElementById('modal-number').textContent = btn.dataset.number;
    document.getElementById('modal-name').textContent = btn.dataset.name;
    document.getElementById('modal-course').textContent = btn.dataset.course;
    document.getElementById('modal-admission').textContent = btn.dataset.admission;
    document.getElementById('modal-graduation').textContent = btn.dataset.graduation;
    document.getElementById('modal-login').textContent = btn.dataset.login;
    document.getElementById('modal-pass').textContent = btn.dataset.pass;

    const list = reserveData[btn.dataset.id];

    let html = '';

    if (list) {
      list.forEach(r => {
        html += `
      <div class="card p-2 mb-2">
        <div>${r.date} ${r.time}</div>
        <div>${r.name}</div>
        <div>${r.method_name}</div>
      </div>`;
      });
    } else {
      html = '予約なし';
    }

    document.getElementById('modal-reserve').innerHTML = html;


  });
</script>

<?php require_once './../inc/footer.php'; ?>