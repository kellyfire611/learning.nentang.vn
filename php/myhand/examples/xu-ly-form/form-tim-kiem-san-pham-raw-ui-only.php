<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tìm kiếm Sản phẩm | Nền tảng - Kiến thức cơ bản về Lập trình Web</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="../../assets/vendor/bootstrap/css/bootstrap.min.css" type="text/css">
  <!-- Font awesome -->
  <link rel="stylesheet" href="../../assets/vendor/font-awesome/css/font-awesome.min.css" type="text/css">
  <!-- Custom css - Các file css do chúng ta tự viết -->
  <link rel="stylesheet" href="../../assets/examples/assets/css/custom-style-for-example.css" type="text/css">

</head>

<body>

  <div class="container">
    <!-- 
    1. Thuộc tính action="file-xu-ly.php" dùng để chỉ định địa chỉ file PHP - nơi sẽ nhận dữ liệu từ CLIENT gởi đến và xử lý theo các LOGIC nào đó...
    - Nêu muốn gởi đến chính mình thì để thuộc tính action là rỗng, tức là action=""
    2. Phương thức (method) dùng để gởi request có thể sử dụng: GET hoặc POST
      * Nếu sử dụng phương thức GET:
      - Dữ liệu trong FORM sẽ được truyền theo dạng tham số trên địa chỉ URL theo định dạng sau:
        http://domain/action.php?param1=value1&param2=value2...

      - Trong đó:
        + Các thông tin được gởi từ FORM đến SERVER (file action được chỉ định) sẽ được phân cách bởi dấu ?
        + Các tham số `param1`, `param2` là thuộc tính name của các thành phần Nhập liệu (inputs) trong FORM
                      `value1`, `value2` là những thông tin người dùng (End user) nhập liệu trong FORM
                      ...
        + Các thành phần Tham số sẽ được phân cách nhau bởi dấu &

      Ví dụ: http://localhost/hoc-php/form-tim-kiem-san-pham.php?username=dnpcuong&password=123456
    -->
    <form>
      <div class="row">
        <div class="col-md-12 text-center">
          <h1>Form Tìm kiếm Sản phẩm | NenTang.vn</h1>
        </div>
        <div class="col col-md-12">
          <div class="text-center">
            <button type="button" id="btnReset" class="btn btn-warning">Xóa bộ lọc</button>
            <button class="btn btn-primary btn-lg" id="btnTimKiem">Tìm kiếm <i class="fa fa-forward" aria-hidden="true"></i></button>
          </div>
        </div>
      </div>
      <hr />
      <div class="row">
        <!-- START: Form nhập liệu Tiêu chí Tìm kiếm -->
        <div class="col-md-4">
          <div class="form-tim-kiem-san-pham">
            <h5 class="text-center">Các tiêu chí Tìm kiếm Sản phẩm</h5>

            <div class="card">
              <!-- Tìm kiếm theo tên sản phẩm -->
              <article class="card-group-item">
                <header class="card-header">
                  <h6 class="title">Tên sản phẩm</h6>
                </header>
                <div class="filter-content">
                  <div class="card-body">
                    <!-- 
                      Thuộc tính name="" cần có trong các thành phần Nhập liệu (input, select, ...)
                      FORM sẽ đóng gói dữ liệu người dùng (End User) nhập liệu vào đúng tên được đặt trong thuộc tính name=""
                      Ví dụ: đặt tên là name="keyword_tensanpham"
                    -->
                    <input class="form-control" type="text" placeholder="Tìm kiếm" aria-label="Search" value="">
                  </div> <!-- card-body.// -->
                </div>
              </article> <!-- // Tìm kiếm theo Tên sản phẩm -->

              <!-- Tìm kiếm theo Loại sản phẩm -->
              <article class="card-group-item">
                <header class="card-header">
                  <h6 class="title">Loại sản phẩm </h6>
                </header>
                <div class="filter-content">
                  <div class="card-body">
                    <div class="custom-control custom-checkbox">
                      <span class="float-right badge badge-light round">3 sp</span>
                      <!-- 
                        Thuộc tính name="" cần có trong các thành phần Nhập liệu (input, select, ...)
                        - FORM sẽ đóng gói dữ liệu người dùng (End User) nhập liệu vào đúng tên được đặt trong thuộc tính name=""
                        - Nếu muốn truyền dữ liệu dạng mảng (array) thì sử dụng cú pháp name="ten_tham_so[]"
                        Ví dụ: đặt tên là name="keyword_loaisanpham[]"
                      -->
                      <input type="checkbox" class="custom-control-input" id="chk-loaisanpham-1">
                      <label class="custom-control-label" for="chk-loaisanpham-1">Máy tính bảng</label>
                    </div> <!-- form-check.// -->
                    <div class="custom-control custom-checkbox">
                      <span class="float-right badge badge-light round">1 sp</span>
                      <input type="checkbox" class="custom-control-input" id="chk-loaisanpham-2">
                      <label class="custom-control-label" for="chk-loaisanpham-2">Máy tính xách tay</label>
                    </div> <!-- form-check.// -->
                    <div class="custom-control custom-checkbox">
                      <span class="float-right badge badge-light round">4 sp</span>
                      <input type="checkbox" class="custom-control-input" id="chk-loaisanpham-3">
                      <label class="custom-control-label" for="chk-loaisanpham-3">Điện thoại</label>
                    </div> <!-- form-check.// -->
                    <div class="custom-control custom-checkbox">
                      <span class="float-right badge badge-light round">1 sp</span>
                      <input type="checkbox" class="custom-control-input" id="chk-loaisanpham-4">
                      <label class="custom-control-label" for="chk-loaisanpham-4">Linh phụ kiện</label>
                    </div> <!-- form-check.// -->
                  </div> <!-- card-body.// -->
                </div>
              </article> <!-- // Tìm kiếm theo Loại sản phẩm -->

              <!-- Tìm kiếm theo Nhà sản xuất -->
              <article class="card-group-item">
                <header class="card-header">
                  <h6 class="title">Nhà sản xuất </h6>
                </header>
                <div class="filter-content">
                  <div class="card-body">
                    <div class="custom-control custom-checkbox">
                      <span class="float-right badge badge-light round">3 sp</span>
                      <!-- 
                        Thuộc tính name="" cần có trong các thành phần Nhập liệu (input, select, ...)
                        - FORM sẽ đóng gói dữ liệu người dùng (End User) nhập liệu vào đúng tên được đặt trong thuộc tính name=""
                        - Nếu muốn truyền dữ liệu dạng mảng (array) thì sử dụng cú pháp name="ten_tham_so[]"
                        Ví dụ: đặt tên là name="keyword_nhasanxuat[]"
                      -->
                      <input type="checkbox" class="custom-control-input" id="chk-nhasanxuat-1">
                      <label class="custom-control-label" for="chk-nhasanxuat-1">Apple</label>
                    </div> <!-- form-check.// -->
                    <div class="custom-control custom-checkbox">
                      <span class="float-right badge badge-light round">3 sp</span>
                      <input type="checkbox" class="custom-control-input" id="chk-nhasanxuat-2">
                      <label class="custom-control-label" for="chk-nhasanxuat-2">Samsung</label>
                    </div> <!-- form-check.// -->
                    <div class="custom-control custom-checkbox">
                      <span class="float-right badge badge-light round">1 sp</span>
                      <input type="checkbox" class="custom-control-input" id="chk-nhasanxuat-3">
                      <label class="custom-control-label" for="chk-nhasanxuat-3">HTC</label>
                    </div> <!-- form-check.// -->
                    <div class="custom-control custom-checkbox">
                      <span class="float-right badge badge-light round">1 sp</span>
                      <input type="checkbox" class="custom-control-input" id="chk-nhasanxuat-4">
                      <label class="custom-control-label" for="chk-nhasanxuat-4">Nokia</label>
                    </div> <!-- form-check.// -->
                  </div> <!-- card-body.// -->
                </div>
              </article> <!-- // Tìm kiếm theo Nhà sản xuất -->

              <!-- Tìm kiếm theo Khuyến mãi -->
              <article class="card-group-item">
                <header class="card-header">
                  <h6 class="title">Khuyến mãi </h6>
                </header>
                <div class="filter-content">
                  <div class="card-body">
                    <div class="custom-control custom-radio">
                      <!-- 
                        Thuộc tính name="" cần có trong các thành phần Nhập liệu (input, select, ...)
                        - FORM sẽ đóng gói dữ liệu người dùng (End User) nhập liệu vào đúng tên được đặt trong thuộc tính name=""
                        - Nếu muốn truyền dữ liệu dạng mảng (array) thì sử dụng cú pháp name="ten_tham_so[]"
                        Ví dụ: đặt tên là name="keyword_khuyenmai"
                      -->
                      <input type="radio" class="custom-control-input" id="rd-khuyenmai-1">
                      <label class="custom-control-label" for="rd-khuyenmai-1">Khuyến mãi Trung Thu (giảm giá 15%)</label>
                    </div> <!-- form-radio.// -->
                    <div class="custom-control custom-radio">
                      <input type="radio" class="custom-control-input" id="rd-khuyenmai-2">
                      <label class="custom-control-label" for="rd-khuyenmai-2">Khuyến mãi Dịp Sinh nhật (được tặng 2 món quà trị giá 500k)</label>
                    </div> <!-- form-radio.// -->
                  </div> <!-- card-body.// -->
                </div>
              </article> <!-- // Tìm kiếm theo Nhà sản xuất -->

              <!-- Tìm kiếm theo khoảng giá tiền -->
              <article class="card-group-item">
                <header class="card-header">
                  <h6 class="title">Khoảng tiền </h6>
                </header>
                <div class="filter-content">
                  <div class="card-body">
                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label>Từ</label>
                        <!-- 
                          Thuộc tính name="" cần có trong các thành phần Nhập liệu (input, select, ...)
                          - FORM sẽ đóng gói dữ liệu người dùng (End User) nhập liệu vào đúng tên được đặt trong thuộc tính name=""
                          - Nếu muốn truyền dữ liệu dạng mảng (array) thì sử dụng cú pháp name="ten_tham_so[]"
                          Ví dụ: đặt tên là name="keyword_khuyenmai"
                        -->
                        <input type="range" class="custom-range" min="0" max="50000000" step="100000" id="sotientu" value="0" oninput="document.getElementById('sotientu-text').innerHTML = this.value;">
                        <span><span id="sotientu-text">0</span></span>
                      </div>
                      <div class="form-group col-md-6 text-right">
                        <label>Đến</label>
                        <input type="range" class="custom-range" min="0" max="50000000" step="100000" id="sotienden" value="50000000" oninput="document.getElementById('sotienden-text').innerHTML = this.value;">
                        <span><span id="sotienden-text">50.000.000</span></span>
                      </div>
                    </div>
                  </div> <!-- card-body.// -->
                </div>
              </article> <!-- // Tìm kiếm theo khoảng giá tiền -->

              <!-- Tìm kiếm theo màu sắc sản phẩm -->
              <article class="card-group-item">
                <header class="card-header">
                  <h6 class="title">Màu sắc (tùy chọn thêm)</h6>
                </header>
                <div class="filter-content">
                  <div class="card-body">
                    <label class="btn btn-danger">
                      <!-- 
                        Thuộc tính name="" cần có trong các thành phần Nhập liệu (input, select, ...)
                        - FORM sẽ đóng gói dữ liệu người dùng (End User) nhập liệu vào đúng tên được đặt trong thuộc tính name=""
                        - Nếu muốn truyền dữ liệu dạng mảng (array) thì sử dụng cú pháp name="ten_tham_so[]"
                        Ví dụ: đặt tên là name="keyword_mausac[]"
                      -->
                      <input class="" type="checkbox" id="keyword-mausac-1">
                      <span class="form-check-label">Đỏ</span>
                    </label>
                    <label class="btn btn-success">
                      <input class="" type="checkbox" id="keyword-mausac-2">
                      <span class="form-check-label">Xanh lá</span>
                    </label>
                    <label class="btn btn-primary">
                      <input class="" type="checkbox" id="keyword-mausac-3">
                      <span class="form-check-label">Xanh dương</span>
                    </label>
                  </div> <!-- card-body.// -->
                </div>
              </article> <!-- // Tìm kiếm theo màu sắc sản phẩm -->
            </div>
          </div>
        </div>
        <!-- END: Form nhập liệu Tiêu chí Tìm kiếm -->

        <!-- START: Kết quả tìm kiếm theo Tiêu chí Tìm kiếm -->
        <div class="col-md-8">
          <div class="ket-qua-tim-kiem-san-pham">
            <h5 class="text-center">Kết quả Tìm kiếm Sản phẩm</h5>

            <!-- START: CODE XỬ LÝ PHP -->
            <?php
              
            ?>
            <!-- END: CODE XỬ LÝ PHP -->

          </div>
        </div>
        <!-- END: Kết quả tìm kiếm theo Tiêu chí Tìm kiếm -->
      </div>
  </div>
  </form>

  <!-- Liên kết JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="../../assets/vendor/jquery/jquery.min.js"></script>
  <script src="../../assets/vendor/popperjs/popper.min.js"></script>
  <script src="../../assets/vendor/bootstrap/js/bootstrap.min.js"></script>

  <!-- Custom script - Các file js do mình tự viết -->
  <script src="../../assets/examples/js/custom-script-for-example.js"></script>
</body>

</html>