<?php $__env->startSection('title', 'Login'); ?>

<?php $__env->startSection('content'); ?>
<div class="form-header">
    <span class="eyebrow">Welcome back</span>
    <h2>Sign in to your account</h2>
    <p>Masuk ke Asset Management System.</p>
</div>

<?php if(session('error')): ?>
<div class="alert-error"><i class="bi bi-exclamation-circle-fill"></i> <?php echo e(session('error')); ?></div>
<?php endif; ?>
<?php if($errors->any()): ?>
<div class="alert-error"><i class="bi bi-exclamation-circle-fill"></i> <?php echo e($errors->first()); ?></div>
<?php endif; ?>

<form action="<?php echo e(route('login')); ?>" method="POST" id="loginForm">
    <?php echo csrf_field(); ?>

    
    <div class="field-group <?php echo e($errors->has('email') ? 'is-invalid' : ''); ?>">
        <label for="email">Email Address</label>
        <div class="input-wrap">
            <i class="bi bi-envelope input-icon"></i>
            <input type="email" id="email" name="email"
                   value="<?php echo e(old('email')); ?>"
                   placeholder="you@company.com"
                   autocomplete="email" autofocus required>
        </div>
        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-msg"><i class="bi bi-x-circle-fill"></i> <?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    
    <div class="field-group <?php echo e($errors->has('password') ? 'is-invalid' : ''); ?>">
        <label for="password">Password</label>
        <div class="input-wrap">
            <i class="bi bi-lock input-icon"></i>
            <input type="password" id="passwordInput"
                   name="password"
                   placeholder="••••••••"
                   autocomplete="current-password" required>
            <button type="button" class="toggle-pw" id="toggleBtn"
                    onclick="togglePassword()" title="Tampilkan/sembunyikan password">
                <i class="bi bi-eye" id="eyeIcon"></i>
            </button>
        </div>
        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-msg"><i class="bi bi-x-circle-fill"></i> <?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    
    <div class="options-row">
        <label class="check-label">
            <input type="checkbox" name="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
            Ingat saya
        </label>
        <a href="<?php echo e(route('password.request')); ?>" class="forgot-link">Lupa password?</a>
    </div>

    <button type="submit" class="btn-login" id="submitBtn">
        <i class="bi bi-box-arrow-in-right"></i> Sign In
    </button>
</form>

<div class="footer-note">
    &copy; <?php echo e(date('Y')); ?> AssetMS &mdash; Asset Management System
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function togglePassword() {
    const input = document.getElementById('passwordInput');
    const icon  = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}

document.getElementById('loginForm').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.innerHTML = '<span class="spinner"></span> Signing in...';
    btn.disabled = true;
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /www/wwwroot/asset.adb.web.id/resources/views/auth/login.blade.php ENDPATH**/ ?>