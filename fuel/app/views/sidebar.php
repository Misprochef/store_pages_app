<div id="sidebar" class="pl20">
  <aside class="flex" style="flex-direction: column;">
    <h2 class="mb10" style="font-size: 20px;">Folders</h2>
    <button data-bind="click: mutualConvRegAndEdit">編集</button>
    <span class="pt10 pb10 mt10 mb10" data-bind="style: colorHerePath('/pages/index')">
      <a href="/pages/index" class="ml10" data-bind="style: colorHerePath('/pages/index')">ホーム</a>
    </span>
    <?php if ($folders): ?>
    <?php foreach ($folders as $folder): ?>
    <?php $thisPath = "/folders/folder_pages/{$folder['name']}"; ?>
    <span class="pt5 pb5 mt5 mb5" data-bind="style: colorHerePath('<?= $thisPath; ?>')">
      <a href="<?= $thisPath; ?>" class="pt5 pb5 ml20"
        data-bind="style: colorHerePath('<?= $thisPath; ?>')"><?php echo $folder['name']; ?></a>
      <span data-bind="visible: stateBool">
        <a href="/folders/edit_folder/<?php echo $folder['name']; ?>" class="ml10">編集</a>
        <a href="/folders/delete_folder/<?php echo $folder['name']; ?>">削除</a>
      </span>
    </span>
    <?php endforeach ?>
    <?php endif ?>
    <a href="/folders/add_folder/<?php echo $folder['name']; ?>" class="ml20 mt30">フォルダーを追加</a>
    <!-- <a href="/folders/index">フォルダーの一覧</a> -->

    <div class="mt40">
      <?php
        if ($title == "Store Pages App index-page" or $title == "Store Pages App フォルダー内のページ一覧"):
      ?>

      <span class="pt40 mt40">ページの登録</span>
      <?php echo Form::open() ?>
      <?php echo Form::hidden(Config::get('security.csrf_token_key'), Security::fetch_token()) ?>

      url : <?php echo Form::input('url', null, array('required' => 'required')) ?><br />
      タイトル :
      <?php echo Form::input('title', null, array('type' => 'text', 'placeholder' => '（記入がない場合は自動抽出）')) ?><br />

      <?php
          $arr_folder = array();
          foreach ($folders as $folder) {
              $arr_folder = array_merge($arr_folder, array($folder['name'] => $folder['name']));
          }
          ?>

      <?php if ($title == "Store Pages App index-page"): ?>
      フォルダーを選択 :
      <?php echo Form::select('folder', '', array_merge(array(null => '登録しない'), $arr_folder)) ?>
      <?php echo Form::submit('submit_btn', '送信') ?>

      <?php elseif ($title == "Store Pages App フォルダー内のページ一覧"): ?>
      <?php echo Form::select('folder', $folder_name, array_merge(array(null => '登録しない'), $arr_folder), ['style' => 'display: none;']) ?>
      <?php echo Form::submit('submit_btn', 'このフォルダー内に追加') ?>
      <?php endif ?>

      <?php echo Form::close() ?>

      <?php endif ?>
    </div>
  </aside>
</div>

<script type="text/javascript" src="/assets/js/knockout-3.5.1.js"></script>
<script>
let viewModel = {
  stateBool: ko.observable(false),
  mutualConvRegAndEdit: function() {
    if (this.stateBool() === false) {
      this.stateBool(true);
    } else if (this.stateBool() === true) {
      this.stateBool(false);
    }
  },
  stateBoolIndex: null,
  mutualConvRegAndEditPage: null,
  colorHerePath: function(path) {
    if (path == decodeURIComponent(location.pathname)) {
      return {
        backgroundColor: 'darkgray',
        color: 'white'
      }
    }
  }
};
ko.applyBindings(viewModel);
</script>