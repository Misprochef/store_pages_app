<div id="student-manage" class="main-right ml40">
  <div style="width: 50%;">
    <p class="mb20" style="font-size: large; font-weight: bold;">ページを編集</p>

    <?php echo Form::open() ?>
    <?php echo Form::hidden(Config::get('security.csrf_token_key'), Security::fetch_token()) ?>

    URL : <?php echo Form::input('url', $page_url, array('required' => 'required', 'class' => 'mb10')) ?><br />
    タイトル :
    <?php echo Form::input('title', $page_title, array('type' => 'text', 'required' => 'required', 'class' => 'mb10')) ?><br />
    <?php
    $arr_folder = array();
    foreach ($folders as $folder) {
        $arr_folder = array_merge($arr_folder, array($folder['name'] => $folder['name']));
    }
    ?>
    フォルダーを選択 :
    <span class="flex">
      <?php echo Form::select('folder', $page_folder, array_merge(array(null => '登録しない'), $arr_folder), ['class' => 'dropdown']) ?>
      <?php echo Form::submit('submit_btn', '送信', ['class' => 'btn btn-blue ml30']) ?>
    </span>

    <?php echo Form::close() ?>
  </div>
</div>