<!--Classe de gestion des projets-->
<?php

class Projet {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // LISTER TOUS LES PROJETS
    public function getAll() {
        $sql = "
            SELECT 
                p.*,
                t.nom AS type
            FROM projet p
            LEFT JOIN type_projet t ON p.type_id = t.id
            ORDER BY p.date DESC
        ";

        return $this->conn->query($sql);
    }

    // RÉCUPÉRER UN PROJET
    public function getById($id) {
        $id = (int)$id;

        $sql = "SELECT * FROM projet WHERE id=$id LIMIT 1";
        $result = $this->conn->query($sql);

        return $result->fetch_assoc();
    }

    // AJOUTER UN PROJET
    public function create($data) {

        $sql = "
            INSERT INTO projet 
            (titre, image, lien_demo, lien_github, type_id, date)
            VALUES (
                '{$data['titre']}',
                '{$data['image']}',
                '{$data['lien_demo']}',
                '{$data['lien_github']}',
                {$data['type_id']},
                '{$data['date']}'
            )
        ";

        return $this->conn->query($sql);
    }

    // MODIFIER UN PROJET
    public function update($id, $data) {

        $id = (int)$id;

        $sql = "
            UPDATE projet SET
                titre='{$data['titre']}',
                image='{$data['image']}',
                lien_demo='{$data['lien_demo']}',
                lien_github='{$data['lien_github']}',
                type_id={$data['type_id']},
                date='{$data['date']}'
            WHERE id=$id
        ";

        return $this->conn->query($sql);
    }

    // SUPPRIMER UN PROJET
    public function delete($id) {

        $id = (int)$id;

        return $this->conn->query("
            DELETE FROM projet WHERE id=$id
        ");
    }
}