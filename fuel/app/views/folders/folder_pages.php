<div id="student-manage" class="main-right">

  <div>
    <span class="flex ml40 mb30" style="justify-content: space-between; margin-right: 180px;">
      <?php if ($err_msg): ?>
      <h2 class="mb30" style="font-size: 25px;">対象のWebページが見つかりません。入力したURLに、間違いがないか確認してください。<br> エラーメッセージ :
        <?= $err_msg ?></h2>
      <?php endif ?>
      <h2 style="font-size: 25px;"><?= $folder_name ?></h1>
        <button data-bind="click: mutualConvRegAndEditPage" class="mr40 mt10 btn header-btn">編集</button>
    </span>
    <ul class="list-group flex flex-wrap">
      <?php if ($pages): ?>
      <?php foreach ($pages as $page): ?>
      <li class="mb30 ml30" style="width: 350px; height: 300px; padding: 15px; background-color: white;">
        <div class="list-item-header flex" style="flex-direction: column;"">
            <a href=<?php echo $page['url']; ?> target=" _blank" rel="noreferrer"><?php echo $page['title']; ?></a>
          <div class="flex" style="justify-content: space-between;">
            <p class="mt5">
              <?php echo date('Y/m/d', strtotime($page['updated_at'])); ?>
            </p>
            <span data-bind="visible: stateBoolIndex" class="mt5 mr20">
              <a href="/pages/edit_page/<?php echo $page['id']; ?>">編集</a>
              <a href="/pages/delete_page/<?php echo $page['id']; ?>" class="ml5">削除</a>
            </span>
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
      <?php endforeach ?>
      <?php endif ?>
    </ul>
  </div>

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