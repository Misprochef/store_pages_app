<div id="student-manage" class="main-right ml40">
  <div style="width: 50%;">
    <p class="mb20" style="font-size: large; font-weight: bold;">新規ページの登録</p>

    <?php if ($title_not_getted): ?>
    <h2 class="mb30" style="font-size: 25px;">タイトルが自動抽出できませんでした。手入力で再度の登録をお願いします。</h2>
    <?php endif ?>

    <?php echo Form::open() ?>
    <?php echo Form::hidden(Config::get('security.csrf_token_key'), Security::fetch_token()) ?>

    URL : <?php echo Form::input('url', null, array('required' => 'required', 'class' => 'mb10')) ?><br />
    タイトル :
    <?php echo Form::input('title', null, array('type' => 'text', 'placeholder' => '(記入がない場合は自動抽出)', 'class' => 'mb10')) ?><br />
    <?php
    $arr_folder = array();
    foreach ($folders as $folder) {
        $arr_folder = array_merge($arr_folder, array($folder['name'] => $folder['name']));
    }
    ?>
    フォルダーを選択 :
    <span class="flex">
      <?php echo Form::select('folder', '', array_merge(array(null => '登録しない'), $arr_folder), ['class' => 'dropdown']) ?>
      <?php echo Form::submit('submit_btn', '送信', ['class' => 'btn btn-blue ml30']) ?>
    </span>

    <?php echo Form::close() ?>
  </div>
</div>