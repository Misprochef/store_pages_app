<div class="sidebar-menu">
  <aside>
    <h1>サイドバー</h1>
    <?php foreach ($folders as $folder): ?>
    <section>
      <a href="/folders/folder_pages/<?php echo $folder['name']; ?>"><?php echo $folder['name']; ?></a>
      <a href="/folders/edit_folder/<?php echo $folder['name']; ?>">Edit</a>
      <a href="/folders/delete_folder/<?php echo $folder['name']; ?>">Delete</a>
    </section>
    <?php endforeach ?>
    <a href="/folders/add_folder/<?php echo $folder['name']; ?>">フォルダーを追加</a>
    <br>
    <br>
    <a href="/folders/index">フォルダーの一覧</a>
  </aside>
</div>