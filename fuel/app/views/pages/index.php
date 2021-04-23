<div id="pages-index" class="main-right">

  <div>
    <div id="main">
      <h3>Tasks</h3>
      <div class="container" data-bind="sortable: tasks">
        <div class="item">
          <span data-bind="visible: !$root.isTaskSelected($data)">
            <a href="#" data-bind="text: name, click: $root.selectedTask"></a>
          </span>
          <span data-bind="visibleAndSelect: $root.isTaskSelected($data)">
            <input data-bind="value: name, event: { blur: $root.clearTask }" />
          </span>
        </div>
      </div>
      <a href="#" data-bind="click: addTask">Add Task</a>
    </div>

    <div id="results">
      <h3>Tasks</h3>
      <ul data-bind="foreach: tasks">
        <li data-bind="text: name"></li>
      </ul>
    </div>

    <span class="flex flex-wrap ml40 mb30" style="justify-content: space-between; margin-right: 180px;">
      <?php if ($err_msg): ?>
      <h2 class="mb30" style="font-size: 25px;">対象のWebページが見つかりません。入力したURLに、間違いがないか確認してください。<br> エラーメッセージ :
        <?= $err_msg ?></h2>
      <?php endif ?>
      <h2 style="font-size: 25px;">All Pages</h2>
      <span style="width: 20%;"></span>
      <button data-bind="click: mutualConvRegAndEditPage" class="mr40 mt10 btn header-btn">編集</button>
    </span>
    <ul class="list-group flex flex-wrap">
      <span style="width: 100%;"></span>
      <?php if ($pages_not_in_folder): ?>
      <?php foreach ($pages_not_in_folder as $page): ?>
      <li class="mb30 ml30" style="width: 350px; height: 300px; padding: 15px; background-color: white;">
        <div class="list-item-header flex" style="flex-direction: column;"">
            <a href=<?php echo $page['url']; ?> target=" _blank" rel="noreferrer"><?php echo $page['title']; ?></a>
          <div class="flex" style="justify-content: space-between;">
            <p class="mt5">
              <?php echo date('Y/m/d', strtotime($page['updated_at'])); ?>
            </p>
            <span data-bind="visible: stateBoolIndex" class="mt5 mr20">
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
      <?php endforeach ?>
      <?php endif ?>
    </ul>
  </div>

  <?php if ($pages_in_folder_arr != array()): ?>
  <?php foreach ($pages_in_folder_arr as $pages_in_folder => $pages): ?>
  <div class="mt40 pt10">
    <span class="flex ml40 mb30" style="justify-content: space-between; margin-right: 180px;">
      <h2 class="" style="font-size: 25px;"><?= $pages_in_folder?></h2>
      <button data-bind="click: mutualConvRegAndEditPage" class="mr40 mt10 btn header-btn">編集</button>
    </span>
    <ul class="list-group flex flex-wrap">
      <?php if ($pages): ?>
      <?php foreach ($pages as $page): ?>
      <li class="mb30 ml30" style="width: 350px; height: 300px; padding: 15px; background-color: white;">
        <div class="list-item-header flex" style="flex-direction: column;"">
            <a href=<?php echo $page['url']; ?> target=" _blank" rel="noreferrer"><?php echo $page['title']; ?></a>
          <div class="flex" style="justify-content: space-between;">
            <p class="mt5">
              <?php echo date('Y/m/d', strtotime($page['updated_at'])); ?>
            </p>
            <span data-bind="visible: stateBoolIndex" class="mt5 mr20">
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
      <?php endforeach ?>
      <?php endif ?>
    </ul>
  </div>
  <?php endforeach ?>
  <?php endif ?>

</div>
<script type="text/javascript" src="/assets/js/knockout-3.5.1.js"></script> -->

<script src="/assets/js/jquery-ui-1.12.1/external/jquery/jquery.js"></script>
<script src="/assets/js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
<script src="/assets/js/knockout-sortable.min.js"></script>
<script>
// let viewModelIndex = function() {
//   this.stateBoolIndex = ko.observable(false),
//     this.mutualConvRegAndEditPage = function() {
//       if (this.stateBoolIndex() === false) {
//         this.stateBoolIndex(true);
//       } else if (this.stateBoolIndex() === true) {
//         this.stateBoolIndex(false);
//       }
//     },
//     this.stateBool = null,
//     this.mutualConvRegAndEdit = null,
//     this.colorHerePath = function() {
//       return null
//     }
// }

var Task = function(name) {
  this.name = ko.observable(name);
}


var ViewModelSortable = function() {
  var self = this;
  self.tasks = ko.observableArray([
    new Task("Get dog food"),
    new Task("Mow lawn"),
    new Task("Fix car"),
    new Task("Fix fence"),
    new Task("Walk dog"),
    new Task("Read book")
  ]);


  self.selectedTask = new ko.observable();
  // self.selectedTask = ko.ComputedObservable();
  self.clearTask = function(data, event) {
    if (data === self.selectedTask()) {
      self.selectedTask(null);
    }

    if (data.name() === "") {
      self.tasks.remove(data);
    }
  };
  self.addTask = function() {
    var task = new Task("new");
    self.selectedTask(task);
    self.tasks.push(task);
  };

  self.isTaskSelected = function(task) {
    return task === self.selectedTask();
  };
};

//control visibility, give element focus, and select the contents (in order)
ko.bindingHandlers.visibleAndSelect = {
  update: function(element, valueAccessor) {
    ko.bindingHandlers.visible.update(element, valueAccessor);
    if (valueAccessor()) {
      setTimeout(function() {
        $(element).find("input").focus().select();
      }, 0); //new tasks are not in DOM yet
    }
  }
};

ko.applyBindings(new ViewModelSortable(), document.getElementById('pages-index'));
</script>