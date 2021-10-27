<!DOCTYPE HTML>
<html lang="ja-JP">
<head>
    <?php echo $this->Html->charset(); ?>
    <title>
        <?php echo 'Debug'; ?>
    </title>
    <?php
        echo $this->Html->css('users_login');
    ?>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1" />
    <?php echo $this->Html->script('jquery.min'); ?>
    <?php echo $this->Html->script('sessionMessage'); ?>
</head>
<body>
    
<?= $this->fetch('content') ?>
</body>
</html>