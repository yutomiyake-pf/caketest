<!-- style指定 -->
<div>
    <h1 style="color: red;">D<span style="color: black;">ebug</span></h1>
</div>

<!-- ログイン -->
<div id="login-form-wrapper">
    <h1>LOGIN</h1>
    <?php echo $this->Session->flash(); ?>
    <?php echo $this->Form->create('User'); ?>
    <div class="form-item">
        <?php echo $this->Form->input('email',[
            'label' => false,
            'error' => false,
            'placeholder' => 'Email',
            'type' => 'email'
        ]);
        ?>
    </div>
    <div class="form-item">
        <?php echo $this->Form->input('password',[
            'label' => false,
            'error' => false,
            'placeholder' => 'Password'
        ]);
        ?>
    </div>
    <div class="button-panel">
        <?php echo $this->Form->end([
            'class' => 'button',
            'label' => 'Login',
            'title' => 'Login'
        ]); ?>
        </div>
    </form>
    <div class="form-footer">
        <?php echo $this->Html->link('Create an account',['action' => 'user_register']); ?>
    </div>
</div>



<!-- <script>

// document.getElementById("register-form-wrapper").style.display = "none";
// document.getElemntById("login-form-wrapper").style.display = "block";

function change() {
    const register = document.getElementById("register-form-wrapper");
    const login = document.getElementById("login-form-wrapper");

    if (login.style.display == "block" && register.style.display == "none") {
        register.style.display = "block";
        login.style.display = "none";
    } else if (login.style.display == "none" && register.style.display == "block") {
        register.style.display = "none";
        login.style.display = "block";
    } else {
        register.style.display = "none";
        login.style.display = "block";
    }
}
 

</script> -->