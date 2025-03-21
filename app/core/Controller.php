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
        // Extract data into variables for the view
        extract($data);



        // Require the view file
        require_once __DIR__ . '/../views/' . $view . '.php';
    }


 }
