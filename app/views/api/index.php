<div class="api-sandbox-container">
    <div class="page-header">
        <div>
            <h1 class="page-title">RESTful API Interactive Sandbox</h1>
            <p style="color: var(--text-secondary); margin-top: 0.5rem;">
                Môi trường thử nghiệm tương tác trực quan cho các API quản lý sản phẩm (Đồ chơi).
            </p>
        </div>
        
        <!-- Auth Status Card -->
        <div class="auth-status-badge">
            <div class="status-indicator admin-ok">
                <span class="indicator-dot"></span>
                <span>Tài khoản: <strong><?php echo htmlspecialchars($_SESSION['fullname']); ?></strong> (Vai trò: <?php echo htmlspecialchars($_SESSION['role']); ?>) - Trạng thái: Sẵn sàng thử nghiệm API</span>
            </div>
        </div>
    </div>

    <div class="sandbox-layout">
        <!-- Left Side: API Controller Forms -->
        <div class="sandbox-control-card">
            <h3 class="panel-title">Endpoints và Tham số</h3>
            
            <!-- Endpoint selector tabs -->
            <div class="endpoint-tabs">
                <button class="tab-btn active" data-endpoint="get-list" data-method="GET" data-path="/api/product">
                    <span class="badge badge-get">GET</span> /product
                </button>
                <button class="tab-btn" data-endpoint="get-detail" data-method="GET" data-path="/api/product/{id}">
                    <span class="badge badge-get">GET</span> /product/{id}
                </button>
                <button class="tab-btn" data-endpoint="post-create" data-method="POST" data-path="/api/product">
                    <span class="badge badge-post">POST</span> /product
                </button>
                <button class="tab-btn" data-endpoint="put-update" data-method="PUT" data-path="/api/product/{id}">
                    <span class="badge badge-put">PUT</span> /product/{id}
                </button>
                <button class="tab-btn" data-endpoint="delete-remove" data-method="DELETE" data-path="/api/product/{id}">
                    <span class="badge badge-delete">DEL</span> /product/{id}
                </button>
            </div>

            <!-- Dynamic Form Area -->
            <div class="params-form-wrapper">
                <form id="api-request-form">
                    
                    <!-- ID Input (Used for GET Detail, PUT, DELETE) -->
                    <div class="form-group input-group-id" style="display: none;">
                        <label class="form-label" for="param-id">ID sản phẩm (Product ID) <span class="required">*</span></label>
                        <input type="number" id="param-id" class="form-control" placeholder="Ví dụ: 1" value="1">
                        <small class="form-help-text">ID của sản phẩm trong cơ sở dữ liệu để xem chi tiết, chỉnh sửa hoặc xóa.</small>
                    </div>

                    <!-- Search Input (Used for GET List) -->
                    <div class="form-group input-group-search">
                        <label class="form-label" for="param-search">Tìm kiếm (search)</label>
                        <input type="text" id="param-search" class="form-control" placeholder="Từ khóa tìm kiếm (Ví dụ: Lego, Robot...)">
                        <small class="form-help-text">Không bắt buộc. Lọc danh sách sản phẩm khớp tên hoặc mô tả.</small>
                    </div>

                    <!-- Fields for POST and PUT -->
                    <div class="input-group-fields" style="display: none;">
                        <div class="form-group">
                            <label class="form-label" for="param-name">Tên sản phẩm (name) <span class="required">*</span></label>
                            <input type="text" id="param-name" class="form-control" placeholder="Ví dụ: Khối Rubik Nam Châm Cao Cấp">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="param-description">Mô tả sản phẩm (description)</label>
                            <textarea id="param-description" class="form-control" placeholder="Mô tả công dụng, tính năng, chất liệu..."></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-half">
                                <label class="form-label" for="param-price">Giá tiền (price) <span class="required">*</span></label>
                                <input type="number" step="1000" id="param-price" class="form-control" placeholder="Ví dụ: 150000">
                            </div>
                            <div class="form-group col-half">
                                <label class="form-label" for="param-stock">Số lượng kho (stock_quantity) <span class="required">*</span></label>
                                <input type="number" id="param-stock" class="form-control" placeholder="Ví dụ: 10" value="10">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="param-category">Danh mục (category_id) <span class="required">*</span></label>
                            <select id="param-category" class="form-control">
                                <option value="">-- Chọn danh mục --</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>">
                                        <?php echo htmlspecialchars($cat['name']); ?> (ID: <?php echo $cat['id']; ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="param-image">Tên tệp hình ảnh (image)</label>
                            <input type="text" id="param-image" class="form-control" placeholder="Ví dụ: rubik.jpg (hoặc để trống)">
                            <small class="form-help-text">Gợi ý ảnh mẫu có sẵn: Sao Thổ.jpg, Sao Hải Vương.jpg, Sao Thiên Vương.jpg</small>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block" style="width: 100%; justify-content: center; margin-top: 1.5rem;">
                        ⚡ Gửi yêu cầu API (Send Request)
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Side: API Console Output -->
        <div class="sandbox-console-card">
            <div class="console-header">
                <h3 class="panel-title">Response Console</h3>
                <span id="response-time" class="response-time-badge" style="display: none;">24ms</span>
            </div>
            
            <div class="console-body">
                <!-- Status Row -->
                <div class="console-status-row">
                    <span class="status-label">HTTP Status:</span>
                    <span id="http-status-badge" class="status-badge status-idle">IDLE</span>
                </div>
                
                <!-- Request Info Raw -->
                <div class="console-status-row">
                    <span class="status-label">Request URL:</span>
                    <span id="request-url-display" class="request-url-text">-</span>
                </div>

                <!-- JSON Response View -->
                <div class="json-output-wrapper">
                    <pre><code id="json-response-display">// Kết quả JSON sẽ được hiển thị tại đây sau khi bạn click "Gửi yêu cầu API"</code></pre>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling dành riêng cho API Sandbox */
    .api-sandbox-container {
        margin-top: 1rem;
    }
    
    .auth-status-badge {
        font-size: 0.9rem;
    }
    
    .status-indicator {
        padding: 0.5rem 1rem;
        border-radius: var(--radius);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        border: 1px solid;
    }
    
    .status-indicator.admin-ok {
        background-color: rgba(34, 197, 94, 0.1);
        color: #86efac;
        border-color: rgba(34, 197, 94, 0.3);
    }
    
    .status-indicator.guest-warn {
        background-color: rgba(234, 179, 8, 0.1);
        color: #fef08a;
        border-color: rgba(234, 179, 8, 0.3);
    }
    
    .indicator-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }
    
    .admin-ok .indicator-dot {
        background-color: #22c55e;
        box-shadow: 0 0 8px #22c55e;
    }
    
    .guest-warn .indicator-dot {
        background-color: #eab308;
        box-shadow: 0 0 8px #eab308;
    }
    
    .sandbox-layout {
        display: grid;
        grid-template-columns: 1.1fr 1.3fr;
        gap: 2rem;
        margin-top: 1.5rem;
        align-items: start;
        margin-bottom: 3rem;
    }
    
    @media (max-width: 992px) {
        .sandbox-layout {
            grid-template-columns: 1fr;
        }
    }
    
    .sandbox-control-card, .sandbox-console-card {
        background-color: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-lg);
        padding: 2rem;
        min-height: 520px;
        display: flex;
        flex-direction: column;
    }
    
    .panel-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 1.25rem;
        border-bottom: 1px solid var(--border);
        padding-bottom: 0.75rem;
        color: var(--text-primary);
    }
    
    .endpoint-tabs {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }
    
    .tab-btn {
        background-color: rgba(255, 255, 255, 0.03);
        border: 1px solid var(--border);
        color: var(--text-secondary);
        padding: 0.75rem 1rem;
        text-align: left;
        border-radius: var(--radius);
        cursor: pointer;
        font-family: 'Courier New', Courier, monospace;
        font-size: 0.85rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: var(--transition);
    }
    
    .tab-btn:hover {
        background-color: rgba(255, 255, 255, 0.07);
        border-color: var(--border-hover);
        color: var(--text-primary);
    }
    
    .tab-btn.active {
        background-color: rgba(255, 255, 255, 0.08);
        border-color: var(--text-primary);
        color: #ffffff;
        box-shadow: 0 0 12px rgba(255, 255, 255, 0.05);
    }
    
    .badge {
        padding: 0.2rem 0.5rem;
        border-radius: var(--radius-sm);
        font-size: 0.7rem;
        font-weight: 700;
        color: #ffffff;
        min-width: 48px;
        text-align: center;
    }
    
    .badge-get { background-color: #0ea5e9; }
    .badge-post { background-color: #22c55e; }
    .badge-put { background-color: #eab308; }
    .badge-delete { background-color: #ef4444; }
    
    .form-help-text {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-top: 0.25rem;
        display: block;
    }
    
    .required {
        color: #f43f5e;
        font-weight: bold;
    }
    
    .form-row {
        display: flex;
        gap: 1rem;
    }
    
    .col-half {
        flex: 1;
    }
    
    /* Console Styles */
    .console-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid var(--border);
        padding-bottom: 0.75rem;
        margin-bottom: 1.25rem;
    }
    
    .console-header .panel-title {
        border-bottom: none;
        padding-bottom: 0;
        margin-bottom: 0;
    }
    
    .response-time-badge {
        background-color: rgba(255, 255, 255, 0.08);
        border: 1px solid var(--border);
        padding: 0.25rem 0.6rem;
        border-radius: var(--radius-sm);
        font-size: 0.8rem;
        color: var(--text-secondary);
        font-family: monospace;
    }
    
    .console-body {
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    
    .console-status-row {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
        font-size: 0.85rem;
    }
    
    .status-label {
        font-weight: 600;
        color: var(--text-secondary);
    }
    
    .status-badge {
        font-size: 0.8rem;
        font-weight: 700;
        padding: 0.2rem 0.6rem;
        border-radius: var(--radius-sm);
        font-family: monospace;
    }
    
    .status-idle { background-color: rgba(255, 255, 255, 0.05); color: var(--text-secondary); }
    .status-success { background-color: rgba(34, 197, 94, 0.2); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.4); }
    .status-error { background-color: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.4); }
    
    .request-url-text {
        font-family: monospace;
        color: var(--text-secondary);
        font-size: 0.8rem;
        background-color: rgba(0, 0, 0, 0.2);
        padding: 0.2rem 0.5rem;
        border-radius: var(--radius-sm);
        word-break: break-all;
    }
    
    .json-output-wrapper {
        background-color: #09090b;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 1.5rem;
        overflow-y: auto;
        max-height: 480px;
        flex-grow: 1;
        font-family: 'Courier New', Courier, monospace;
        font-size: 0.85rem;
        color: #e4e4e7;
    }
    
    .json-output-wrapper pre {
        margin: 0;
        white-space: pre-wrap;
        word-break: break-all;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tabs = document.querySelectorAll(".tab-btn");
        const idInputGroup = document.querySelector(".input-group-id");
        const searchInputGroup = document.querySelector(".input-group-search");
        const bodyFieldsGroup = document.querySelector(".input-group-fields");
        const requestForm = document.getElementById("api-request-form");
        
        // Cấu hình endpoint hiện tại
        let activeEndpoint = "get-list";
        let activeMethod = "GET";
        let basePath = "<?php echo BASE_PATH; ?>";

        // Xử lý chuyển tab Endpoint
        tabs.forEach(tab => {
            tab.addEventListener("click", function(e) {
                e.preventDefault();
                
                // Active tab class
                tabs.forEach(t => t.classList.remove("active"));
                this.classList.add("active");
                
                activeEndpoint = this.getAttribute("data-endpoint");
                activeMethod = this.getAttribute("data-method");
                
                // Hiện/Ẩn các trường tương ứng với endpoint được chọn
                if (activeEndpoint === "get-list") {
                    idInputGroup.style.display = "none";
                    searchInputGroup.style.display = "block";
                    bodyFieldsGroup.style.display = "none";
                } 
                else if (activeEndpoint === "get-detail") {
                    idInputGroup.style.display = "block";
                    searchInputGroup.style.display = "none";
                    bodyFieldsGroup.style.display = "none";
                } 
                else if (activeEndpoint === "post-create") {
                    idInputGroup.style.display = "none";
                    searchInputGroup.style.display = "none";
                    bodyFieldsGroup.style.display = "block";
                    
                    // Reset field values to default suggestions for POST
                    document.getElementById("param-name").value = "Đồ chơi Lego Tàu Vũ Trụ NASA";
                    document.getElementById("param-description").value = "Bộ xếp hình tàu vũ trụ NASA mô phỏng tinh xảo với 1000 chi tiết lắp ráp.";
                    document.getElementById("param-price").value = "850000";
                    document.getElementById("param-stock").value = "12";
                    document.getElementById("param-category").value = "1"; // Mặc định Lắp ráp
                    document.getElementById("param-image").value = "Sao Thổ.jpg";
                } 
                else if (activeEndpoint === "put-update") {
                    idInputGroup.style.display = "block";
                    searchInputGroup.style.display = "none";
                    bodyFieldsGroup.style.display = "block";
                    
                    // Trộn giá trị thử nghiệm PUT
                    document.getElementById("param-name").value = "Đồ chơi Lego Tàu Vũ Trụ NASA (Cập nhật)";
                    document.getElementById("param-description").value = "Mô tả đã được sửa đổi qua API PUT.";
                    document.getElementById("param-price").value = "890000";
                    document.getElementById("param-stock").value = "8";
                    document.getElementById("param-category").value = "1";
                    document.getElementById("param-image").value = "Sao Thiên Vương.jpg";
                } 
                else if (activeEndpoint === "delete-remove") {
                    idInputGroup.style.display = "block";
                    searchInputGroup.style.display = "none";
                    bodyFieldsGroup.style.display = "none";
                }
            });
        });

        // Xử lý submit Request
        requestForm.addEventListener("submit", async function(e) {
            e.preventDefault();
            
            const consoleDisplay = document.getElementById("json-response-display");
            const statusBadge = document.getElementById("http-status-badge");
            const requestUrlDisplay = document.getElementById("request-url-display");
            const responseTimeBadge = document.getElementById("response-time");
            
            consoleDisplay.textContent = "// Đang gửi yêu cầu, vui lòng đợi...";
            statusBadge.className = "status-badge status-idle";
            statusBadge.textContent = "WAITING";
            responseTimeBadge.style.display = "none";

            // 1. Tạo URL request
            let url = window.location.origin + basePath + "/api/product";
            const idVal = document.getElementById("param-id").value;
            const searchVal = document.getElementById("param-search").value;

            if (activeEndpoint === "get-list" && searchVal.trim() !== "") {
                url += "?search=" + encodeURIComponent(searchVal.trim());
            } else if (activeEndpoint === "get-detail" || activeEndpoint === "put-update" || activeEndpoint === "delete-remove") {
                url += "/" + parseInt(idVal);
            }
            
            requestUrlDisplay.textContent = activeMethod + " " + url;

            // 2. Tạo Request Options
            const options = {
                method: activeMethod,
                headers: {
                    "Accept": "application/json"
                }
            };

            // Tạo request body cho POST/PUT
            if (activeMethod === "POST" || activeMethod === "PUT") {
                options.headers["Content-Type"] = "application/json; charset=utf-8";
                const bodyData = {
                    name: document.getElementById("param-name").value,
                    description: document.getElementById("param-description").value,
                    price: parseFloat(document.getElementById("param-price").value),
                    stock_quantity: parseInt(document.getElementById("param-stock").value),
                    category_id: parseInt(document.getElementById("param-category").value),
                    image: document.getElementById("param-image").value
                };
                options.body = JSON.stringify(bodyData);
            }

            // 3. Thực thi fetch và đo thời gian phản hồi
            const startTime = performance.now();
            try {
                const response = await fetch(url, options);
                const endTime = performance.now();
                const duration = Math.round(endTime - startTime);
                
                // Hiển thị thời gian phản hồi
                responseTimeBadge.textContent = duration + "ms";
                responseTimeBadge.style.display = "inline-block";
                
                // Cập nhật HTTP Status Badge
                statusBadge.textContent = response.status + " " + response.statusText;
                if (response.ok || (response.status >= 200 && response.status < 300)) {
                    statusBadge.className = "status-badge status-success";
                } else {
                    statusBadge.className = "status-badge status-error";
                }

                // Đọc response body
                const responseText = await response.text();
                try {
                    // Cố gắng parse JSON để định dạng đẹp
                    const json = JSON.parse(responseText);
                    consoleDisplay.textContent = JSON.stringify(json, null, 4);
                } catch (parseErr) {
                    // Nếu không phải JSON, hiển thị text thô
                    consoleDisplay.textContent = responseText || `// Response rỗng (HTTP Status ${response.status})`;
                }
            } catch (fetchErr) {
                const endTime = performance.now();
                const duration = Math.round(endTime - startTime);
                
                responseTimeBadge.textContent = duration + "ms";
                responseTimeBadge.style.display = "inline-block";
                
                statusBadge.textContent = "FAILED";
                statusBadge.className = "status-badge status-error";
                consoleDisplay.textContent = `// Lỗi kết nối mạng: \n${fetchErr.message}`;
            }
        });
    });
</script>
