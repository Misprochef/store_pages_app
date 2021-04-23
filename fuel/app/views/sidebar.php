<div id="sidebar" class="pl20">
  <aside class="flex" style="flex-direction: column;">
    <span class="flex">
      <h2 class="mb10" style="font-size: 20px;">
        <a href="/folders/index" class="bold">Folders</a>
      </h2>
      <button onclick="location.href='/folders/add_folder/'" class="ml30 btn">追加</button>
      <button data-bind="click: mutualConvRegAndEdit" class="ml5 btn">編集</button>
    </span>
    <span class="pt10 pb10 mt10 mb10" data-bind="style: colorHerePath('/pages/index')">
      <a href="/pages/index" class="ml10" data-bind="style: colorHerePath('/pages/index')">ホーム</a>
    </span>
    <?php if ($folders): ?>
    <?php foreach ($folders as $folder): ?>
    <?php $thisPath = "/folders/folder_pages/{$folder['name']}"; ?>
    <span class="pt5 pb5 flex justify-between" data-bind="style: colorHerePath('<?= $thisPath; ?>')">
      <a href="<?= $thisPath; ?>" class="pt5 pb5 ml20"
        data-bind="style: colorHerePath('<?= $thisPath; ?>')"><?php echo $folder['name']; ?></a>
      <span data-bind="visible: stateBool" class="flex" style="flex-direction: column;">
        <a href="/folders/edit_folder/<?php echo $folder['name']; ?>" class="mb5">編集</a>
        <a href="/folders/delete_folder/<?php echo $folder['id']; ?>" class="">削除</a>
      </span>
    </span>
    <?php endforeach ?>
    <?php endif ?>

    <div class="mt30">
      <?php
        if ($title == "Store Pages App index-page" or $title == "Store Pages App フォルダー内のページ一覧"):
      ?>

      <p class="mb10" style="font-size: medium; font-weight: bold;">ページの登録</p>
      <?php echo Form::open() ?>
      <?php echo Form::hidden(Config::get('security.csrf_token_key'), Security::fetch_token()) ?>

      URL : <?php echo Form::input('url', null, array('required' => 'required', 'class' => 'mb10')) ?>
      タイトル :
      <?php echo Form::input('title', null, array('type' => 'text', 'placeholder' => '(記入がない場合は自動抽出)', 'class' => 'mb10')) ?><br />

      <?php if ($title == "Store Pages App index-page"): ?>
      フォルダーを選択 :
      <span class="flex">
        <?php echo Form::select('folder', '', $arr_folder, ['class' => 'dropdown']) ?>
        <?php echo Form::submit('submit_btn', '送信', ['class' => 'btn btn-blue ml30']) ?>
      </span>

      <?php elseif ($title == "Store Pages App フォルダー内のページ一覧"): ?>
      <span class="flex">
        <?php echo Form::select('folder', $folder_name, $arr_folder, ['style' => 'display: none;']) ?>
        <?php echo Form::submit('submit_btn', 'このフォルダー内に追加', ['class' => 'btn btn-blue mt10']) ?>
      </span>
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
ko.applyBindings(viewModel, document.getElementById('sidebar'));
</script>