<div id="student-manage" class="main-right">
  <?php if ($err_msg): ?>
  <h1>対象のWebページが見つかりません。入力したURLに、間違いがないか確認してください。<br> エラーメッセージ : <?= $err_msg ?></h1>
  <?php endif ?>
  <h1><?= $folder_name ?></h1>

  <?php if ($pages): ?>
  <?php foreach ($pages as $page): ?>
  <p><?php echo $page['id']; ?></p>
  <a href=<?php echo $page['url']; ?> target="_blank" rel="noreferrer"><?php echo $page['title']; ?></a>
  <p><?php echo $page['url']; ?></p>
  <?php
  if ($page['img_path']) {
      echo Asset::img($page['img_path']);
  } elseif ($page['fav_path']) {
      echo Asset::img($page['fav_path']);
  } else {
      echo 'img or favicon not found';
  }
  ?>
  <?php echo date('Y/m/d', strtotime($page['updated_at'])); ?>
  <span data-bind="visible: stateBoolIndex">
    <a href="/pages/edit_page/<?php echo $page['title']; ?>">Edit</a>
    <a href="/pages/delete_page/<?php echo $page['title']; ?>">Delete</a>
  </span>
  <?php endforeach ?>
  <?php endif ?>
</div>

<script type="text/javascript" src="/assets/js/knockout-3.5.1.js"></script>
<script>
let viewModelIndex = {
  stateBoolIndex: ko.observable(false),
  mutualConvRegAndEditPage: function() {
    if (this.stateBoolIndex() === false) {
      this.stateBoolIndex(true);
    } else if (this.stateBoolIndex() === true) {
      this.stateBoolIndex(false);
    }
  },
  stateBool: null,
  mutualConvRegAndEdit: null,
  colorHerePath: function() {
    return null
  }
};
ko.applyBindings(viewModelIndex);
</script>