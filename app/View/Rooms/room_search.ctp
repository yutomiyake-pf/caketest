<h2 id="session-message"><?php echo $this->Session->flash(); ?></h2>

<!-- ルーム作成モーダル -->
<div id="mask" class="hidden"></div>
<div id="modal" class="hidden">
    <?php echo $this->Form->create('Room',['url' => 'roomCreate']); ?>
    <?php echo $this->Form->error('Room.room_name'); ?>
    <?php echo $this->Form->input('room_name',[
        'label' => false,
        'error' => false,
        'div' => false,
        'class' => 'room-name',
        'placeholder' => 'ルーム名'
    ]);
    ?>
    <label id="sub-label">
    <?php echo $this->Form->end([
        'class' => 'button',
        'label' => '作成',
        'title' => 'submit'
    ]);
    ?> 
    submit
    </label>
    <p id="close">閉じる</p>
</div>
<!-- ルーム作成モーダルここまで -->

<!-- ルーム検索 -->
<div id="search-message-wrapper">
    <h1>ルーム検索フォーム</h1>
</div>

<div id="search-form-wrapper">
    <form id="search-form" action="/Debug/rooms/roomSearch" method="get">
	    <input id="sbox" name="keyword" type="text" placeholder="キーワードを入力" />
	    <input id="sbtn" type="submit" value="検索" />
	</form>
</div>

<!-- ルーム検索結果 -->
<?php if (isset($rooms) && isset($keyWord)): ?>
<div id="room-wrapper">
<h1 id="keyword"><?php echo h($keyWord); ?>の検索結果</h1>
<table border="1" id="room-table">
    <tr>
    <th>ルームNO</th>
    <th>ルーム名</th>
    <th>管理者</th>
    <th>作成者</th>
    </tr>
<?php foreach ($rooms as $room): ?>
    <tr>
    <?php if (strpos($room['Room']['room_id'],$keyWord) !== false): ?>
    <td class="match"><a href="/Debug/Rooms/view/<?php echo h($room['Room']['room_id']); ?>"><?php echo h($room['Room']['room_id']); ?></a></td>
    <?php else: ?>
    <td class="no-match"><a href="/Debug/Rooms/view/<?php echo h($room['Room']['room_id']); ?>"><?php echo h($room['Room']['room_id']); ?></a></td>
    <?php endif; ?>

    <?php if (strpos($room['Room']['room_name'],$keyWord) !== false): ?>
    <td class="match"><a href="/Debug/Rooms/view/<?php echo h($room['Room']['room_id']); ?>"><?php echo h($room['Room']['room_name']); ?></a></td>
    <?php else: ?>
    <td class="no-match"><a href="/Debug/Rooms/view/<?php echo h($room['Room']['room_id']); ?>"><?php echo h($room['Room']['room_name']); ?></a></td>
    <?php endif; ?>

    <?php if (strpos($room['User']['nick_name'],$keyWord) !== false): ?>
    <td class="match"><a href="/Debug/Users/account/<?php echo h($room['User']['user_id']); ?>"><?php echo h($room['User']['nick_name']); ?></a></td>
    <?php else: ?>
    <td class="no-match"><a href="/Debug/Users/account/<?php echo h($room['User']['user_id']); ?>"><?php echo h($room['User']['nick_name']); ?></a></td>
    <?php endif; ?>

    <td class="no-match"><?php echo h($room['Room']['created_at']); ?></td>
    </tr>

<?php endforeach; ?>
</table>
</div>
<?php endif; ?>
<div id="pagi">
    <?php echo $this->Paginator->numbers(); ?>
</div>

<script type="text/javascript">

//ルーム作成formのモーダル
const open = document.getElementById('open');
const close = document.getElementById('close');
const modal = document.getElementById('modal');
const mask = document.getElementById('mask');

open.addEventListener('click',()=>{
		modal.classList.remove('hidden');
		mask.classList.remove('hidden');
	});
	
close.addEventListener('click',()=>{
    modal.classList.add('hidden');
    mask.classList.add('hidden');
});

mask.addEventListener('click',()=>{
    close.click();
});

</script>