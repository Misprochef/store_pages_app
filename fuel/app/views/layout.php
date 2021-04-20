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