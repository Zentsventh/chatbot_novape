<?php
// Deployment is performed through authenticated infrastructure, never HTTP.
http_response_code(404);
exit;
