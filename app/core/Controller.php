<?php
class Controller {
    /**
     * Load a model.
     *
     * @param string $model The name of the model to load.
     * @return object An instance of the model.
     */
    protected function model($model) {
        // Require the model file
        require_once __DIR__ . '/../models/' . $model . '.php';
        // Instantiate and return the model
        return new $model();
    }

    /**
     * Load a view.
     *
     * @param string $view The name of the view to load.
     * @param array $data An associative array of data to pass to the view.
     */
    protected function view($view, $data = []) {
        // Extract data to make variables available in view
        extract($data);

        // Get flash messages
        $flash = getFlash();
        if ($flash) {
            extract(['flash' => $flash]);
        }

        // Add CSRF token to all views
        $csrf_token = generateCsrfToken();

        // Start output buffering
        ob_start();

        // Include the view file
        $viewFile = __DIR__ . "/../views/{$view}.php";
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            throw new Exception("View file not found: {$view}");
        }

        // Get the contents and clean the buffer
        $content = ob_get_clean();

        // Return the rendered view
        return $content;
    }

    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function redirect($controller, $action = '', $params = [], $message = '', $type = 'info') {
        $url = 'index.php?controller=' . urlencode($controller);
        
        if ($action) {
            $url .= '&action=' . urlencode($action);
        }
        
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $url .= '&' . urlencode($key) . '=' . urlencode($value);
            }
        }

        if ($message) {
            $_SESSION['flash'] = [
                'message' => $message,
                'type' => $type
            ];
        }

        header('Location: ' . $url);
        exit();
    }

    protected function buildUrl($controller, $action = '', $params = []) {
        $url = 'index.php?controller=' . urlencode($controller);
        
        if ($action) {
            $url .= '&action=' . urlencode($action);
        }
        
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $url .= '&' . urlencode($key) . '=' . urlencode($value);
            }
        }

        return $url;
    }

    protected function validateCSRF() {
        $token = $_POST['csrf_token'] ?? '';
        if (!verifyCsrfToken($token)) {
            $this->json(['error' => 'Invalid CSRF token'], 403);
        }
    }

    protected function getRequestMethod() {
        return $_SERVER['REQUEST_METHOD'];
    }

    protected function isPost() {
        return $this->getRequestMethod() === 'POST';
    }

    protected function isGet() {
        return $this->getRequestMethod() === 'GET';
    }

    protected function getPostData() {
        return $_POST;
    }

    protected function getQueryParams() {
        return $_GET;
    }

    protected function getRequestBody() {
        return file_get_contents('php://input');
    }

    protected function getJsonBody() {
        $body = $this->getRequestBody();
        return json_decode($body, true);
    }

    protected function requireAuth() {
        requireAuth();
    }

    protected function requireAdmin() {
        requireAdmin();
    }

    protected function getCurrentUser() {
        if (!isLoggedIn()) {
            return null;
        }

        require_once __DIR__ . '/../models/UserModel.php';
        $userModel = new UserModel();
        return $userModel->getUserById($_SESSION['user_id']);
    }

    protected function hasAdminRole() {
        return hasAdminRole();
    }

    protected function isLoggedIn() {
        return isLoggedIn();
    }

    protected function validate($data, $rules) {
        $errors = [];

        foreach ($rules as $field => $rule) {
            // Skip if field doesn't exist and is not required
            if (!isset($data[$field]) && !in_array('required', $rule)) {
                continue;
            }

            $value = $data[$field] ?? '';

            foreach ($rule as $validation) {
                switch ($validation) {
                    case 'required':
                        if (empty($value)) {
                            $errors[$field][] = ucfirst($field) . ' is required';
                        }
                        break;

                    case 'email':
                        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $errors[$field][] = 'Invalid email format';
                        }
                        break;

                    case 'min:8':
                        if (strlen($value) < 8) {
                            $errors[$field][] = ucfirst($field) . ' must be at least 8 characters';
                        }
                        break;

                    default:
                        if (strpos($validation, 'matches:') === 0) {
                            $otherField = substr($validation, 8);
                            if ($value !== ($data[$otherField] ?? '')) {
                                $errors[$field][] = ucfirst($field) . ' must match ' . $otherField;
                            }
                        }
                        break;
                }
            }
        }

        return $errors;
    }

    protected function sanitize($data) {
        $sanitized = [];
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $sanitized[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            } else {
                $sanitized[$key] = $value;
            }
        }
        return $sanitized;
    }
 }
