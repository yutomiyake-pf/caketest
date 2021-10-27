<h2 id="session-message"><?php echo $this->Session->flash(); ?></h2>

<div id="top-wrapper">
    <h1>管理者: <?php echo h($roomInfo['User']['nick_name']); ?>　ルーム名：<?php echo h($roomInfo['Room']['room_name']); ?></h1>
    <h3 id="info-open">このルームの情報</h3>
</div>

<!-- 写真投稿モーダル -->
<div id="mask" class="hidden"></div>
<div id="modal" class="hidden">
    <?php echo $this->Form->create('Roomimage',['url' => 'postViewImage/'.h($roomId),'type' => 'file', 'enctype' => 'multipart/form-data',]); ?>
    <?php echo $this->Form->error('Roomimage.image'); ?>
    <label id="image-label">
    <?php echo $this->Form->input('image',[
            'label' => false,
            'error' => false,
            'div' => false,
            'class' => 'image',
            'id' => 'image-file',
            'type' => 'file', 'multiple',
            'placeholder' => 'Image (jpeg,jpg,gifのみ)'
    ]);
    ?>
    このルームに写真を投稿する(jpeg jpg gifのみ)
    </label>
    <p id="output"></p>
    <label id="sub-label">
    <?php echo $this->Form->end([
            'class' => 'button',
            'label' => '投稿',
            'title' => 'submit'
    ]);
    ?> 
    投稿する
    </label>
    <span id="close">閉じる</span>
</div>
<!-- 写真投稿モーダルここまで -->

<!-- ルーム詳細モーダル -->
<div id="info-mask" class="info-hidden"></div>
<div id="info-modal" class="info-hidden">
    <table border="1" id="info-table">
        <tr><th>ルーム名</th><td><?php echo h($roomInfo['Room']['room_name']); ?></td></tr>
        <tr><th>ルームNO</th><td><?php echo h($roomId); ?></td></tr>
        <tr><th>作成者</th><td><?php echo h($roomInfo['User']['nick_name']); ?></td></tr>
        <tr><th>作成日時</th><td><?php echo h($roomInfo['Room']['created_at']); ?></td></tr>
        <tr><th>投稿数</th><td id="image-cnt"><?php echo h($roomInfo['Room']['image_cnt']); ?></td></tr>
    </table>

    <span id="info-close" class="info-btn">閉じる</span>
    <!-- <span id="info-reload" class="info-btn">更新</span> -->
</div>
<!-- ルーム詳細モーダルここまで -->


<?php if (isset($roomImages)): ?>
<div id="room-image-wrapper">
    <?php foreach ($roomImages as $image): ?>
        <?php if ($image['User']['user_id'] !== $userId): ?>
            <a href="/Debug/Users/account/<?php echo $image['Roomimage']['user_id']; ?>">
                <?php echo $this->Html->image("/img/RoomsImage/" . h($image['Roomimage']['room_image']),[
                    'width' => 150,
                    'height' => 150,
                    'alt' => '画像',
                    'class' => 'other-image'
                ]); ?>
            </a>
        <?php endif; ?>
        <!-- 自分の投稿 -->
        <?php if ($image['User']['user_id'] == $userId): ?>
            <!-- クリックで削除(ajax) -->
            <?php echo $this->Html->link($this->Html->image('/img/RoomsImage/' . h($image['Roomimage']['room_image']),[
                'width' => 148,
                'height' => 148,
                'alt' => '画像',
                'class' => 'my-image',
                'id' => 'my-image-' . h($image['Roomimage']['room_image_id'])
            ]),'#',[
                'escape' => false,
                'class' => 'delete',
                'id' => 'link-' . h($image['Roomimage']['room_image_id']),
                'data-post-id' => h($image['Roomimage']['room_image_id']),
                'data-created' => h($image['Roomimage']['created_at'])
            ]);
            ?>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
<?php endif; ?>
<div id="pagi">
    <?php echo $this->Paginator->numbers(); ?>
</div>