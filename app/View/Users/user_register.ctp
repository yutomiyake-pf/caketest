<!-- style指定 -->
<div>
    <h1 style="color: red;">D<span style="color: black;">ebug</span></h1>
</div>

<!-- 会員登録 -->
<div id="register-form-wrapper">
    <h1>REGISTER</h1>
    <?php echo $this->Form->create('User',['url' => 'user_register','type' => 'file', 'enctype' => 'multipart/form-data','onsubmit'=>'return confirm("登録を完了します。よろしいですか？")']); ?>
        <div class="form-item">
            <?php echo $this->Form->error('User.real_name'); ?>
            <?php echo $this->Form->input('real_name',[
                'label' => false,
                'error' => false,
                'placeholder' => 'Real Name 30文字以内',
            ]);
            ?>
        </div>
        <div class="form-item">
            <?php echo $this->Form->error('User.nick_name'); ?>
            <?php echo $this->Form->input('nick_name',[
                'label' => false,
                'error' => false,
                'placeholder' => 'Nick Name 20文字以内'
            ]);
            ?>
        </div>
        <div class="form-item">
            <?php echo $this->Form->error('User.email'); ?>
            <?php echo $this->Form->input('email',[
                'label' => false,
                'error' => false,
                'type' => 'email',
                'placeholder' => 'Email 40文字以内'
            ]);
            ?>
        </div>
        <div class="form-item">
            <?php echo $this->Form->error('User.password'); ?>
            <?php echo $this->Form->input('password',[
                'label' => false,
                'error' => false,
                'placeholder' => 'Password 半角英数字4~20文字以内'
            ]);
            ?>
        </div>
        <div class="form-item">
            <?php echo $this->Form->error('User.image'); ?>
            <span>イメージ画像（jpeg,jpg,gifのみ使用可）</span>
            <?php echo $this->Form->input('image',[
                'label' => false,
                'error' => false,
                'type' => 'file', 'multiple',
                'placeholder' => 'Image (jpeg,jpg,gifのみ)'
            ]);
            ?>
        </div>
        <div class="button-panel">
        <?php echo $this->Form->end([
                'class' => 'button',
                'label' => 'Register',
                'title' => 'Register'
            ]); ?>
        </div>
    </form>
    <div class="form-footer">
        <?php echo $this->Html->link('Login',['action' => 'login']); ?>
    </div>    
</div>