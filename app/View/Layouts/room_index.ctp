<!DOCTYPE HTML>
<html lang="ja-JP">
<head>
    <?php echo $this->Html->charset(); ?>
    <title>
        <?php echo 'Debug'; ?>
    </title>
    <?php
        echo $this->Html->css('room_index');
        echo $this->Html->css('menu_bar');
        echo $this->Html->css('session_message');
    ?>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1" />
    <?php echo $this->Html->script('jquery.min'); ?>
    <?php echo $this->Html->script('sessionMessage'); ?>
</head>
<body>

<!-- ヘッダー -->
<header>
    <h1>D<span>ebug</span></h1>
    <!-- <a href="">初めての方</a> -->
    <p id="open">投稿</p>
</header>

<div id="menu-bar">
    <ul>
        <li><a href="">マイページ</a></li>
        <li>
        <?php echo $this->Html->link('ROOMS',[
            'controller' => 'Rooms',
            'action' => 'roomSearch'
        ]); 
        ?>
        </li>
    </ul>
</div>


<?= $this->fetch('content') ?>

<?php echo $this->Html->script('room'); ?>
<?php echo $this->Html->script('room_index'); ?>
</body>