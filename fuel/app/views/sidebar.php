<div id="sidebar">
  <aside>
    <h1>サイドバー</h1>
    <button data-bind="click: mutualConvRegAndEdit">編集</button>
    <?php foreach ($folders as $folder): ?>
    <section>
      <a href="/folders/folder_pages/<?php echo $folder['name']; ?>"><?php echo $folder['name']; ?></a>
      <span data-bind="visible: stateBool">
        <a href="/folders/edit_folder/<?php echo $folder['name']; ?>">Edit</a>
        <a href="/folders/delete_folder/<?php echo $folder['name']; ?>">Delete</a>
      </span>
    </section>
    <?php endforeach ?>
    <a href="/folders/add_folder/<?php echo $folder['name']; ?>">フォルダーを追加</a>
    <br>
    <br>
    <a href="/folders/index">フォルダーの一覧</a>

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

<script>
let viewModel = {
  state: ko.observable('Regular'),
  stateBool: ko.observable(false),
  mutualConvRegAndEdit: function() {
    if (this.stateBool() === false) {
      this.state('Edit');
      this.stateBool(true);
    } else if (this.stateBool() === true) {
      this.state('Regular');
      this.stateBool(false);
    }
  },
  stateBoolIndex: null,
  mutualConvRegAndEditPage: null
};
ko.applyBindings(viewModel);
</script>