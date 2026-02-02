<?php
class AdminController extends Controller {
    public function dashboard(){
        // Only administrators can view the admin dashboard. Staff have
        // their own dashboard with a separate sidebar. (UC‑11)
        require_role(['admin']);
        $db = new Database();

        // Compute annual revenue across all branches. Combine online
        // (donhangonl) and POS (donhangpos) orders, summing by year
        // (YEAR(NgayTao)). The query groups by year and orders
        // descending. (UC‑11.1)
        $db->query("SELECT YEAR(NgayTao) AS nam, SUM(ThanhTien) AS tong
                    FROM (
                        SELECT NgayTao, ThanhTien FROM donhangonl
                        UNION ALL
                        SELECT NgayTao, ThanhTien FROM donhangpos
                    ) t
                    GROUP BY YEAR(NgayTao)
                    ORDER BY nam DESC");
        $annualRevenue = $db->resultSet();

        // Compute quarterly revenue by branch for the current year. We
        // group by MaChiNhanh and QUARTER(NgayTao) and sum the
        // revenue. Only rows from the current year (YEAR(CURDATE()))
        // are included to produce a succinct report. (UC‑11.2)
        $db->query("SELECT MaChiNhanh, QUARTER(NgayTao) AS quy, SUM(ThanhTien) AS tong
                    FROM (
                        SELECT MaChiNhanh, NgayTao, ThanhTien FROM donhangonl
                        UNION ALL
                        SELECT MaChiNhanh, NgayTao, ThanhTien FROM donhangpos
                    ) t
                    WHERE YEAR(NgayTao) = YEAR(CURDATE())
                    GROUP BY MaChiNhanh, quy
                    ORDER BY MaChiNhanh ASC, quy ASC");
        $quarterRevenue = $db->resultSet();

        // Fetch today's appointments. Join with donhangonl to
        // retrieve branch and vaccine codes from the associated
        // order. Use MaLichHen for the appointment ID. (UC‑11.3)
        $db->query("SELECT lh.MaLichHen AS id,
                           lh.NgayGio    AS giotiem,
                           d.MaChiNhanh  AS machinhanh,
                           d.MaVacXin    AS mavacxin,
                           lh.TrangThai  AS trangthai
                    FROM lichhentiem lh
                    JOIN donhangonl d ON lh.MaDHonl = d.MaDHonl
                    WHERE DATE(lh.NgayGio) = CURDATE()
                    ORDER BY lh.NgayGio ASC");
        $apm = $db->resultSet();

        // Compute available stock per branch. We subtract used
        // quantity from current stock to get the number of doses left
        // available. (UC‑11.4)
        $db->query("SELECT MaChiNhanh, SUM(SoLuongHienTai - SoLuongDaSuDung) AS Khadung
                    FROM chitiettonkho
                    GROUP BY MaChiNhanh");
        $stock = $db->resultSet();

        // Render the admin dashboard with a custom title and hide
        // the top navigation bar. The sidebar will be shown instead.
        $this->view('admin/dashboard', [
            'annualRevenue' => $annualRevenue,
            'quarterRevenue' => $quarterRevenue,
            'apm'           => $apm,
            'stock'         => $stock,
            'hide_navbar'   => true,
            'title'         => 'Dashboard Quản trị'
        ]);
    }
}