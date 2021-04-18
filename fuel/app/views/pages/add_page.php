<div id="form">
  <?php
  use Fuel\Core\Form;

  ?>

  <h1>新規ページを登録</h1>

  <?php if ($title_not_getted): ?>
  <h1>タイトルが自動抽出できませんでした。手入力で再度の登録をお願いします。</h1>
  <?php endif ?>

  <?php echo Form::open() ?>
  <?php echo Form::hidden(Config::get('security.csrf_token_key'), Security::fetch_token()) ?>

  url : <?php echo Form::input('url', null, array('required' => 'required')) ?><br />
  タイトル : <?php echo Form::input('title', null, array('type' => 'text', 'placeholder' => '（記入がない場合は自動抽出）')) ?><br />
  <?php
  $arr_folder = array();
  foreach ($folders as $folder) {
      $arr_folder = array_merge($arr_folder, array($folder['name'] => $folder['name']));
  }
  ?>
  フォルダーを選択 : <?php
  echo Form::select('folder', '', array_merge(array(null => '登録しない'), $arr_folder))
  ?>

  <?php echo Form::submit('submit_btn', '送信') ?>
  <?php echo Form::close() ?>
</div>