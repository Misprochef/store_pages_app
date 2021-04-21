<div id="student-manage" class="main-right">
  <?php if ($err_msg): ?>
  <h1>対象のWebページが見つかりません。入力したURLに、間違いがないか確認してください。<br> エラーメッセージ : <?= $err_msg ?></h1>
  <?php endif ?>

  <div id="students">
    <div id="student_list">
      <h2 class="ml40 mb30" style="font-size: 25px;">All Pages</h2>
      <ul class="list-group flex flex-wrap" style="justify-content: space-around;">
        <?php
        $num = 0;
        foreach ($pages_not_in_folder as $page):
        $num++;
        ?>
        <?php if ($page): ?>
        <li id=":<?= $num ?>" class="mb30" style="width: 350px; height: 300px; padding: 15px; background-color: white;">
          <div class="list-item-header flex" style="flex-direction: column;"">
            <a href=<?php echo $page['url']; ?> target=" _blank" rel="noreferrer"><?php echo $page['title']; ?></a>
            <div class="flex" style="justify-content: space-between;">
              <span data-bind="visible: stateBoolIndex" class="mt5 mr20">
                <a href="/pages/edit_page/<?php echo $page['title']; ?>">編集</a>
                <a href="/pages/delete_page/<?php echo $page['title']; ?>">削除</a>
              </span>
              <p class="mt5">
                <?php echo date('Y/m/d', strtotime($page['updated_at'])); ?>
              </p>
            </div>
          </div>
          <div class="list-item-body">
            <?php
            if ($page['img_path']) {
                echo Asset::img($page['img_path'], ['style' => 'aspect-ratio: 16 / 9;', 'class' => 'w100']);
            } elseif ($page['fav_path']) {
                echo Asset::img($page['fav_path'], ['style' => 'width: 60%; margin-left: 20%;', 'class' => 'mb20']);
            } else {
                echo 'img or favicon not found';
            }
            ?>
          </div>
        </li>
        <?php endif ?>
        <?php endforeach ?>
      </ul>
    </div>
  </div>


  <?php if ($pages_in_folder_arr != array()): ?>
  <?php foreach ($pages_in_folder_arr as $pages_in_folder => $pages): ?>
  <h1><?= $pages_in_folder?></h1>

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
    <a href="/pages/edit_page/<?php echo $page['title']; ?>">編集</a>
    <a href="/pages/delete_page/<?php echo $page['title']; ?>">削除</a>
  </span>
  <?php endforeach ?>
  <?php endif ?>

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