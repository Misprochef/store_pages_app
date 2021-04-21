<?php include_once(dirname(__FILE__) . '/head.php'); ?>

<?php
  use Fuel\Core\Asset;

  echo Asset::css('style.css')
?>

<body style="background-color: #F5F5F5;">
  <header style="background-color: white;">
    <div id="header">
      <a class="pl20" id="logo" href="/pages/index">
        <span> Store Pages App</span>
      </a>
      <span class="flex">
        <a href="/pages/index" class="btn header-btn mr20">Home</a>
        <a href="/pages/add_page" class="btn header-btn">ページを追加</a>
        <?php if ($title == "Store Pages App index-page" or $title == "Store Pages App フォルダー内のページ一覧"): ?>
        <button data-bind="click: mutualConvRegAndEditPage" class="mr40 btn header-btn">ページを編集</button>
        <?php endif ?>
      </span>
    </div>
    </div>
    </div>
  </header>

  <main>
    <div class="flex">
      <?php
    if ($disp_sidebar) {
        include(dirname(__FILE__) . '/sidebar.php');
    } ?>

      <?php echo $content; ?>
    </div>
  </main>

</body>

</html>