<?php
// app/controllers/StaffController.php
class StaffController extends Controller {
    public function dashboard(){
        require_role(['staff']);
        // Determine staff role memberships for CSKH and Doctor. These flags will
        // control which dashboard cards are displayed. Default to false.
        $isCSKH  = false;
        $isDoctor = false;
        try {
            $db = new Database();
            // Check customer service staff membership (nhanviencskh)
            $db->query("SELECT 1 FROM nhanviencskh WHERE MaNV = :id LIMIT 1");
            $db->bind(':id', current_user_id());
            $isCSKH = (bool)$db->single();
            // Check doctor membership (bacsi)
            $db->query("SELECT 1 FROM bacsi WHERE MaBS = :id LIMIT 1");
            $db->bind(':id', current_user_id());
            $isDoctor = (bool)$db->single();
        } catch (Exception $e) {
            $isCSKH  = false;
            $isDoctor = false;
        }
        // Hide the standard navbar on staff pages; the sidebar will be used instead
        $this->view('staff/dashboard', [
            'title'       => 'Bảng điều khiển nhân viên',
            'hide_navbar' => true,
            'isCSKH'      => $isCSKH,
            'isDoctor'    => $isDoctor
        ]);
    }
}