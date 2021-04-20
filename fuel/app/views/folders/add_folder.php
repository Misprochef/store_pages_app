<div id="student-manage" class="main-right mt40">
  <?php
  use Fuel\Core\Form;

  ?>

  <?php if ($err_msg): ?>
  <h1><?= $err_msg ?></h1>
  <?php endif ?>

  <h1>新規フォルダーの登録</h1>

  <?php echo Form::open() ?>
  <?php echo Form::hidden(Config::get('security.csrf_token_key'), Security::fetch_token()) ?>

  フォルダー名 : <?php echo Form::input('folder', null, array('required' => 'required')) ?><br />

  <?php echo Form::submit('submit_btn', '送信') ?>
  <?php echo Form::close() ?>
</div>