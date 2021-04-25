<div id="folder-pages" class="main-right">

  <div>
    <span class="flex ml40 mb30" style="justify-content: space-between; margin-right: 180px;">
      <?php if ($err_msg): ?>
      <h2 class="mb30" style="font-size: 25px;">対象のWebページが見つかりません。入力したURLに、間違いがないか確認してください。<br> エラーメッセージ :
        <?= $err_msg ?></h2>
      <?php endif ?>
      <h2 style="font-size: 25px;"><?= $folder_name ?></h1>
        <button data-bind="click: mutualConvRegAndEditPage" class="mr40 mt10 btn header-btn">編集</button>
    </span>
    <?php if ($pages): ?>
    <ul data-bind="sortable: cardsInFolder" class="list-group flex flex-wrap">
      <li class="mb30 ml30" style="width: 350px; height: 300px; padding: 15px; background-color: white;">
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
    </ul>
    <?php endif ?>
  </div>

</div>

<script type="text/javascript" src="/assets/js/knockout-3.5.1.js"></script>
<script src="/assets/js/jquery-ui-1.12.1/external/jquery/jquery.js"></script>
<script src="/assets/js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
<script src="/assets/js/knockout-sortable.min.js"></script>

<script>
let viewModel = function() {
  let self = this;

  self.stateBoolIndex = ko.observable(false);

  self.mutualConvRegAndEditPage = function() {
    if (self.stateBoolIndex() === false) {
      self.stateBoolIndex(true);
    } else if (self.stateBoolIndex() === true) {
      self.stateBoolIndex(false);
    }
  };

  let pagesInFolder = <?= json_encode($pages) ?>;

  self.cardsInFolder = ko.observableArray(
    pagesInFolder
  );

  self.sortPages = function() {
    var post_pagesInFolder = $(self.cardsInFolder()).map(function(index, card) {
      return {
        id: card.id,
        folder_id: card.folder_id,
        page_order: index
      };
    }).get();

    $.ajax({
      type: 'POST',
      url: '/sortable/cards.json',
      data: {
        'cards': post_pagesInFolder
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

let viewModelObj = new viewModel();
ko.bindingHandlers.sortable.afterMove = viewModelObj.sortPages;

ko.applyBindings(viewModelObj, document.getElementById('folder-pages'));
</script>