<div id="form">
  <?php
  use Fuel\Core\Form;

  ?>

  <h1>ページを編集</h1>

  <?php echo Form::open() ?>
  <?php echo Form::hidden(Config::get('security.csrf_token_key'), Security::fetch_token()) ?>

  url : <?php echo Form::input('url', $page_url, array('required' => 'required')) ?><br />
  タイトル : <?php echo Form::input('title', $page_title, array('type' => 'text', 'required' => 'required')) ?><br />
  <?php
  $arr_folder = array();
  foreach ($folders as $folder) {
      $arr_folder = array_merge($arr_folder, array($folder['name'] => $folder['name']));
  }
  ?>
  フォルダーを選択 : <?php
  echo Form::select('folder', $page_folder, array_merge(array(null => '登録しない'), $arr_folder))
  ?>

  <?php echo Form::submit('submit_btn', '送信') ?>
  <?php echo Form::close() ?>
</div>