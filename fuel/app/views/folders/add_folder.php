<div id="student-manage" class="main-right ml40">
  <div style="width: 50%;">
    <p class="mb20" style="font-size: large; font-weight: bold;">新規フォルダーの登録</p>

    <?php if ($err_msg): ?>
    <h2 class="mb30" style="font-size: 25px;"><?= $err_msg ?></h2>
    <?php endif ?>

    <?php echo Form::open() ?>
    <?php echo Form::hidden(Config::get('security.csrf_token_key'), Security::fetch_token()) ?>

    <p class="mb10">フォルダー名 :</p>
    <span class="flex">
      <?php echo Form::input('folder', null, array('required' => 'required')) ?>
      <?php echo Form::submit('submit_btn', '送信', ['class' => 'btn btn-blue ml20']) ?>
    </span>

    <?php echo Form::close() ?>
  </div>
</div>