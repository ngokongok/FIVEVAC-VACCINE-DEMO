<?php
/**
 * Controller responsible for displaying available vaccines and initiating
 * the purchase flow. Guests can browse the catalog; clicking on
 * "Đặt mua" will either redirect them to the login page or, if
 * already authenticated as a customer, forward them to the order
 * creation form with the chosen vaccine pre-selected. The selected
 * vaccine ID is stored in the session temporarily to allow the
 * AuthController to redirect back after a successful login.
 */
class VaccinesController extends Controller {
    /**
     * Show all vaccines in a card grid. Each card displays the vaccine
     * name, description snippet, price and an image. Hovering over a
     * card reveals the "Đặt mua" button.
     */
    public function index() {
        // Fetch all vaccine records along with available quantity across active branches.
        $db = new Database();
        $db->query(
            "SELECT v.*, 
                    COALESCE(SUM(CASE 
                        WHEN cn.MaChiNhanh IS NOT NULL THEN (ct.SoLuongHienTai - ct.SoLuongDaSuDung) 
                        ELSE 0 END), 0) AS AvailableQuantity
             FROM vacxin v
             LEFT JOIN chitiettonkho ct ON v.MaVacXin = ct.MaVacXin
             LEFT JOIN chinhanh cn ON ct.MaChiNhanh = cn.MaChiNhanh AND cn.TrangThaiHD = 'Hoạt động'
             GROUP BY v.MaVacXin"
        );
        $vaccines = $db->resultSet();
        // Render the view with the list and their available quantities
        $this->view('vaccines/index', ['vaccines' => $vaccines]);
    }

    /**
     * Handle a user's intent to purchase a given vaccine. The vaccine
     * identifier is provided as a URL segment. If the user is not
     * logged in, the ID is stored in the session and the user is
     * redirected to the login page via require_role(). After login,
     * AuthController will detect the pending vaccine and send the user
     * straight to the order form. If the user is already authenticated
     * as a customer, they are redirected directly to the order form.
     *
     * @param string $id Vaccine code (MaVacXin)
     */
    public function order($id) {
        // Remember which vaccine the user wants to order. This must be
        // set before the require_role() call so it persists through the
        // redirect to the login page.
        $_SESSION['pending_vaccine'] = $id;
        // Only customers are allowed to place orders
        require_role(['customer']);
        // If we reach here, the user is authenticated as a customer.
        // Retrieve the pending vaccine and clear the session flag.
        $vx = $_SESSION['pending_vaccine'] ?? null;
        if ($vx) {
            unset($_SESSION['pending_vaccine']);
            // Use '&' separator instead of '?'. Otherwise `vx` would be
            // appended to the `url` query parameter rather than as its own
            // query parameter. See Helpers::base_url() for details.
            redirect('orders/create_onl&vx=' . urlencode($vx));
        }
        // Fallback: go to the order form without a specific vaccine
        redirect('orders/create_onl');
    }
}