<?php include(dirname(__FILE__) . '/head.php'); ?>

<?php
  use Fuel\Core\Asset;

  echo Asset::css('style.css')
?>

<body id="body" style="background-color: #F5F5F5;">
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

<script src="/assets/js/jquery-ui-1.12.1/external/jquery/jquery.js"></script>
<script src="/assets/js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript" src="/assets/js/knockout-3.5.1.js"></script>
<script src="/assets/js/knockout-sortable.min.js"></script>
<script>
let viewModelIndex = {
  stateBoolIndex: ko.observable(false),
  mutualConvRegAndEditPage: function() {
    if (this.stateBoolIndex() === false) {
      this.stateBoolIndex(true);
    } else if (this.stateBoolIndex() === true) {
      this.stateBoolIndex(false);
    }
  },
  stateBool: null,
  mutualConvRegAndEdit: null,
  colorHerePath: function() {
    return null
  },
  Task: function(name) {
    this.name = ko.observable(name);
  },


  ViewModelSortable: function() {
    var self = this;
    self.tasks = ko.observableArray([
      new Task("Get dog food"),
      new Task("Mow lawn"),
      new Task("Fix car"),
      new Task("Fix fence"),
      new Task("Walk dog"),
      new Task("Read book")
    ]);


    self.selectedTask = ko.observable();
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
  },

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
};
// ko.applyBindings(viewModelIndex, document.getElementById('body'));

// var Task = function(name) {
//   this.name = ko.observable(name);
// }


// var ViewModelSortable = function() {
//   var self = this;
//   self.tasks = ko.observableArray([
//     new Task("Get dog food"),
//     new Task("Mow lawn"),
//     new Task("Fix car"),
//     new Task("Fix fence"),
//     new Task("Walk dog"),
//     new Task("Read book")
//   ]);


//   self.selectedTask = ko.observable();
//   self.clearTask = function(data, event) {
//     if (data === self.selectedTask()) {
//       self.selectedTask(null);
//     }

//     if (data.name() === "") {
//       self.tasks.remove(data);
//     }
//   };
//   self.addTask = function() {
//     var task = new Task("new");
//     self.selectedTask(task);
//     self.tasks.push(task);
//   };

//   self.isTaskSelected = function(task) {
//     return task === self.selectedTask();
//   };
// };

// //control visibility, give element focus, and select the contents (in order)
// ko.bindingHandlers.visibleAndSelect = {
//   update: function(element, valueAccessor) {
//     ko.bindingHandlers.visible.update(element, valueAccessor);
//     if (valueAccessor()) {
//       setTimeout(function() {
//         $(element).find("input").focus().select();
//       }, 0); //new tasks are not in DOM yet
//     }
//   }
// };

ko.applyBindings(viewModelIndex, document.getElementById('body'));
</script>

</html>