<div id="sidebar">
  <aside>
    <h1>サイドバー</h1>
    <button data-bind="click: mutualConvRegAndEdit">編集</button>
    <?php foreach ($folders as $folder): ?>
    <section>
      <a href="/folders/folder_pages/<?php echo $folder['name']; ?>"><?php echo $folder['name']; ?></a>
      <span data-bind="visible: stateBool">
        <a href="/folders/edit_folder/<?php echo $folder['name']; ?>">Edit</a>
        <a href="/folders/delete_folder/<?php echo $folder['name']; ?>">Delete</a>
      </span>
    </section>
    <?php endforeach ?>
    <a href="/folders/add_folder/<?php echo $folder['name']; ?>">フォルダーを追加</a>
    <br>
    <br>
    <a href="/folders/index">フォルダーの一覧</a>
  </aside>
</div>

<script>
let viewModel = {
  state: ko.observable('Regular'),
  stateBool: ko.observable(false),
  mutualConvRegAndEdit: function() {
    if (this.stateBool() === false) {
      this.state('Edit');
      this.stateBool(true);
    } else if (this.stateBool() === true) {
      this.state('Regular');
      this.stateBool(false);
    }
  },
  stateBoolIndex: null,
  mutualConvRegAndEditPage: null
};
ko.applyBindings(viewModel);
</script>