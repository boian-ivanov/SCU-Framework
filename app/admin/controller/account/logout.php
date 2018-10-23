<?php

class ControllerAccountLogout extends Controller {

    public function index() {
        if(session_id() !== '') {
            session_destroy();
        }
        header("Location: /" . ADMIN_LINK);
    }
}