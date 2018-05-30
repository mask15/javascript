<?php

class GifLibrary
{

    function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;dbname=giphy;charset=utf8mb4', 'okn-user', 'okn-pass');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getCategories()
    {
        $sth = $this->db->prepare('SELECT * FROM categories');
        $sth->execute();
        $res = $sth->fetchAll();
        return $res;
    }

    public function getGifs($category = NULL)
    {
        $where = (empty($category)) ? '' : 'INNER JOIN gifs_categories gc ON g.gif_id = gc.gif_id WHERE gc.category_id IN (' . $category . ')';

        $sql = 'SELECT g.gif_id, g.gif_name, g.gif_link FROM gifs g ' . $where;
        $res = $this->db->query($sql);
        return $res->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getGifById($id)
    {
        $sth = $this->db->prepare('SELECT * FROM gifs WHERE gif_id = :id');
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        $sth->execute();
        $res = $sth->fetchAll();
        return $res;
    }

    public function showGifsBycategory($category)
    {
        $gifs = $this->getGifs($category);
        $this->printTable($gifs);
    }

    public function showSelectedGif($gif_id)
    {
        $gifs = $this->getGifById($gif_id);
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
        $gifs = $this->getGifs($category);

        echo json_encode($gifs);
    }

    public function generateGif($data)
    {
        $name = $data['gif_name'];
        $link = $data['gif_link'];

        $this->db->beginTransaction();

        $sth = $this->db->prepare("INSERT INTO gifs (gif_name,gif_link) VALUES (:name, :link)");
        $sth->bindValue(':name', $name, PDO::PARAM_STR);
        $sth->bindValue(':link', $link, PDO::PARAM_STR);
        $sth->execute();
        $gif = $this->db->lastInsertId();
        if (!empty($gif)) {
            if (!empty($data['category_id'])) {
                $sth2 = $this->db->prepare('INSERT INTO gifs_categories (gif_id,category_id) VALUES (:gifId, :catId)');
                $sth2->bindParam(':gifId', $gif, PDO::PARAM_INT);
                $sth2->bindParam(':catId', $data['category_id'], PDO::PARAM_INT);
                $sth2->execute();

                $relation_id = $this->db->lastInsertId();

                if (empty($relation_id)) {
                    $this->db->rollBack();
                    return false;
                }
            }

        } else {
            $this->db->rollBack();
            return false;
        }
        $this->db->commit();
        return true;
    }

    public function generateCategory($data)
    {
        $catName = $data['category_name'];
        $catDesc = $data['category_desc'];

        $sth = $this->db->prepare('INSERT INTO categories (category_name,category_desc) VALUES (:name, :descr)') ;
        $sth->bindValue(':name', $catName, PDO::PARAM_STR);
        $sth->bindValue(':descr', $catDesc, PDO::PARAM_STR);
        $sth->execute();

        $category = (!empty($this->db->lastInsertId())) ? $this->db->lastInsertId() : false;

        return $category;
    }

    public function insertGif($data)
    {
        $res['result'] = $this->generateGif($data);
        echo json_encode($res);
    }

    public function insertCategory($data)
    {
        $res['result'] = $this->generateCategory($data);
        echo json_encode($res);
    }

    public function getCategoryId($name)
    {
        $sth = $this->db->prepare('SELECT category_id FROM categories WHERE category_name = :name');
        $sth->bindParam(':name', $name, PDO::PARAM_STR);
        $sth->execute();
        $res = $sth->fetchAll();

        return $res[0]['category_id'];
    }

    public function getGifByLink($link)
    {
        $sth = $this->db->prepare('SELECT gif_id FROM gifs WHERE gif_link = :link');
        $sth->bindParam(':link', $link, PDO::PARAM_STR);
        $sth->execute();
        $res = $sth->fetchAll();

        return $res[0]['gif_id'];
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

            $category_id = $this->getCategoryId($category['category_name']);

            if (empty($category_id)) {
                $category_id = $this->generateCategory($category);
            }

            $gif = [
                'gif_name' => $gifName,
                'gif_link' => $gifLink,
                'category_id' => (int) $category_id
            ];

            $gifId = $this->getGifByLink($gif['gif_link']);
            
            if (empty($gifId)) {
                $this->generateGif($gif);
            }

        }
        
        header('Location: gifLibrary.php');
    }
}

$gifLib = new GifLibrary();

if (isset($_POST['category'])){
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

?>
