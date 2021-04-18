<?php include_once(dirname(__FILE__) . '/head.php'); ?>
<!-- 上の方法と、controller内の各クラス内で
  $view->head = View::forge('head')とする（値は、$view->set_global('title', '渡すタイトル名')）か、
  もしくは、viewファイル内で
  echo render('head')の3つのうち、どれが速いだろうか？ -->

<?php
  use Fuel\Core\Asset;

  echo Asset::css('style.css')
?>

<body>
  <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">

        <button type="button" class="navbar-toggle collapsed" datatoggle="collapse" data-target="#navbar"
          aria-expanded="false" ariacontrols="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="/pages/index">Store Pages App</a>
      </div>

      <div id="navbar" class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
          <li class="active"><a href="/pages/index">Home</a></li>
          <li><a href="/pages/add_page">Add page</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <?php
  if ($title == "Store Pages App index-page" or $title == "Store Pages App フォルダー内のページ一覧"):
  ?>

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

  <?php
  if ($disp_sidebar) {
      include(dirname(__FILE__) . '/sidebar.php');
  } ?>

  <div class="container">
    <div class="starter-template" style="padding: 50px 0 0 0;">
      <?php
      echo $content;
      ?>
    </div>
  </div>

</body>

</html>