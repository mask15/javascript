<?php

require_once 'gifLibraryModel.php';
class GifLibrary
{
    public function __construct()
    {
        $this->model = new GifLibraryModel();
    }

    public function showGifsBycategory($category)
    {
        $gifs = $this->model->getGifs($category);
        $this->printTable($gifs);
    }

    public function showSelectedGif($gif_id)
    {
        $gifs = $this->model->getGifById($gif_id);
        $this->printTable($gifs);
    }

    public function printTable($gifs)
    {
        echo "<table class='result_table' cellspacing=0><tr><td>Nombre</td><td>Link</td><td>Gif</td></tr>";
        foreach ($gifs as $gif) {
            echo "<tr><td>" . $gif['gif_name'] . "</td><td><input class='link' id='link" . $gif['gif_id'] . "' type='text' size='60' value='" . $gif['gif_link'] . "' onclick='copyLink(event, ". $gif['gif_id'] .");'/></td><td><img src=" . $gif['gif_link'] . " width='150px' height='150px' alt=" . $gif['gif_name'] . " title=" . $gif['gif_name'] . " /></td></tr>";
        }
        echo "</table>";
    }

    public function fillGifsSelect($category)
    {
        $gifs = $this->model->getGifs($category);

        echo json_encode($gifs);
    }

    public function insertGif($data)
    {
        $res['result'] = $this->model->generateGif($data);
        echo json_encode($res);
    }

    public function insertCategory($data)
    {
        $res['result'] = $this->model->generateCategory($data);
        echo json_encode($res);
    }

    public function getCategories()
    {
        return $this->model->getCategories();
    }
    
    public function getGifs()
    {
        return $this->model->getGifs();
    }
    
    public function loadFile($data)
    {
        foreach ($data as $pos => $info) {
            $gifName = $info[0];
            $gifLink = $info[1];
            $categoryName = $info[2];
            $categoryDesc = $info[3];

            $category = [
                'category_name' => $categoryName,
                'category_desc' => $categoryDesc
            ];

            $category_id = $this->model->getCategoryId($category['category_name']);

            if (empty($category_id)) {
                $category_id = $this->model->generateCategory($category);
            }

            $gif = [
                'gif_name' => $gifName,
                'gif_link' => $gifLink,
                'category_id' => (int) $category_id
            ];

            $gifId = $this->model->getGifByLink($gif['gif_link']);
            
            if (empty($gifId)) {
                $this->model->generateGif($gif);
            }
        }
        
        header('Location: gifLibrary.php');
    }
}

$gifLib = new GifLibrary();

if (isset($_POST['category'])) {
    $category = $_POST['category'];
    $gifLib->showGifsBycategory($category);
}

if (isset($_POST['category_select'])) {
    $category = $_POST['category_select'];
    $gifLib->fillGifsSelect($category);
}

if (isset($_POST['gif_id'])) {
    $gif_id = $_POST['gif_id'];
    $gifLib->showSelectedGif($gif_id);
}

if (isset($_POST['gifName'])) {
    $data = [
        'gif_name' => $_POST['gifName'],
        'gif_link' => $_POST['gifLink'],
        'category_id' => $_POST['gifCat']
    ];

    $gifLib->insertGif($data);
}

if (isset($_POST['catName'])) {
    $data = [
        'category_name' => $_POST['catName'],
        'category_desc' => $_POST['catDesc']
    ];

    $gifLib->insertCategory($data);
}

if (isset($_FILES['fileCsv'])) {
    $tmpFile = $_FILES['fileCsv']['tmp_name'];
    $file = fopen($tmpFile, 'r');

    while ($row = fgetcsv($file, 0, ';')) {
        $data[] = $row;
    }

    $gifLib->loadFile($data);
}
