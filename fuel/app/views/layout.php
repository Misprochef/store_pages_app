<?php include_once(dirname(__FILE__) . '/head.php'); ?>

<?php
  use Fuel\Core\Asset;

  echo Asset::css('style.css')
?>

<body>
  <header>
    <div id="header">
      <a class="" id="logo" href="/pages/index">
        <span> Store Pages App</span>
      </a>
      <!-- <a href="/pages/index" class="btn header-btn">Home</a> -->
      <a href="/pages/add_page" class="btn header-btn">ページを追加</a>
      <div class="header-right">
        <div>
          <button data-bind="click: mutualConvRegAndEditPage" class="mr40">編集</button>
        </div>
      </div>
    </div>
  </header>

  <div class="flex">
    <?php
    if ($disp_sidebar) {
        include(dirname(__FILE__) . '/sidebar.php');
    } ?>

    <!-- <div class="justify-between"> -->
    <div class="pt40">
      <?php
          if ($title == "Store Pages App index-page" or $title == "Store Pages App フォルダー内のページ一覧"):
          ?>

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

    <?php
      echo $content;
    ?>
    <!-- </div> -->
  </div>

</body>

</html>