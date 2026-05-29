    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; <?php echo date('Y'); ?> CosmicStore. Bản quyền thuộc học phần COS340 - Thực hành phát triển phần mềm mã nguồn mở.</p>
    </footer>

    <!-- Custom Cosmic Confirm Modal -->
    <div id="cosmic-confirm-modal" class="cosmic-modal">
        <div class="cosmic-modal-content">
            <div class="cosmic-modal-icon">🪐</div>
            <h3 class="cosmic-modal-title">Xác Nhận Hành Động</h3>
            <p class="cosmic-modal-message" id="cosmic-confirm-message">Bạn có chắc chắn muốn thực hiện hành động này không?</p>
            <div class="cosmic-modal-buttons">
                <button id="cosmic-confirm-cancel" class="btn btn-secondary">Hủy bỏ</button>
                <button id="cosmic-confirm-ok" class="btn btn-danger">Xác nhận</button>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const modal = document.getElementById("cosmic-confirm-modal");
        const confirmMessage = document.getElementById("cosmic-confirm-message");
        const btnCancel = document.getElementById("cosmic-confirm-cancel");
        const btnOk = document.getElementById("cosmic-confirm-ok");
        
        let activeLink = null;

        // Intercept clicks on links or elements with onclick confirmation popups
        document.body.addEventListener("click", function(e) {
            let target = e.target.closest("a, button");
            if (!target) return;
            
            const onclickAttr = target.getAttribute("onclick");
            if (onclickAttr && onclickAttr.includes("confirm(")) {
                // Prevent the browser confirm prompt from opening
                e.preventDefault();
                e.stopPropagation();
                
                // Extract message inside confirm(...)
                let message = "Bạn có chắc chắn muốn thực hiện hành động này?";
                const match = onclickAttr.match(/confirm\(['"](.*?)['"]\)/);
                if (match && match[1]) {
                    message = match[1];
                }
                
                // Show custom cosmic modal
                confirmMessage.innerHTML = message;
                modal.classList.add("show");
                
                activeLink = target;
            }
        });

        // Cancel button click
        btnCancel.addEventListener("click", function() {
            modal.classList.remove("show");
            activeLink = null;
        });

        // OK/Confirm button click
        btnOk.addEventListener("click", function() {
            modal.classList.remove("show");
            if (activeLink) {
                // Temporarily remove onclick so it doesn't loop, and simulate click
                const originalOnclick = activeLink.getAttribute("onclick");
                activeLink.removeAttribute("onclick");
                activeLink.click();
                
                // Restore onclick in case navigation is delayed or aborted
                setTimeout(() => {
                    activeLink.setAttribute("onclick", originalOnclick);
                }, 200);
            }
        });
    });
    </script>
</body>
</html>
