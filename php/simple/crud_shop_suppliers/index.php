
    <!-- Main content -->
    <div class="container">
        <h1>Danh sách Nhà cung cấp</h1>

        ...
        <table class="table table-borderd">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Mã nhà Cung cấp</th>
                    <th>Tên nhà Cung cấp</th>
                    <th>Ghi chú</th>
                    <th>Ảnh đại diện</th>
                    <th>Ngày tạo mới</th>
                    <th>Ngày cập nhật</th>
                    <th>###</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row) : ?>
                    <tr>
                        <td><?php echo $row['rowNum']; ?></td>
                        <td><?php echo $row['supplier_code']; ?></td>
                        <td><?php echo $row['supplier_name']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td><?php echo $row['image']; ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td><?php echo $row['updated_at']; ?></td>
                        <td>
                            <!-- Button Sửa -->
                            <a href="edit.php?id=<?php echo $row['id']; ?>" id="btnUpdate" class="btn btn-primary">
                                <i class="fas fa-edit"></i>
                            </a>

                            <!-- Button Xóa -->
                            <a href="delete.php?id=<?php echo $row['id']; ?>" id="btnDelete" class="btn btn-danger">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
