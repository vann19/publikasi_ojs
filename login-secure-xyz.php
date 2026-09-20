<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: /admin/index.php");
    exit;
}
$activePage = 'login';
include 'includes/header.php';
?>

<section class="max-w-md mx-auto px-4 py-20">
    <div class="bg-white p-8 rounded-xl border border-gray-100 shadow-sm">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Admin Login</h2>
        
        <div id="alert-box" class="hidden mb-4 p-4 text-sm rounded-lg" role="alert"></div>

        <form id="login-form">
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="email" name="email" placeholder="contoh@email.com" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500" required>
            </div>
            
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" id="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500" required>
            </div>
            
            <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                Masuk
            </button>
        </form>
    </div>
</section>

<script>
document.getElementById('login-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const alertBox = document.getElementById('alert-box');
    
    try {
        const response = await fetch('/api/login.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        
        if (data.status === 'success') {
            alertBox.className = 'mb-4 p-4 text-sm rounded-lg bg-green-50 text-green-800 block';
            alertBox.textContent = data.message;
            setTimeout(() => {
                window.location.href = '/admin/index.php';
            }, 1000);
        } else {
            alertBox.className = 'mb-4 p-4 text-sm rounded-lg bg-red-50 text-red-800 block';
            alertBox.textContent = data.message;
        }
    } catch (error) {
        alertBox.className = 'mb-4 p-4 text-sm rounded-lg bg-red-50 text-red-800 block';
        alertBox.textContent = 'Terjadi kesalahan sistem.';
    }
});
</script>

<?php include 'includes/footer.php'; ?>
