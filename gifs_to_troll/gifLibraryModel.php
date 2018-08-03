<?php

class GifLibraryModel
{
    public function __construct()
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

    public function getGifs($category = null)
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
}
