<div id="formLoader" style="
    display: none;
    position: fixed;
    z-index: 9999;
    top: 0;
    left: 0;
    height: 100vh;
    width: 100vw;
    background: rgba(255, 255, 255, 0.5);
    justify-content: center;
    align-items: center;
">Please Wait
    <div class="spinner" style="
        border: 8px solid #f3f3f3;
        border-top: 8px solid #3498db;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        animation: spin 1s linear infinite;
    "></div>
</div>

<style>
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<!-- JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('myForm');
        const loader = document.getElementById('formLoader');

        form.addEventListener('submit', function () {
            loader.style.display = 'flex'; // Show only on submit
        });
    });
</script>
