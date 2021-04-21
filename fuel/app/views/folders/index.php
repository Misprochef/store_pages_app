<div id="student-manage" class="main-right flex flex-center">
  <div class="flex" style="flex-direction: column;">
    <p class="mb20" style="font-size: 25px; font-weight: bold;">フォルダーの一覧</p>
    <?php if ($folders): ?>
    <?php foreach ($folders as $folder): ?>
    <span class="mb10">
      <a href="/folders/folder_pages/<?php echo $folder['name']; ?>"><?php echo $folder['name']; ?></a>
    </span>
    <?php endforeach ?>
    <?php endif ?>
  </div>
</div>