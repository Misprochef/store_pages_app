<div id="pages-index" class="main-right">

  <div>
    <div id="main">

      <span class="flex flex-wrap ml40 mb30" style="justify-content: space-between; margin-right: 180px;">
        <?php if ($err_msg): ?>
        <h2 class="mb30" style="font-size: 25px;">対象のWebページが見つかりません。入力したURLに、間違いがないか確認してください。<br> エラーメッセージ :
          <?= $err_msg ?></h2>
        <?php endif ?>
        <h2 style="font-size: 25px;">All Pages</h2>
        <span style="width: 20%;"></span>
        <button data-bind="click: $root.mutualConvRegAndEditPage" class="mr40 mt10 btn header-btn">編集</button>
      </span>
      <?php if ($pages_not_in_folder): ?>
      <ul data-bind="sortable: cardsNotInFolder" class="list-group flex flex-wrap">
        <li class="mb30 ml30" data-bind="click: $root.selectedTask"
          style="width: 350px; height: 300px; padding: 15px; background-color: white;">
          <div class="list-item-header flex" style="flex-direction: column;"">
            <a data-bind=" attr: { href: url }, click: ()=> true;, clickBubble: false, text: title" target=" _blank"
            rel="noreferrer">
            </a>
            <div class="flex" style="justify-content: space-between;">
              <p data-bind="text: updated_at" class="mt5"></p>
              <span data-bind="visible: $root.stateBoolIndex" class="mt5 mr20">
                <a
                  data-bind=" attr: { href: '<?= \Uri::create('/pages/edit_page/'); ?>' + $data.id }, click: ()=> true;, clickBubble: false">編集</a>
                <a data-bind=" attr: { href: '<?= \Uri::create('/pages/delete_page/'); ?>' + $data.id }, click: ()=> true;, clickBubble: false"
                  class="ml5">削除</a>
              </span>
            </div>
          </div>
          <div class="list-item-body">
            <img data-bind="visible: img_path, attr: { src: '<?= \Uri::create('assets/img/'); ?>' + $data.img_path }"
              class="w100" style="aspect-ratio: 16 / 9;">
            <img data-bind="visible: fav_path, attr: { src: '<?= \Uri::create('assets/img/'); ?>' + $data.fav_path }"
              class="mb20" style="width: 60%; margin-left: 20%;">
          </div>
        </li>
        <?php endif ?>
      </ul>
    </div>

    <?php if ($pages_in_folder_arr != array()): ?>
    <?php
       foreach ($pages_in_folder_arr as $pages_in_folder => $pages):
    ?>
    <div class="mt40 pt10">
      <span class="flex ml40 mb30" style="justify-content: space-between; margin-right: 180px;">
        <h2 class="" style="font-size: 25px;"><?php
        echo $pages_in_folder
         ?></h2>
        <button data-bind="click: $root.mutualConvRegAndEditPage" class="mr40 mt10 btn header-btn">編集</button>
      </span>
      <ul class="list-group flex flex-wrap">
        <?php
        if ($pages):
        ?>
        <?php
        foreach ($pages as $page):
        ?>
        <li class="mb30 ml30" style="width: 350px; height: 300px; padding: 15px; background-color: white;">
          <div class="list-item-header flex" style="flex-direction: column;"">
            <a href=<?php echo $page['url']; ?> target=" _blank" rel="noreferrer"><?php echo $page['title']; ?></a>
            <div class="flex" style="justify-content: space-between;">
              <p class="mt5">
                <?php echo date('Y/m/d', strtotime($page['updated_at'])); ?>
              </p>
              <span data-bind="visible: $root.stateBoolIndex" class="mt5 mr20">
                <a href="/pages/edit_page/<?php echo $page['id']; ?>">編集</a>
                <a href="/pages/delete_page/<?php echo $page['id']; ?>" class="ml5">削除</a>
              </span>
            </div>
          </div>
          <div class="list-item-body">
            <?php
            if ($page['img_path']) {
                echo Asset::img($page['img_path'], ['style' => 'aspect-ratio: 16 / 9;', 'class' => 'w100']);
            } elseif ($page['fav_path']) {
                echo Asset::img($page['fav_path'], ['style' => 'width: 60%; margin-left: 20%;', 'class' => 'mb20']);
            } else {
                echo 'img or favicon not found';
            }
            ?>
          </div>
        </li>
        <?php
        endforeach
        ?>
        <?php
        endif
        ?>
      </ul>
    </div>
    <?php
     endforeach
    ?>
    <?php endif ?>

  </div>
</div>

<script type="text/javascript" src="/assets/js/knockout-3.5.1.js"></script>
<script src="/assets/js/jquery-ui-1.12.1/external/jquery/jquery.js"></script>
<script src="/assets/js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
<script src="/assets/js/knockout-sortable.min.js"></script>

<script>
let viewModelSortable = function() {
  let self = this;

  self.stateBoolIndex = ko.observable(false);

  self.mutualConvRegAndEditPage = function() {
    if (self.stateBoolIndex() === false) {
      self.stateBoolIndex(true);
    } else if (self.stateBoolIndex() === true) {
      self.stateBoolIndex(false);
    }
  }

  let pagesNotInFolder = <?= json_encode($pages_not_in_folder) ?>;

  self.cardsNotInFolder = ko.observableArray(
    pagesNotInFolder
  );

  self.sortPages = function() {
    var post_pagesNotInFolder = $(self.cardsNotInFolder()).map(function(index, card) {
      return {
        id: card.id,
        page_order: index
      };
    }).get();

    $.ajax({
      type: 'POST',
      url: '/sortable/cards.json',
      data: {
        'cards': post_pagesNotInFolder
      },
      dataType: 'text',
      async: true
    }).done(function(res) {
      console.log(res);
    }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
      console.log(XMLHttpRequest.status);
      console.log(textStatus);
      console.log(errorThrown);
    });
  }
};

let viewModelSortableObj = new viewModelSortable();
ko.bindingHandlers.sortable.afterMove = viewModelSortableObj.sortPages;

ko.applyBindings(viewModelSortableObj, document.getElementById('pages-index'));
</script>